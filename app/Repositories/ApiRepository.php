<?php

namespace App\Repositories;

use App\Models\{Contact, Label, Substrate, Third};
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

    public function searchCustomersForAutocomplete(Array $datas)
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
        $filteredEthicContacts = collect();
        if ($datas['ethic']) {
            $filteredEthicContacts = $this->findByIdThirdContact($datas['company'], $datas['third']);
        }
        $filteredEstibotContacts = Contact::where('third_id', $datas['third'])->get();
        $filteredEstibotContacts = $filteredEstibotContacts->map(function($item) {
            $item['ethic'] = false;
            return $item;
        });

        $merged = $filteredEthicContacts->merge($filteredEstibotContacts);
        return $merged->sortBy('name');
    }

    public function getThirdLabels(Array $datas)
    {
        $filteredEthicLabels = collect();
        if ($datas['ethic']) {
            $filteredEthicLabels = $this->findByIdThirdLabel($datas['company'], $datas['third']);
        }
        $filteredEstibotLabels = Label::where('third_id', $datas['third'])->get();
        $filteredEstibotLabels = $filteredEstibotLabels->map(function($item) {
            $item->offsetSet('ethic', false);
            return $item;
        });

        $merged = $filteredEthicLabels->merge($filteredEstibotLabels);

        return $merged;
    }

    public function getPrintings(Array $datas)
    {
        $response = $this->client->request('GET', 'http://89.92.37.229/API/POSTEPRODUCTION/001/null/null');

        if ($response->getStatusCode() === 200) {
            $collection = collect(json_decode($response->getBody()))->map(function($item) {
                $printing = collect();
                $printing->offsetSet('id', $item->CODEPOSTE);
                $printing->offsetSet('name', $item->LIBELLE);
                $printing->offsetSet('type', $item->TYPEDEPOSTE);
                return $printing;
            });
            return collect($collection->where('type', 3));
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the content cannot be loaded'
            ], $response->getStatusCode());
        }
    }

    public function getSubstratesSearchCriteria(Array $datas)
    {
        $substrates = $this->getSubstrates($datas);

        $substrateSearchCriteria = array();


        // Families
        $allSubstratesFamilies = $substrates->map(function ($item) {
            return collect($item)->only('family');
        });
        $substrateSearchCriteria['families'] = $allSubstratesFamilies->unique('family')->sortBy('family');

        // Types
        $allSubstratesTypes = $substrates->map(function ($item) {
            return collect($item)->only('type');
        });
        $substrateSearchCriteria['types'] = $allSubstratesTypes->unique('type')->sortBy('type');

        // Colors
        $allSubstratesColors = $substrates->map(function ($item) {
            return collect($item)->only('color');
        });
        $substrateSearchCriteria['colors'] = $allSubstratesColors->unique('color')->sortBy('color');


        // Weights
        $allSubstratesWeights = $substrates->map(function ($item) {
            return collect($item)->only('weight');
        });
        $allSubstratesWeights = $allSubstratesWeights->unique('weight');
//
//        $allEstibotWeights = Substrate::all();
//        $allEstibotWeights = $allEstibotWeights->map(function ($weight) {
//            return collect($weight->toArray())
//                ->only(['weight'])
//                ->all();
//        });
//        $merged = $allSubstratesWeights->merge($allEstibotWeights);
//        $substrateSearchCriteria['weights']  = $merged->sortBy('weight');

        // All substrates suppliers
//        $allEthicSubstratesSuppliers = $this->allSubstratesSuppliers($datas);
//
//        $allEthicSubstratesSuppliers = $allEthicSubstratesSuppliers->map(function ($item) {
//            return collect($item)->only('RAISONSOCIALE');
//        });
//        $substrateSearchCriteria['suppliers'] = $allEthicSubstratesSuppliers->sortBy('RAISONSOCIALE');

        return $substrateSearchCriteria;
    }

    public function searchSubstratesForAutocomplete(Array $datas)
    {
        $queryString = $datas['queryString'];

        $substrates = $this->getSubstrates($datas);

        $substrates = collect($substrates->map(function ($item) use ($queryString) {
            return collect($item)->only(['name']);
        }));

        $substrates = $this->searchBySubstrateName($substrates, $queryString);

        return $substrates->sortBy('name')->take(10);
    }

    public function getFilteredSubstrates(Array $datas)
    {
        $substrates = $this->getSubstrates($datas);

        $filters = $datas['filters'];

        if (!empty($filters['family'])) {
            $substrates = collect($substrates->where('family', $filters['family'])->all());
        }

        if (!empty($filters['type'])) {
            $substrates = collect($substrates->where('type', $filters['type'])->all());
        }

        if (!empty($filters['color'])) {
            $substrates = collect($substrates->where('color', $filters['color'])->all());
        }

        if (!empty($filters['weight'])) {
            $substrates = collect($substrates->where('weight', $filters['weight'])->all());
        }

//        if (!empty($filters['supplier'])) {
//            $ethicSubstrates = collect($ethicSubstrates->where('supplier', $filters['supplier'])->all());
//        }

        if (!empty($filters['name'])) {
            $substrates = $this->searchBySubstrateName($substrates, $filters['name']);
        }

        $collection = $substrates->sortBy('name');
        return new LengthAwarePaginator($collection->forPage(intval($datas['page']), 15), $collection->count(), 15, intval($datas['page']));
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
                $contacts['ethic'] = true;
                $contacts['id'] = $item->NOCONTACT;
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
            return collect(json_decode($response->getBody()))->map(function($item) {
                $label = collect();
                $label->offsetSet('ethic', true);
                $label->offsetSet('id', $item->REFARTICLE);
                $label->offsetSet('variant', $item->VARARTICLE);
                $label->offsetSet('reference', $item->CLIENTFINAL);
                $label->offsetSet('name', $item->LIBELLEVARIANTE);
                $label->offsetSet('width', $item->LAIZEDIMENSION);
                $label->offsetSet('length', $item->AVANCEDIMENSION);
                $label->offsetSet('number_colors', $item->NBCOULRECTO);
                $label->offsetSet('packing', $item->ROULEAUXDE);
                return $label;
            });
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the content cannot be loaded'
            ], $response->getStatusCode());
        }
    }

    private function getSubstrates(Array $datas)
    {
        if ($datas['ethic']) {
            $substrates = $this->findSubstratesByLabelId($datas);
        } else {
            if (!empty($datas['label'])) $substrates = collect(Label::find($datas['label'])->substrate);

            if (empty($substrates))  $substrates = $this->allSubstrates($datas);
        }

        return $substrates;
    }

    private function findSubstratesByLabelId($datas)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/ETIQUETTESUPPORT/' . $datas['company'] . '/' . $datas['establishment'] . '/' . $datas['label'] . '/' . $datas['variant']);
            return collect(json_decode($response->getBody()))->map(function($item) {
                $substrate = collect();
                $substrate->offsetSet('id', $item->IDREFSTOCK);
                $substrate->offsetSet('ethic', true);
                $substrate->offsetSet('family', $item->FAMILLE);
                $substrate->offsetSet('type', $item->TYPE);
                $substrate->offsetSet('name', $item->LIBELLE);
                $substrate->offsetSet('color', $item->COULEUR);
                $substrate->offsetSet('weight', $item->GRAMMAGE);
                $substrate->offsetSet('width', $item->LAIZEDIMENSION);
                $substrate->offsetSet('price', $item->PRIXDEVIS);
                return $substrate;
            });
        } catch(\Exception $e) {
            return $this->findFamiliesSubstratesByLabelId($datas);
        }
    }

    private function findFamiliesSubstratesByLabelId($datas)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/SUPPORTFAMILLE/' . $datas['company'] . '/' . $datas['establishment'] . '/' . $datas['label'] . '/' . $datas['variant']);
            return collect(json_decode($response->getBody()))->map(function($item) {
                $substrate = collect();
                $substrate->offsetSet('id', $item->IDREFSTOCK);
                $substrate->offsetSet('ethic', true);
                $substrate->offsetSet('family', $item->FAMILLE);
                $substrate->offsetSet('type', $item->TYPE);
                $substrate->offsetSet('name', $item->LIBELLE);
                $substrate->offsetSet('color', $item->COULEUR);
                $substrate->offsetSet('weight', $item->GRAMMAGE);
                $substrate->offsetSet('width', $item->LAIZEDIMENSION);
                $substrate->offsetSet('price', $item->PRIXDEVIS);
                return $substrate;
            });
        } catch(\Exception $e) {
            return $this->allSubstrates($datas);
        }
    }

    private function allSubstrates($datas)
    {
        $allEstibotSubstrates = Substrate::all();
        $allEstibotSubstrates = collect($allEstibotSubstrates->map(function($item) {
            $item['ethic'] = false;
            $item['family'] = '';
            $item['type'] = '';
            $item['color'] = '';
            return $item;
        }));

        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/SUPPORT/' . $datas['company'] . '/' . $datas['establishment']);
            $allEthicSubstrates = collect(json_decode($response->getBody()))->map(function($item) {
                $substrate = collect();
                $substrate->offsetSet('id', $item->IDREFSTOCK);
                $substrate->offsetSet('ethic', true);
                $substrate->offsetSet('family', $item->FAMILLE);
                $substrate->offsetSet('type', $item->TYPE);
                $substrate->offsetSet('name', $item->LIBELLE);
                $substrate->offsetSet('color', $item->COULEUR);
                $substrate->offsetSet('weight', $item->GRAMMAGE);
                $substrate->offsetSet('width', $item->LAIZEDIMENSION);
                $substrate->offsetSet('price', $item->PRIXDEVIS);
                return $substrate;
            });

            $merged = $allEthicSubstrates->merge($allEstibotSubstrates);
            return $merged->sortBy('name');
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return $allEstibotSubstrates;
        }
    }

    private function allSubstratesSuppliers($datas)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/FOURNISSEUR/' . $datas['company'] . '/' . $datas['establishment']);
            return collect(json_decode($response->getBody()));
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return $responseBodyAsString;
        }
    }

    private function searchBySubstrateName($substrates, $queryString)
    {
        return $substrates->filter(function($element) use ($queryString) {
            if (stripos(strtolower($element['name']), strtolower($queryString)) !== false) {
                return $element;
            }
        });
    }
}
