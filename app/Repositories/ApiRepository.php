<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Models\Third;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiRepository
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getCustomers(Array $datas)
    {
        $filters = $datas['filters'];

        $estibot = Third::all();
//        $estibot = $estibot->map(function ($item) {
//            return collect($item)->only([
//                    'id',
//                    'alias',
//                    'name',
//                    'address_line1',
//                    'address_line2',
//                    'address_line3',
//                    'zipcode',
//                    'city'
//                ])
//                ->all();
//        });
        $estibot = $estibot->map(function($item) {
            $item['ethic'] = false;
            $item['type'] = "P";
            return $item;
        });


        $ethic = $this->allCustomers($datas['company']);

        if (!empty($filters['name'])) {
            $resultResearch = $this->searchByCustomerName($estibot, $ethic, $filters['name']);
            $estibot = $resultResearch['estibot'];
            $ethic = $resultResearch['ethic'];
        }

        if (!empty($filters['zipcode'])) {
            $resultResearch = $this->searchByCustomerZipcode($estibot, $ethic, $filters['zipcode']);
            $estibot = $resultResearch['estibot'];
            $ethic = $resultResearch['ethic'];
        }

        $merged = $ethic->merge($estibot);
        $collection = $merged->sortBy('name');
        return new LengthAwarePaginator($collection->forPage(intval($datas['page']), 15), $collection->count(), 15, intval($datas['page']));
    }

    public function searchCustomers(Array $datas)
    {
        $queryString = $datas['queryString'];

        $estibot = Third::all();

        $estibotSubset = $estibot->map(function ($item) use ($queryString) {
            return collect($item->toArray())
                ->only(['alias', 'name'])
                ->all();
        });

        $ethic = $this->allCustomers($datas['company']);
        $ethicSubset = $ethic->map(function ($item) use ($queryString) {
            return collect($item)
                ->only(['alias', 'name'])
                ->all();
        });

        $result = $this->searchByCustomerName($estibotSubset, $ethicSubset, $queryString);
        $ethicSubset = $result['ethic'];
        $estibotSubset = $result['estibot'];

        $merged = $ethicSubset->merge($estibotSubset);
        return $merged->sortBy('name')->take(10);
    }

    public function getThirdContacts(Array $datas)
    {
        if ($datas['ethic']) {
            $filteredContacts = $this->findByIdThirdContact($datas['company'], $datas['third']);
        } else {
            $filteredContacts = Contact::where('third_id', $datas['third']);
        }

        return $filteredContacts;
    }

    public function getThirdLabels(Array $datas)
    {
        if ($datas['ethic']) {
            $filteredLabels = $this->findByIdThirdLabel($datas['company'], $datas['third']);
        } else {
            $filteredLabels = Contact::where('third_id', $datas['third']);
        }

        return $filteredLabels;
    }

    public function getFinishings(Array $datas)
    {
        $response = $this->client->request('GET', 'http://89.92.37.229/API/FINITION/'.$datas['company']);

        if ($response->getStatusCode() === 200) {
            $finishings = array();
            return collect(json_decode($response->getBody()))->map(function($item) use ($finishings) {
                $finishings['id'] = utf8_encode($item->CODEOPEFAB);
                $finishings['name'] = utf8_encode($item->LIBELLE);
                $finishings['consumable'] = utf8_encode($item->CONSOMMABLE);
                return $finishings;
            });
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the content cannot be loaded'
            ], $response->getStatusCode());
        }
    }

    public function getPrintings(Array $datas)
    {
        $response = $this->client->request('GET', 'http://89.92.37.229/API/POSTEPRODUCTION/'.$datas['company']);

        if ($response->getStatusCode() === 200) {
            $printings = array();
            return collect(json_decode($response->getBody()))->map(function($item) use ($printings) {
                $printings['id'] = utf8_encode($item->CODEPOSTE);
                $printings['name'] = utf8_encode($item->sLIBELLE);
                return $printings;
            });
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the content cannot be loaded'
            ], $response->getStatusCode());
        }

        return $filteredContacts;
    }

    private function searchByCustomerName($estibot, $ethic, $queryString) {
        $estibot = $estibot->filter(function($element) use ($queryString) {
            if (stripos(strtoupper($element['alias']), strtoupper($queryString)) !== false || stripos(strtolower($element['name']), strtolower($queryString)) !== false) {
                return $element;
            }
        });

        $ethic = $ethic->filter(function($element) use ($queryString) {
            if (stripos(strtoupper($element['alias']), strtoupper($queryString)) !== false || stripos(strtolower($element['name']), strtolower($queryString)) !== false) {
                return $element;
            }
            // with reject instead of filter
//            return (stripos($element['alias'], $queryString) === false AND stripos($element['name'], $queryString) === false);
        });

        $result = array();
        $result['estibot'] = $estibot;
        $result['ethic'] = $ethic;
        return $result;
    }

    private function searchByCustomerZipcode($estibot, $ethic, $queryString) {
        $estibot = $estibot->filter(function($element) use ($queryString) {
            return (substr($element['zipcode'], 0, strlen($queryString)) === $queryString);
        });

        $ethic = $ethic->filter(function($element) use ($queryString) {
            return (substr($element['zipcode'], 0, strlen($queryString)) === $queryString);
        });

        $result = array();
        $result['estibot'] = $estibot;
        $result['ethic'] = $ethic;
        return $result;
    }

    private function allCustomers($company)
    {
        $response = $this->client->request('GET', 'http://89.92.37.229/API/CLIENT/' . $company);
        if ($response->getStatusCode() === 200) {
            $customer = array();
            return collect(json_decode($response->getBody()))->map(function($item) use ($customer) {
                $customer['ethic'] = true;
                $customer['type'] = "C";
                $customer['id'] = utf8_encode($item->NOCOMPTE);
                $customer['alias'] = utf8_encode($item->MOTCLE);
                $customer['name'] = utf8_encode($item->RAISONSOCIALE);
                $customer['addressLine1'] = utf8_encode($item->ADRESSE1);
                $customer['addressLine2'] = utf8_encode($item->ADRESSE2);
                $customer['addressLine3'] = utf8_encode($item->ADRESSE3);
                $customer['zipcode'] = utf8_encode($item->CODEPOSTAL);
                $customer['city'] = utf8_encode($item->VILLE);
                $customer['email'] = utf8_encode($item->EMAIL);
                return $customer;
            });
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the content cannot be loaded'
            ], $response->getStatusCode());
        }

    }

    private function findByIdThirdContact($company, $third)
    {

        $response = $this->client->request('GET', 'http://89.92.37.229/API/CONTACT/'.$company.'/'.$third);
        if ($response->getStatusCode() === 200) {
            $contacts = array();
            return collect(json_decode($response->getBody()))->map(function($item) use ($contacts) {
                $contacts['id'] = utf8_encode($item->NOCONTACT);
                $contacts['civility'] = utf8_encode($item->CIVILITE);
                $contacts['name'] = utf8_encode($item->NOMPRENOM);
                $contacts['surname'] = "";
                $contacts['profession'] = utf8_encode($item->FONCTIONCONTACT);
                $contacts['email'] = utf8_encode($item->EMAIL);
                $contacts['mobile'] = utf8_encode($item->TELEPHONEMOBILE);
                $contacts['phone'] = utf8_encode($item->TELEPHONE);
                $contacts['default'] = $item->PRINCIPAL;
                return $contacts;
            });
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the content cannot be loaded'
            ], $response->getStatusCode());
        }
    }

    private function findByIdThirdLabel($company, $third)
    {
        $response = $this->client->request('GET', 'http://89.92.37.229/API/ETIQUETTE/'.$company.'/'.$third);
        if ($response->getStatusCode() === 200) {
            return collect(json_decode($response->getBody()));
            $contacts = array();
            return collect(json_decode($response->getBody()))->map(function($item) use ($contacts) {
                $contacts['id'] = utf8_encode($item->NOCONTACT);
                $contacts['civility'] = $item->CIVILITE;
                $contacts['name'] = utf8_encode($item->NOMPRENOM);
                $contacts['surname'] = "";
                $contacts['profession'] = utf8_encode($item->FONCTIONCONTACT);
                $contacts['email'] = utf8_encode($item->EMAIL);
                $contacts['mobile'] = utf8_encode($item->TELEPHONEMOBILE);
                $contacts['phone'] = utf8_encode($item->TELEPHONE);
                $contacts['default'] = $item->PRINCIPAL;
                return $contacts;
            });
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the content cannot be loaded'
            ], $response->getStatusCode());
        }
    }
}
