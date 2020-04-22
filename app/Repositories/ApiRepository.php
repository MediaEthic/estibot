<?php

namespace App\Repositories;

use App\Models\{Contact, FinishingLabel, Label, Substrate, Third};
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
            $filteredEthicContacts = collect($this->findByIdThirdContact($datas['company'], $datas['third']));
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
        $printings = collect($this->getWorkstations($datas));
        return $printings->where('type', 3)->all();
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
        // If label is from ethic database
        if ($datas['ethic']) {
            $finishings = $this->getLabelDies($datas);
        } else {
            if (!empty($datas['label'])) {
                $label = Label::findOrFail($datas['label']);
                if ($label->cutting_type === 'estibot') {
                    $finishings = array();
                    $finishings['database']['cuttings'] = Label::where('id', $datas['label'])->cutting;
                }
            }
            if (empty($finishings)) {
                $finishings = $this->getAllFinishings($datas);
            }
        }

        return $finishings;
    }

    public function getReworkings(Array $datas)
    {
        $workstations = collect($this->getWorkstations($datas));
        return $workstations->where('reworking', 1)->all();
    }

    public function getCadences(Array $datas)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/CALAGE/' . $datas['company'] . '/' . $datas['finishing'] . '/' . $datas['workstation']);

            return collect(json_decode($response->getBody()))->map(function($item) use($datas) {
                $cadence = collect();
                $cadence->offsetSet('workstation_id', $item->CODEPOSTE);

                $datas['class'] = $item->CODEPOSTE;
                $workstation = collect($this->getWorkstations($datas)->first());

                $cadence->offsetSet('workstation_name', $workstation['name']);
                $cadence->offsetSet('workstation_hourly_rate', $workstation['hourly_rate']);
                $cadence->offsetSet('workstation_size_papermaxx', $workstation['size_papermaxx']);
                $cadence->offsetSet('finishing_id', $item->CODEOPEFAB);
                $cadence->offsetSet('overlay_sheet', $item->PASSECALAGE);
                $cadence->offsetSet('makeready_times', $item->TEMPSCALAGE);
                if ($item->UNITE === 1) {
                    $unitCadence = "striking";
                } else if ($item->UNITE === 2) { // if === 3 -> sheet
                    $unitCadence = "meter";
                } else {
                    $unitCadence = "error";
                }
                $cadence->offsetSet('unit_cadence', $unitCadence);
                $cadence->offsetSet('cadence', $item->CADENCE);
                return $cadence;
            });
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return [];
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

    public function allCustomers($company)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/CLIENT/' . $company);
            return collect(json_decode($response->getBody()))->map(function($item) {
                $customer = collect();
                $customer->offsetSet('ethic', true);
                $customer->offsetSet('type', $item->TYPECOMPTE);
                $customer->offsetSet('id', $item->NOCOMPTE);
                $customer->offsetSet('alias', $item->MOTCLE);
                $customer->offsetSet('name', $item->RAISONSOCIALE);
                $customer->offsetSet('addressLine1', $item->ADRESSE1);
                $customer->offsetSet('addressLine2', $item->ADRESSE2);
                $customer->offsetSet('addressLine3', $item->ADRESSE3);
                $customer->offsetSet('zipcode', $item->CODEPOSTAL);
                $customer->offsetSet('city', $item->VILLE);
                $customer->offsetSet('email', $item->EMAIL);
                return $customer;
            });
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return [];
        }
    }

    public function findByIdThirdContact($company, $third)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/CONTACT/'.$company.'/'.$third);
            return collect(json_decode($response->getBody()))->map(function($item) {
                $contact = collect();
                $contact->offsetSet('ethic', true);
                $contact->offsetSet('id', $item->NOCONTACT);
                $contact->offsetSet('civility', $item->CIVILITE);
                $contact->offsetSet('name', $item->NOMPRENOM);
                $contact->offsetSet('surname', '');
                $contact->offsetSet('profession', $item->FONCTIONCONTACT);
                $contact->offsetSet('email', $item->EMAIL);
                $contact->offsetSet('mobile', $item->TELEPHONEMOBILE);
                $contact->offsetSet('phone', $item->TELEPHONE);
                $contact->offsetSet('default', $item->PRINCIPAL);
                return $contact;
            });
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return [];
        }
    }

    public function findByIdThirdLabel($company, $third)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/ETIQUETTE/'.$company.'/'.$third);
            $collection = collect(json_decode($response->getBody()))->map(function($item) {
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
            return $collection->sortByDesc('id')->sortByDesc('variant');
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return [];
        }
    }

    public function getWorkstations(Array $datas)
    {
        $listClass = $datas['class'];
        if ($datas['class'] !== 'null') {
            $listClass = explode(",", $datas['class']);
            $listClass = "'" . implode("','", $listClass) . "'";
        }
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/POSTEPRODUCTION/' . $datas['company'] . '/' . $listClass);
            return collect(json_decode($response->getBody()))->map(function($item) {
                $workstation = collect();
                $workstation->offsetSet('id', $item->CODEPOSTE);
                $workstation->offsetSet('name', $item->LIBELLE);
                $workstation->offsetSet('size_papermaxx', $item->LAIZEMAXIMUM);
                $workstation->offsetSet('size_papermaxy', $item->TEMPSLAVAGEPARGROUPE);
                $workstation->offsetSet('printable_areax', $item->LAIZEIMPRESSION);
                $workstation->offsetSet('reworking', $item->CELLULEDEREPRISE);
                $workstation->offsetSet('cadence', $item->CADENCE);
                if ($item->UNITECADENCE === 1) {
                    $unitCadence = "striking";
                } else if ($item->UNITECADENCE === 2) { // if === 3 -> sheet
                    $unitCadence = "meter";
                } else {
                    $unitCadence = "error";
                }
                $workstation->offsetSet('unit_cadence', $unitCadence);
                $workstation->offsetSet('number_units', $item->NBGROUPES);
                $workstation->offsetSet('type', $item->TYPEDEPOSTE);
                $workstation->offsetSet('overlay_sheet', $item->PASSAGECALAGE1);
                $workstation->offsetSet('plate', $item->PRIXPLAQUEOUCLICHE);
                $workstation->offsetSet('makeready_times', $item->TEMPSREGLAGEBOBINE);
                $workstation->offsetSet('makeready_plate', $item->TEMPSCALAGEPLAQUEOUCLICHE);
                $workstation->offsetSet('washing_times', $item->TEMPSLAVAGEPARGROUPE);
                $workstation->offsetSet('hourly_rate', $item->TAUX2);
                return $workstation;
            });

        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return $responseBodyAsString;
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

    private function getLabelDies($datas)
    {
        try {
            $datas['class'] = "null";
            $allReworkings = $this->getReworkings($datas);

            $response = $this->client->request('GET', 'http://89.92.37.229/API/ETIQUETTEOUTIL/' . $datas['company'] . '/' . $datas['label'] . '/' . $datas['variant']);
            $finishings = collect(json_decode($response->getBody()))->map(function($item) {
                $finishing = collect();
                $finishing->offsetSet('id', $item->CODEOPEFAB);
                $finishing->offsetSet('name', $item->LIBELLE_OPEFAB);
                $finishing->offsetSet('reference', $item->IDREFSTOCK);
                $finishing->offsetSet('list_consumable', $item->LISTECODECLASSECONSO);
                $finishing->offsetSet('list_cutting', $item->LISTECODECLASSEOUTIL);
                $finishing->offsetSet('class', $item->CLASSE_BASE);
                return $finishing;
            });


            $labelFinishings = array();
            foreach ($finishings as $finishing) {
                $labelFinishing = collect();
                $labelFinishing->offsetSet('id', $finishing['id']);
                $labelFinishing->offsetSet('name', $finishing['name']);

                if ($finishing['class'] === "Outil") {
                    $labelFinishingDie = collect($this->getFinishingDieLabel($datas['company'], $finishing['reference'])[0]);
                    if (!$labelFinishingDie['cutting']) {
                        $labelFinishing->offsetSet('die', $labelFinishingDie);
                    }
                    $labelFinishing->offsetSet('presence_consumable', false);
                } else if ($finishing['class'] === "Consommable") {
                    $labelFinishing->offsetSet('presence_consumable', true);
                    $labelFinishingConsumable = $this->getFinishingConsumableLabel($datas['company'], $finishing['reference'])[0];
                    $labelFinishing->offsetSet('consumable', $labelFinishingConsumable);
                }

                $labelFinishing->offsetSet('reworking', ''); // for workflow form

                $datas['finishing'] = $finishing['id'];
                $datas['workstation'] = "null";
                $labelFinishingReworkings = collect($this->getCadences($datas));
                if (count($labelFinishingReworkings) === 0) {
                    $labelFinishingReworkings = $allReworkings;
                }
                $labelFinishing->offsetSet('reworkings', $labelFinishingReworkings);

                $labelFinishing->offsetSet('hasFocus', false);
                if (!$labelFinishingDie['cutting']) {
                    $labelFinishings['form'][] = $labelFinishing;
                } else {
                    $labelFinishings['database']['cuttings'][] = $labelFinishingDie;
                }
            }
            return $labelFinishings;
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return $this->getLabelFinishings($datas);
        }
    }

    private function getFinishingDieLabel($company, $reference)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/ETIQUETTEOUTILDECOUPE/' . $company . '/' . $reference);
            return collect(json_decode($response->getBody()))->map(function($item) {
                $finishingDie = collect();
                $finishingDie->offsetSet('id', $item->IDREFSTOCK);
                $finishingDie->offsetSet('ethic', true);
//                $finishingDie->offsetSet('stock', $item->REFSTOCK);
                $finishingDie->offsetSet('name', $item->LIBELLE);
                $finishingDie->offsetSet('width', $item->LAIZEDIMENSION);
                $finishingDie->offsetSet('length', $item->AVANCEDIMENSION);
                $finishingDie->offsetSet('bleed_width', $item->LAIZEENTREPOSE);
                $finishingDie->offsetSet('bleed_length', $item->AVANCEENTREPOSE);
                $finishingDie->offsetSet('pose_width', $item->LAIZENBPOSES);
                $finishingDie->offsetSet('pose_length', $item->AVANCENBPOSES);
                $finishingDie->offsetSet('list_workstation', $item->LISTEDESPOSTES);
                $finishingDie->offsetSet('cutting', $item->OUTILDEDECOUPE);
                $finishingDie->offsetSet('price', 0);
                return $finishingDie;
            });
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return $responseBodyAsString;
        }
    }

    private function getFinishingConsumableLabel($company, $reference)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/ETIQUETTECONSOMMABLE/' . $company . '/' . $reference);
            return collect(json_decode($response->getBody()))->map(function($item) {
                $finishingConsumable = collect();
                $finishingConsumable->offsetSet('id', $item->IDREFSTOCK);
                $finishingConsumable->offsetSet('name', $item->LIBELLE);
                $finishingConsumable->offsetSet('price', $item->PRXDEVIS);
                return $finishingConsumable;
            });
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return $responseBodyAsString;
        }
    }

    private function getLabelFinishings($datas)
    {
        try {
            $datas['class'] = "null";
            $allReworkings = $this->getReworkings($datas);

            $response = $this->client->request('GET', 'http://89.92.37.229/API/ETIQUETTEFINITION/' . $datas['company'] . '/' . $datas['label'] . '/' . $datas['variant']);
            $finishings = collect(json_decode($response->getBody()))->map(function($item) {
                $finishing = collect();
                $finishing->offsetSet('id', $item->CODEOPEFAB);
                $finishing->offsetSet('name', $item->LIBELLE);
                $finishing->offsetSet('presence_die', $item->OUTIL);
                $finishing->offsetSet('list_die', $item->LISTECODECLASSEOUTIL);
                $finishing->offsetSet('presence_consumable', $item->CONSOMMABLE);
                $finishing->offsetSet('list_consumable', $item->LISTECODECLASSECONSO);
                return $finishing;
            });

            $labelFinishings = array();
            foreach ($finishings as $finishing) {
                $labelFinishing = collect();
                $labelFinishing->offsetSet('id', $finishing['id']);
                $labelFinishing->offsetSet('name', $finishing['name']);
                if ($finishing['presence_die'] === 1) {
                    $labelFinishing->offsetSet('presence_die', true);
                    $listDies = explode(",", $finishing['list_die']);
                    $listDies = "'" . implode("','", $listDies) . "'";
                    $labelFinishingDie = collect($this->getFinishingDies($datas, $listDies));
                    if (!empty($labelFinishingDie['dies'])) $labelFinishing->offsetSet('die', $labelFinishingDie['dies']);
                } else {
                    $labelFinishing->offsetSet('presence_die', false);
                }
                if ($finishing['presence_consumable'] === 1) {
                    $labelFinishing->offsetSet('presence_consumable', true);
                    $listConsumables = explode(",", $finishing['list_consumable']);
                    $listConsumables = "'" . implode("','", $listConsumables) . "'";
                    $labelFinishingConsumable = collect($this->getFinishingConsumables($datas, $listConsumables));
                    $labelFinishing->offsetSet('consumable', $labelFinishingConsumable);
                } else {
                    $labelFinishing->offsetSet('presence_consumable', false);
                }



                $datas['finishing'] = $finishing['id'];
                $datas['workstation'] = "null";
                $labelFinishingReworkings = collect($this->getCadences($datas));
                if (count($labelFinishingReworkings) === 0) {
                    $labelFinishingReworkings = $allReworkings;
                }
                $labelFinishing->offsetSet('reworkings', $labelFinishingReworkings);




                if (!empty($labelFinishingDie['cuttings'])) {
                    $labelFinishings['database']['cuttings'] = $labelFinishingDie['cuttings'];
                }

                $labelFinishings['database']['finishings'][] = $labelFinishing;
            }

            return $labelFinishings;
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return $this->getAllFinishings($datas);
        }
    }

    private function getAllFinishings($datas)
    {
        try {
            $datas['class'] = "null";
            $allReworkings = $this->getReworkings($datas);

            $response = $this->client->request('GET', 'http://89.92.37.229/API/FINITION/' . $datas['company']);
            $finishings = collect(json_decode($response->getBody()))->map(function($item) {
                $finishing = collect();
                $finishing->offsetSet('id', $item->CODEOPEFAB);
                $finishing->offsetSet('name', $item->LIBELLE);
                $finishing->offsetSet('presence_die', $item->OUTIL);
                $finishing->offsetSet('list_die', $item->LISTECODECLASSEOUTIL);
                $finishing->offsetSet('presence_consumable', $item->CONSOMMABLE);
                $finishing->offsetSet('list_consumable', $item->LISTECODECLASSECONSO);
                return $finishing;
            });


            $labelFinishings = array();
            foreach ($finishings as $finishing) {
                $labelFinishing = collect();
                $labelFinishing->offsetSet('id', $finishing['id']);
                $labelFinishing->offsetSet('name', $finishing['name']);
                if ($finishing['presence_die'] === 1) {
                    $labelFinishing->offsetSet('presence_die', true);
                    $listDies = explode(",", $finishing['list_die']);
                    $listDies = "'" . implode("','", $listDies) . "'";
                    $labelFinishingDie = collect($this->getFinishingDies($datas, $listDies));
                    if (!empty($labelFinishingDie['dies'])) $labelFinishing->offsetSet('die', $labelFinishingDie['dies']);
                } else {
                    $labelFinishing->offsetSet('presence_die', false);
                }
                if ($finishing['presence_consumable'] === 1) {
                    $labelFinishing->offsetSet('presence_consumable', true);
                    $listConsumables = explode(",", $finishing['list_consumable']);
                    $listConsumables = "'" . implode("','", $listConsumables) . "'";
                    $labelFinishingConsumable = collect($this->getFinishingConsumables($datas, $listConsumables));
                    $labelFinishing->offsetSet('consumable', $labelFinishingConsumable);
                } else {
                    $labelFinishing->offsetSet('presence_consumable', false);
                }



                $datas['finishing'] = $finishing['id'];
                $datas['workstation'] = "null";
                $labelFinishingReworkings = collect($this->getCadences($datas));
                if (count($labelFinishingReworkings) === 0) {
                    $labelFinishingReworkings = $allReworkings;
                }
                $labelFinishing->offsetSet('reworkings', $labelFinishingReworkings);



                if (!empty($labelFinishingDie['cuttings'])) {
                    $labelFinishings['database']['cuttings'] = $labelFinishingDie['cuttings'];
                }

                $labelFinishings['database']['finishings'][] = $labelFinishing;
            }
            return $labelFinishings;
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return $responseBodyAsString;
        }
    }

    private function getFinishingDies($datas, $list)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/OUTILDECOUPE/' . $datas['company'] . '/' . $datas['establishment'] . '/' . $list);
            $dies = collect(json_decode($response->getBody()))->map(function($item) {
                $finishingDie = collect();
                $finishingDie->offsetSet('id', $item->IDREFSTOCK);
                $finishingDie->offsetSet('ethic', true);
                $finishingDie->offsetSet('stock', $item->REFSTOCK);
                $finishingDie->offsetSet('name', $item->LIBELLE);
                $finishingDie->offsetSet('width', $item->LAIZEDIMENSION);
                $finishingDie->offsetSet('length', $item->AVANCEDIMENSION);
                $finishingDie->offsetSet('bleed_width', $item->LAIZEENTREPOSE);
                $finishingDie->offsetSet('bleed_length', $item->AVANCEENTREPOSE);
                $finishingDie->offsetSet('pose_width', $item->LAIZENBPOSES);
                $finishingDie->offsetSet('pose_length', $item->AVANCENBPOSES);
                $finishingDie->offsetSet('list_workstation', $item->LISTEDESPOSTES);
                $finishingDie->offsetSet('cutting', $item->OUTILDEDECOUPE);
                return $finishingDie;
            });

            $finishing = array();
            foreach ($dies as $die) {
                if ($die['cutting'] === 1) {
                    $finishing['cuttings'][] = $die;
                } else {
                    $finishing['dies'][] = $die;
                }
            }
            return $finishing;
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return [];
        }
    }

    private function getFinishingConsumables($datas, $list)
    {
        try {
            $response = $this->client->request('GET', 'http://89.92.37.229/API/CONSOMMABLE/' . $datas['company'] . '/' . $datas['establishment'] . '/' . $list);
            return collect(json_decode($response->getBody()))->map(function($item) {
                $finishingConsumable = collect();
                $finishingConsumable->offsetSet('id', $item->IDREFSTOCK);
                $finishingConsumable->offsetSet('name', $item->LIBELLE);
                $finishingConsumable->offsetSet('price', $item->PRXDEVIS);
                $finishingConsumable->offsetSet('ethic', true);
                return $finishingConsumable;
            });
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
//            $response = $exception->getResponse();
//            $responseBodyAsString = $response->getBody()->getContents();
//            return $responseBodyAsString;
        }
    }
}
