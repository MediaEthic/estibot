<?php

namespace App\Repositories;

use App\Models\{Company,
    Consumable,
    Contact,
    Cutting,
    Finishing,
    FinishingLabel,
    Label,
    Packing,
    Printing,
    Quantity,
    Quotation,
    Settlement,
    Substrate,
    Third};

use App\Repositories\ApiRepository;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class QuotationRepository
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $repository;

    public function __construct(ApiRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginate($page)
    {
        $totalPerPage = 15;

        $collection = collect(Quotation::orderBy('created_at', 'desc')->orderBy('id', 'desc')->get());

        return new LengthAwarePaginator(
            $collection->forPage(
                intval($page),
                $totalPerPage
            ),
            $collection->count(),
            $totalPerPage,
            intval($page)
        );
    }

    public function getById($id, $company)
    {
        $quotation = Quotation::with('quantities', 'status')->findOrFail($id);

        $quotation['company'] = Company::findorFail(1);

        if ($quotation->third_type === "ethic") {
            $customers = $this->repository->allCustomers($company);
            $quotation['third'] = collect($customers->where('id', $quotation->third_id)->first());
        } else {
            $quotation['third'] = Third::findOrFail($quotation->third_id);
        }

//        if ($quotation->contact_ethic) {
//            $contacts = $this->repository->findByIdThirdContact($company, $quotation->third_id);
//            if ($quotation->contact_id) $quotation['contact'] = $contacts->where('id', $quotation->contact_id)->first();
//        } else {
//            $quotation['contact'] = Contact::findOrFail($quotation->contact_id);
//        }

        if ($quotation->contact_ethic) {
            $quotation['third']['contacts'] = $this->repository->findByIdThirdContact($company, $quotation->third_id);
        } else {
            $quotation['third']['contacts'] = Contact::where('third_id', $quotation->third_id)->get();
        }

        if ($quotation->label_type === "ethic") {
            $labels = $this->repository->findByIdThirdLabel($company, $quotation->third_id);
            $quotation['label'] = $labels->where('id', $quotation->label_id)->first();
        } else {
            $quotation['label'] = Label::findOrFail($quotation->label_id);
        }

        $quotation['settlements'] = Settlement::get();
        return $quotation;
    }

    private function saveProspect(Third $model, Array $inputs)
    {
        if (!empty($inputs['name'])) $model->name = $inputs['name'];
        if (!empty($inputs['addressLine1'])) $model->address_line1 = $inputs['addressLine1'];
        if (!empty($inputs['addressLine2'])) $model->address_line2 = $inputs['addressLine2'];
        if (!empty($inputs['addressLine3'])) $model->address_line3 = $inputs['addressLine3'];
        if (!empty($inputs['zipcode'])) $model->zipcode = $inputs['zipcode'];
        if (!empty($inputs['city'])) $model->city = $inputs['city'];
        $modelCompany = Company::findOrFail(1);
        $model->settlement_id = $modelCompany->settlement_id;
        $model->save();

        return $model;
    }

    private function saveContact(Contact $model, Array $inputs, $third)
    {
        $model->third_id = $third;
        if (!empty($inputs['civility'])) $model->civility = $inputs['civility'];
        if (!empty($inputs['name'])) $model->name = $inputs['name'];
        if (!empty($inputs['surname'])) $model->surname = $inputs['surname'];
        if (!empty($inputs['service'])) $model->service = $inputs['service'];
        if (!empty($inputs['email'])) $model->email = $inputs['email'];
        $model->save();

        return $model;
    }

    private function saveSubstrate(Substrate $model, Array $inputs)
    {
        if (!empty($inputs['name'])) $model->name = $inputs['name'];
        if (!empty($inputs['width'])) $model->width = $inputs['width'];
        if (!empty($inputs['weight'])) $model->weight = $inputs['weight'];
        if (!empty($inputs['price'])) $model->price = $inputs['price'];
        $model->save();

        return $model;
    }

    private function saveCutting(Cutting $model, Array $inputs)
    {
        if (!empty($inputs['name'])) $model->name = $inputs['name'];
        if (!empty($inputs['dimension_width'])) $model->dimension_width = $inputs['dimension_width'];
        if (!empty($inputs['dimension_length'])) $model->dimension_length = $inputs['dimension_length'];
        if (!empty($inputs['bleed_width'])) $model->bleed_width = $inputs['bleed_width'];
        if (!empty($inputs['bleed_length'])) $model->bleed_length = $inputs['bleed_length'];
        if (!empty($inputs['pose_width'])) $model->pose_width = $inputs['pose_width'];
        if (!empty($inputs['pose_length'])) $model->pose_length = $inputs['pose_length'];
        $model->save();

        return $model;
    }

    private function saveLabel(Label $model, Array $inputs, $substrate, $cutting)
    {
        if ($inputs['identification']['third']['ethic']) $model->third_type = "ethic";
        if (!empty($inputs['identification']['third']['id'])) $model->third_id = $inputs['identification']['third']['id'];
        if (!empty($inputs['description']['label']['name'])) $model->name = $inputs['description']['label']['name'];
        if (!empty($inputs['description']['label']['width'])) $model->width = $inputs['description']['label']['width'];
        if (!empty($inputs['description']['label']['length'])) $model->length = $inputs['description']['label']['length'];
        if (!empty($inputs['printing']['press'])) $model->printing_id = $inputs['printing']['press'];
        if (!empty($inputs['printing']['colors'])) $model->number_colors = $inputs['printing']['colors'];
        if ($inputs['printing']['quadri']) $model->quadri = 1;
        if ($inputs['description']['label']['ethic']) $model->substrate_type = "ethic";
        if (!empty($substrate)) $model->substrate_id = $substrate;
        if ($inputs['finishing']['cutting']['ethic']) $model->cutting_type = "ethic";
        if (!empty($cutting)) $model->cutting_id = $cutting;
        if (!empty($inputs['packing']['direction'])) $model->winding = $inputs['packing']['direction'];
        if (!empty($inputs['packing']['packing'])) $model->packing = $inputs['packing']['packing'];
        $model->save();

        return $model;
    }

    private function saveFinishingLabel(FinishingLabel $model, Array $inputs, $label)
    {
        if (!empty($inputs['id'])) $model->finishing_id = $inputs['id'];
        if (!empty($label)) $model->label_id = $label;
        if (floatval($inputs['shape']) > 0) $model->shape = $inputs['shape'];
        if (!empty($inputs['reworking'])) $model->reworking = $inputs['reworking'];
        $model->save();
        return $model;

    }

    private function saveConsumable(Consumable $model, Array $inputs, $finishingLabel)
    {
        if (!empty($finishingLabel)) $model->finishing_label = $finishingLabel;
        if (!empty($inputs['name'])) $model->name = $inputs['name'];
        if (!empty($inputs['width'])) $model->width = $inputs['width'];
        if (!empty($inputs['price'])) $model->price = $inputs['price'];
        if (!empty($inputs['ethic'])) $model->ethic = $inputs['ethic'];
        $model->save();
        return $model;
    }

    private function saveQuotation(Quotation $model, Array $inputs, $price, $third, $contact, $label, $user)
    {
//        $user = Auth::user();
//        $model->user_id = $user->id;
        if (!empty($user['name'])) $model->user_name = $user['name'];
        if (!empty($user['surname'])) $model->user_surname = $user['surname'];
        if (!empty($inputs['summary'])) $model->description = $inputs['summary'];
        $images = ["undraw_Credit_card_3ed6.svg", "undraw_make_it_rain_iwk4.svg", "undraw_printing_invoices_5r4r.svg", "undraw_Savings_dwkw.svg"];
        $model->image = $images[array_rand($images)];
        if ($inputs['identification']['third']['ethic']) $model->third_type = "ethic";
        if (!empty($third)) $model->third_id = $third;
        $model->third_name = $inputs['identification']['third']['name'];
        if ($inputs['identification']['contact']['ethic']) $model->contact_ethic = true;
        if (!empty($contact)) $model->contact_id = $contact;
        if (!empty($inputs['identification']['contact']['civility'])) $model->contact_civility = $inputs['identification']['contact']['civility'];
        if (!empty($inputs['identification']['contact']['name'])) $model->contact_name = $inputs['identification']['contact']['name'];
        if (!empty($inputs['identification']['contact']['surname'])) $model->contact_surname = $inputs['identification']['contact']['surname'];
        if (!empty($inputs['identification']['contact']['email'])) $model->contact_email = $inputs['identification']['contact']['email'];
        if ($inputs['description']['label']['ethic']) $model->label_type = "ethic";
        if (!empty($label)) $model->label_id = $label;
//        TODO : to add duration
        $validityDate = date('Y-m-d', strtotime("+3 months"));
        $model->validity = $validityDate;

        $modelCompany = Company::findOrFail(1);
        $settlementID = $modelCompany->settlement_id;

        if (!$inputs['identification']['third']['ethic']) {
            $modelThird = Third::findOrFail($third);
            $settlementID = $modelThird->settlement_id;
        }

        $model->settlement_id = $settlementID;

        $allTotalCosts = array();
        $allQuantities = array();
        foreach ($price['quantities'] as $key => $quantity) {
            $allTotalCosts[] = $quantity['totals']['totalCosts'];
            $allQuantities[] = $key;
        }
        $minQuantity = min($allQuantities);

        $totalCostWithMargin = round($price['quantities'][$minQuantity]['totals']['totalCosts'] + ($price['quantities'][$minQuantity]['totals']['totalCosts'] * (20/100)), 2);
        $model->cost = $totalCostWithMargin;
        $thousandPrice = round(($totalCostWithMargin / $minQuantity) * 1000, 2); // prix de revient
        $model->thousand = $thousandPrice;
        $model->quantity = $minQuantity;
        $model->shipping = $price['quantities'][$minQuantity]['totals']['expedition'];
        $vat = 20;
        $model->vat = $vat;
        $subtotal = round($totalCostWithMargin + $price['quantities'][$minQuantity]['totals']['expedition'], 2);
        $vat_price = round($subtotal * ($vat / 100), 2);
        $model->vat_price = $vat_price;
        $model->price = round($subtotal + $vat_price, 2);
        $model->workflow = json_encode($inputs);
        $model->datas_price = json_encode($price);
        $model->body_email = $modelCompany->body_email;

        $model->save();
        return $model;
    }

    private function saveQuantities(Quantity $model, Array $inputs, $quotation)
    {
        $model->quotation_id = $quotation['id'];
        $model->quantity = $inputs['datas']['copies'];
        $model->models = $inputs['datas']['models'];
        $model->plates = $inputs['datas']['plates'];
        if (floatval($inputs['datas']['prepress']) > 0) $model->prepress = $inputs['datas']['prepress'];
        $model->time = $inputs['totals']['totalTimes'];
        $model->weight = $inputs['totals']['weight'];
        $model->cost = $inputs['totals']['totalCosts']; // prix de revient
        $model->margin = 20;
        $totalCostWithMargin = round($inputs['totals']['totalCosts'] + ($inputs['totals']['totalCosts'] * (20/100)), 2);
        $model->thousand = round(($totalCostWithMargin / $inputs['datas']['copies']) * 1000, 2); // prix de vente
        $model->shipping = $inputs['totals']['expedition'];
        $subtotal = round($totalCostWithMargin + $inputs['totals']['expedition'], 2);
        $model->subtotal = $subtotal;
        $vat_price = round($subtotal * ($quotation['vat'] / 100), 2);
        $model->vat_price = $vat_price;
        $model->price = round($subtotal + $vat_price, 2);
        $model->save();
        return $model;
    }

    public function store(Array $inputs)
    {
        $workflow = $inputs['workflow'];

        $identification = $workflow['identification'];
        $third = $identification['third'];
        $contact = $identification['contact'];

        $description = $workflow['description'];
        $label = $description['label'];

        $finishing = $workflow['finishing'];
        $finishings = $finishing['finishings'];
        $cutting = $finishing['cutting'];

        $printing = $workflow['printing'];
        $substrate = $printing['substrate'];
        $errors = array();
        if (isset($inputs['quotation'])) {
            if (!$third['ethic']) $modelThird = Third::findOrFail($third['id']);
            if (!$contact['ethic']) $modelContact = Contact::findOrFail($contact['id']);
            if (!$substrate['ethic']) $modelSubstrate = Substrate::findOrFail($substrate['id']);
            if (!$cutting['ethic']) $modelCutting = Cutting::findOrFail($cutting['id']);
            if (!$label['ethic']) $modelLabel = Label::findOrFail($label['id']);
            $modelQuotation = Quotation::findOrFail($inputs['quotation']);
        } else {
            if ($third['type'] === "new" || $third['id'] === "") $modelThird = new Third();
            if ($contact['type'] === "new" || $contact['id'] === "") $modelContact = new Contact();
            if ($substrate['type'] === "new" || $substrate['id'] === "") $modelSubstrate = new Substrate();
            if ($cutting['type'] === "new" || $cutting['id'] === "") $modelCutting = new Cutting();
            if ($label['type'] === "new" || $label['id'] === "") $modelLabel = new Label();
            $modelQuotation = new Quotation();
        }


        if (!empty($modelThird)) {
            $third = $this->saveProspect($modelThird, $identification['third']);
            $inputs['workflow']['identification']['third']['type'] = "old";
            $inputs['workflow']['identification']['third']['id'] = $third['id'];
            if (!isset($third)) $errors[] = "L'insertion du donneur d'ordre a échoué.";
        }

        if (empty($errors)) {
            if (!empty($modelContact)) {
                $contact = $this->saveContact($modelContact, $inputs['workflow']['identification']['contact'], $third['id']);
                $inputs['workflow']['identification']['contact']['id'] = $contact['id'];
                if (!isset($contact)) $errors['errors'][] = "L'insertion du contact a échoué.";
            }
        }

        if (empty($errors)) {
            if (!empty($modelSubstrate)) {
                $substrate = $this->saveSubstrate($modelSubstrate, $inputs['workflow']['printing']['substrate']);
                $inputs['workflow']['printing']['substrate']['type'] = "old";
                $inputs['workflow']['printing']['substrate']['id'] = $substrate['id'];
                if (!isset($substrate)) $errors['errors'][] = "L'insertion du support d'impression a échoué.";
            }
        }

        if (empty($errors)) {
            if (!empty($modelCutting)) {
                $cutting = $this->saveCutting($modelCutting, $inputs['workflow']['finishing']['cutting']);
                $inputs['workflow']['finishing']['cutting']['type'] = "old";
                $inputs['workflow']['finishing']['cutting']['id'] = $cutting['id'];
                if (!isset($cutting)) $errors['errors'][] = "L'insertion de l'outil de découpe a échoué.";
            }
        }

        if (empty($errors)) {
            if (!empty($modelLabel)) {
                $label = $this->saveLabel($modelLabel, $inputs['workflow'], $substrate['id'], $cutting['id']);
                $inputs['workflow']['description']['label']['type'] = "old";
                $inputs['workflow']['description']['label']['id'] = $label['id'];
                if (!isset($label)) $errors['errors'][] = "L'insertion de l'étiquette a échoué.";
            }
        }

//        if (empty($errors)) {
//            $finishingsLabel = array();
//            foreach ($inputs['workflow']['finishing']['finishings'] as $key => $finishing) {
//                if (empty($errors)) {
//                    if ($finishing['id'] === "") {
//                        $modelFinishingLabel = new FinishingLabel();
//                    } else {
//                        $modelFinishingLabel = FinishingLabel::findOrFail($finishing['id']);
//                    }
//
//                    $finishingLabel = $this->saveFinishingLabel($modelFinishingLabel, $finishing, $label['id']);
//                    if (!isset($finishingLabel)) $errors['errors'][] = "L'insertion de la finition " . $finishing['name'] . " a échoué.";
//
//                    if (empty($errors)) {
//                        $inputs['workflow']['finishing']['finishings'][$key]['id'] = $finishingLabel['id'];
//                        $finishingsLabel[] = $finishingLabel['id'];
//
//                        if ($finishing['presence_consumable']) {
//                            if ($finishing['consumable']['id'] !== "") {
//                                $modelConsumable = Consumable::findOrFail($finishing['consumable']['id']);
//                            } else {
//                                $modelConsumable = new Consumable();
//                            }
//                            if (!empty($modelConsumable)) {
//                                $consumable = $this->saveConsumable($modelConsumable, $finishing['consumable'], $finishingLabel['id']);
//                                if (!isset($consumable)) $errors['errors'][] = "L'insertion du consommable de " . $finishing['name'] . " a échoué.";
//                                $finishing['consumable']['id'] = $consumable['id'];
//                            }
//                        } else {
////                TODO : to test
//                            if ($finishing['id'] !== "") {
//                                Consumable::where('finishing_label', $finishingLabel['id'])->delete();
//                            }
//                        }
//                    }
//                }
//            }
//        }
//
        if (empty($errors)) {
//            $finishingsLabels = FinishingLabel::where('label_id', $label['id'])->pluck('id')->toArray();

//            $finishingsDeleted = array_diff($finishingsLabels, $finishingsLabel);
//            foreach ($finishingsDeleted as $finishingDeleted) {
//                Consumable::where('finishing_label', $finishingDeleted)->delete();
//                FinishingLabel::findOrFail($finishingDeleted)->delete();
//            }

            $quotation = $this->saveQuotation($modelQuotation, $inputs['workflow'], $inputs['price'], $third['id'], $contact['id'], $label['id'], $inputs['user']);
            if (!isset($quotation)) $errors['errors'][] = "L'insertion du devis a échoué.";
        }

        $quantitiesQuotation = array();
        foreach ($inputs['workflow']['description']['quantities'] as $key => $workflowQuantity) {
            foreach ($inputs['price']['quantities'] as $index => $priceQuantity) {
                if (empty($errors)) {
                    if (intval($workflowQuantity['quantity']) === $index) {
                        if ($workflowQuantity['id'] === "") {
                            $modelQuantity = new Quantity();
                        } else {
                            $modelQuantity = Quantity::findOrFail($workflowQuantity['id']);
                        }
                        $quantity = $this->saveQuantities($modelQuantity, $priceQuantity, $quotation);
                        if (!isset($quantity)) $errors['errors'][] = "L'insertion de la quantité" . $index . " a échoué.";

                        $inputs['workflow']['description']['quantities'][$key]['id'] = $quantity['id'];
                        $quantitiesQuotation[] = $quantity['id'];
                    }
                }
            }
        }

        if (empty($errors)) {
            $allQuantities = Quantity::where('quotation_id', $quotation['id'])->pluck('id')->toArray();

            $quantitiesDeleted = array_diff($allQuantities, $quantitiesQuotation);
            foreach ($quantitiesDeleted as $quantityDeleted) {
                Quantity::findOrFail($quantityDeleted)->delete();
            }
        }

        if (empty($errors)) {
            return $quotation;
        } else {
            return $errors;
        }
    }

    public function getPrice($inputs) {
        $results = array();
        $errors = array();
        $userDatas = array();
        $userDatas['company'] = $inputs['company'];
        $userDatas['establishment'] = $inputs['establishment'];


        $results['workflow'] = $inputs['workflow'];

        $margin = 20;

        $workflow = $inputs['workflow'];
        $identification = $workflow['identification'];
        $third = $identification['third'];
        $contact = $identification['contact'];

        if (empty($third['name'])) { $errors['errors'][] = "Vous devez saisir le nom du donneur d'ordre"; }
        if (empty($third['zipcode'])) { $errors['errors'][] = "Vous devez saisir le code postal du donneur d'ordre"; }
        if (empty($contact['email'])) { $errors['errors'][] = "Vous devez saisir l'adresse e-mail du donneur d'ordre"; }


        /*
         * Substrate
         */
        $printing = $workflow['printing'];
        $substrate = $printing['substrate'];

        $substrateName = $substrate['name'];
        $substrateWidth = $substrate['width'];
        $substrateWeight = $substrate['weight'];
        $substratePrice = $substrate['price'];

        if (empty($substrateWidth)) { $errors['errors'][] = "Vous devez saisir la laize du support d'impression"; }
        if (empty($substrateWeight)) { $errors['errors'][] = "Vous devez saisir le grammage du support d'impression"; }
        if (empty($substratePrice)) { $errors['errors'][] = "Vous devez saisir le prix au mètre carré du support d'impression"; }

        /*
         * Printing
        */
        $pressID = $printing['press'];
        if (!empty($pressID)) {
            $userDatas['class'] = $pressID;
            $press = $this->repository->getPrintings($userDatas)[0];
        } else {
            $errors['errors'][] = "Vous devez choisir une machine d'impression";
        }

        if (empty($errors)) {
            if (!empty($press['cadence'])) {
                if ($press['unit_cadence'] === "striking") {
                    $pressCadence = $press['cadence'];
                } else {
                    $pressCadence = $press['cadence'] * 60; // transform meters per minutes into hours
                }
            } else {
                $errors['errors'][] = "Aucune cadence n'a été renseignée pour la machine d'impression";
            }
        }

        if (empty($errors)) {
            if (!empty($press['number_units'])) {
                if ($printing['colors'] > $press['number_units']) { $errors['errors'][] = "La machine ne peut imprimer que " . $press['number_units'] . " couleurs"; }
            }
            if (!empty($press['size_papermaxx'])) {
//                if ($substrateWidth <= $press['cadence']->size_paperminx) { $errors['errors'][] = "La laize du support d'impression est inférieure à la laize minimum de la machine"; }
                if ($substrateWidth > $press['size_papermaxx']) { $errors['errors'][] = "La laize du support d'impression est supérieure à la laize maximum de la machine"; }
            } else {
                $results['warnings'][] = "Veuillez vérifier que la laize du support d'impression correspond à celle de la machine";
            }
        }

        $description = $workflow['description'];
        /*
         * Label
        */
        $label = $description['label'];

        $labelName = $label['name'];
        $labelWidth = $label['width'];
        $labelLength = $label['length'];

        if (empty($labelWidth)) { $errors['errors'][] = "Vous devez saisir la laize de l'étiquette"; }
        if (empty($labelLength)) { $errors['errors'][] = "Vous devez saisir l'avance de l'étiquette"; }

        if (empty($errors)) {
            if (!empty($labelWidth) && !empty($labelLength)) {
                if ($labelWidth > $labelLength) {
                    $labelSizeLength = $labelWidth;
                    $labelSizeWidth = $labelLength;
                } else {
                    $labelSizeLength = $labelLength;
                    $labelSizeWidth = $labelWidth;
                }
            }
        }


        /*
         * Cutting form
         */
        $finishing = $workflow['finishing'];
        $cutting = $finishing['cutting'];

        $cuttingName = $cutting['name'];
        $cuttingDimensionWidth = $cutting['dimension_width'];
        $cuttingDimensionLength = $cutting['dimension_length'];
        $cuttingBleedWidth = $cutting['bleed_width'];
        $cuttingBleedLength = $cutting['bleed_length'];
        $cuttingPoseWidth = $cutting['pose_width'];
        $cuttingPoseLength = $cutting['pose_length'];

        if (empty($cuttingDimensionWidth) || empty($cuttingDimensionLength)) { $errors['errors'][] = "Vous devez saisir une dimension en laize et en avance pour l'outil de découpe"; }
        if (empty($cuttingBleedWidth) || empty($cuttingBleedLength)) { $errors['errors'][] = "Vous devez saisir l'entrepose en laize et en avance pour l'outil de découpe"; }
        if (empty($cuttingPoseWidth) || empty($cuttingPoseLength)) { $errors['errors'][] = "Vous devez saisir le nombre de poses en laize et en avance pour l'outil de découpe"; }

        /*
         * Packing
        */
        $packing = $workflow['packing'];
        $direction = $packing['direction'];

        if (empty($errors)) {
//            if ($direction === "ehead" || $direction === "efoot" || $direction === "ihead" || $direction === "ifoot") {
//                $labelSizeWidthWithBleed = $labelSizeLength + $cuttingBleedWidth;
//                $labelSizeLengthWithBleed = $labelSizeWidth + $cuttingBleedLength;
//            } else {
                $cuttingDimensionWidthWithBleed = $cuttingDimensionWidth + $cuttingBleedWidth;
                $cuttingDimensionLengthWithBleed = $cuttingDimensionLength + $cuttingBleedLength;
//            }

            if (!empty($press['printable_areax'])) {
                $sizePosesWidth = $cuttingDimensionWidthWithBleed * $cuttingPoseWidth;
                if ($sizePosesWidth > $press['printable_areax']) {
                    $errors['errors'][] = "La laize de l'outil excède celle du support d'impression.";
                }
            } else {
                $results['warnings'][] = "Veuillez vérifier avec l'atelier de production que la laize du support d'impression est bien inférieure à celle de la machine.";
            }
        }

        /*
         * Finishings
         */
        $quantities = $description['quantities'];
        if (empty($errors)) {
            foreach ($quantities as $quantity) {
                if (!empty($quantity['quantity']) && !empty($quantity['model']) && !empty($quantity['plate'])) {
                    $totalFixedCostsWithoutMargin = 0;
                    $totalVariableCostsWithoutMargin = 0;
                    $totalFixedCostsWithMargin = 0;
                    $totalVariableCostsWithMargin = 0;
                    $totalCostsWithoutMargin = 0;
                    $totalCostsWithMargin = 0;
                    $totalTimes = 0;

                    $copies = intval($quantity['quantity']);
                    $models = intval($quantity['model']);
                    $plates = intval($quantity['plate']);

                    $results['quantities'][$copies]['datas']['copies'] = $copies;
                    $results['quantities'][$copies]['datas']['models'] = $models;
                    $results['quantities'][$copies]['datas']['plates'] = $plates;



                    /*
                     * Général
                     */
                    $operationId = 0;
                    $meterMakereadyPress = 0;

                    $pressMakeready = array();
                    $pressMakeready[] = $press['id'];

                    // nb frappes roulage
                    $strikingRun = ceil($copies / ($cuttingPoseWidth * $cuttingPoseLength));
                    $results['quantities'][$copies]['wastage'][] = "Nombre de frappes : " . $strikingRun;

                    // métrage papier roulage
                    $substrateLinear = $strikingRun * $cuttingDimensionLengthWithBleed * $cuttingPoseLength / 1000; // longueur papier en mL


                    /*
                     * Conditionnement - bonbineuse
                     */
                    $conditioning = intval($packing['packing']);

                    if ($conditioning > 0) {
                        $company = Company::findOrFail(1);

                        if ($company->winder != null) {
                            $userDatas['class'] = $company->winder;
                            $windingMachine = $this->repository->getWorkstations($userDatas)[0];

                            if ($windingMachine['cadence'] > 0) {
                                // Temps de calage bobineuse
                                $timeMakereadyWinding = $windingMachine['makeready_plate'];
                                $results['quantities'][$copies]['time'][] = "Calage de la bobineuse : " . $timeMakereadyWinding . "h";

                                // Temps de production bobineuse
                                if ($windingMachine['unit_cadence'] === "striking") {
                                    $windingCadence = $windingMachine['cadence'];
                                } else {
                                    $windingCadence = $windingMachine['cadence'] * 60; // transform meters per minutes into hours
                                }
                                $timeProductionWinding = $substrateLinear / $windingCadence + $copies / $conditioning * $windingMachine['makeready_times'];
                                $results['quantities'][$copies]['time'][] = "Roulage de la bobineuse : " . $timeProductionWinding . "h";

                                $totalTimeWinding = round($timeMakereadyWinding + $timeProductionWinding, 4);

                                // Coût de production bobineuse
                                $totalFixedCostWinding = $timeMakereadyWinding * $windingMachine['hourly_rate'];
                                $results['quantities'][$copies]['cost'][] = "Calage de la bobineuse : " . $totalFixedCostWinding . "€";
                                $totalVariableCostWinding = round($timeProductionWinding * $windingMachine['hourly_rate'], 4);
                                $results['quantities'][$copies]['cost'][] = "Roulage de la bobineuse : " . $totalVariableCostWinding . "€";
                                $totalCostWinding = $totalFixedCostWinding + $totalVariableCostWinding;

                                $marginWinding = $margin;

                                $costWithMarginReturned = $this->handleMargin($marginWinding, $totalCostWinding, $totalFixedCostWinding, $totalVariableCostWinding);
                                if (!empty($costWithMarginReturned)) {
                                    $lastElement = end($costWithMarginReturned);
                                    foreach ($costWithMarginReturned as $result) {
                                        if ($result === $lastElement) {
                                            $totalCostsWithoutMargin += $totalCostWinding;
                                            $totalCostsWithMargin += $result[0];
                                            $totalFixedCostsWithoutMargin += $totalFixedCostWinding;
                                            $totalVariableCostsWithoutMargin += $totalVariableCostWinding;
                                            $totalFixedCostsWithMargin += $result[1];
                                            $totalVariableCostsWithMargin += $result[2];
                                            $totalTimes += $totalTimeWinding;

                                            $results['quantities'][$copies]['operations'][$operationId]['name'] = $windingMachine['name'];
                                            $results['quantities'][$copies]['operations'][$operationId]['time'] = $totalTimeWinding;
                                            $results['quantities'][$copies]['operations'][$operationId]['cost'] = $totalCostWinding;
                                            $results['quantities'][$copies]['operations'][$operationId]['fixed'] = $result[1];
                                            $results['quantities'][$copies]['operations'][$operationId]['variable'] = $result[2];
                                            $results['quantities'][$copies]['operations'][$operationId]['margin'] = $marginWinding;
                                            $results['quantities'][$copies]['operations'][$operationId]['price'] = $result[0];

                                            $operationId++;
                                        }
                                    }
                                }
                            } else {
                                $errors['errors'][] = "La cadence de la bobineuse doit être supérieure à 0.";
                            }
                        } else {
                            $errors['errors'][] = "Aucune bobineuse n'a été renseigné dans les paramètres entreprise.";
                        }
                    }


                    /*
                     * Finitions - Opérations de fabrication
                     */
                    $marginFinishing = $margin;
                    $finishings = $workflow['finishing']['finishings'];
                    foreach ($finishings as $finishing) {
                        if (!empty($finishing['id'])) {
                            $reworking = $finishing['reworking'];
                            $userDatas['finishing'] = "null";
                            if (!empty($reworking)) {
                                $userDatas['workstation'] = $reworking;
                                $finishingPress = $reworking;
                                $finishingCadences = $this->repository->getCadences($userDatas);
                            } else {
                                $userDatas['workstation'] = $press['id'];
                                $finishingPress = $press['id'];
                                $finishingCadences = $this->repository->getCadences($userDatas);
                            }

                            if (empty($finishingCadences)) {
                                $errors['errors'][] = "Veuillez renseigner la cadence et calage de base pour le poste " . $finishingPress . " dans Paramètres > Gestion production > Postes de production (onglet Calages).";
                            } else {
                                $base = $finishingCadences->where('finishing_id', null)->first();

                                if (!in_array($base['workstation_id'], $pressMakeready)) {
                                    // Métrage de calage machine de reprise
                                    $meterMakereadyFinishingPress = $base['overlay_sheet'];
                                    $meterMakereadyPress += $meterMakereadyFinishingPress;
                                    $results['quantities'][$copies]['wastage'][] = "Calage pour " . $base['workstation_name'] . " : " . $meterMakereadyFinishingPress  . "m";

                                    // Temps de calage machine de reprise
                                    $timeMakereadyFinishingPress = round($base['makeready_times'], 4);
                                    $results['quantities'][$copies]['time'][] = "Calage pour " . $base['workstation_name'] . " : " . $timeMakereadyFinishingPress . "h";

                                    $totalCostFinishingPress = $timeMakereadyFinishingPress * $base['workstation_hourly_rate'];
                                    $totalFixedCostFinishingPress = $totalCostFinishingPress;
                                    $totalVariableCostFinishingPress = 0;

                                    $costWithMarginReturned = $this->handleMargin($marginFinishing, $totalCostFinishingPress, $totalFixedCostFinishingPress, $totalVariableCostFinishingPress);
                                    if (!empty($costWithMarginReturned)) {
                                        $lastElement = end($costWithMarginReturned);
                                        foreach ($costWithMarginReturned as $result) {
                                            if ($result === $lastElement) {
                                                $totalCostsWithoutMargin += $totalCostFinishingPress;
                                                $totalCostsWithMargin += $result[0];
                                                $totalFixedCostsWithoutMargin += $totalFixedCostFinishingPress;
                                                $totalVariableCostsWithoutMargin += $totalVariableCostFinishingPress;
                                                $totalFixedCostsWithMargin += $result[1];
                                                $totalVariableCostsWithMargin += $result[2];
                                                $totalTimes += $timeMakereadyFinishingPress;

                                                $results['quantities'][$copies]['operations'][$operationId]['name'] = $base['workstation_name'];
                                                $results['quantities'][$copies]['operations'][$operationId]['time'] = $timeMakereadyFinishingPress;
                                                $results['quantities'][$copies]['operations'][$operationId]['cost'] = $totalCostFinishingPress;
                                                $results['quantities'][$copies]['operations'][$operationId]['fixed'] = $result[1];
                                                $results['quantities'][$copies]['operations'][$operationId]['variable'] = $result[2];
                                                $results['quantities'][$copies]['operations'][$operationId]['margin'] = $marginFinishing;
                                                $results['quantities'][$copies]['operations'][$operationId]['price'] = $result[0];

                                                $operationId++;
                                            }
                                        }
                                    }
                                }




                                $finishingOperation = $finishingCadences->where('finishing_id', $finishing['id'])->first();

                                if (!empty($finishingOperation)) {
                                    // Métrage de calage finition
                                    $meterMakereadyFinishingOperation = $finishingOperation['overlay_sheet'];
                                    $meterMakereadyPress += $meterMakereadyFinishingOperation;
                                    $results['quantities'][$copies]['wastage'][] = "Calage pour " . $finishing['name'] . " : " . $meterMakereadyFinishingOperation . "m";

                                    // Temps de calage finition
                                    $timeMakereadyFinishingOperation = round($finishingOperation['makeready_times'], 4);
                                    $results['quantities'][$copies]['time'][] = "Calage pour " . $finishing['name'] . " : " . $timeMakereadyFinishingOperation . "h";


                                    $allOperations = array();
                                    foreach ($finishings as $operation) {
                                        if (!empty($reworking)) {
                                            if ($operation['reworking'] === $reworking) {
                                                $allOperations[] = $finishingCadences->where('finishing_id', $operation['id'])->first();
                                            }
                                        } else {
                                            $allOperations[] = $finishingCadences->where('finishing_id', $operation['id'])->first();
                                        }
                                    }
                                    $allOperations[] = $base;

                                    $allOperationsFinishingCadences = array();
                                    foreach ($allOperations as $operation) {
                                        if ($operation['unit_cadence'] === "meter") {
                                            $cadence = $operation['cadence'] * 60;
                                        } else {
                                            $cadence = $operation['cadence'];
                                        }
                                        $allOperationsFinishingCadences[] = $cadence;
                                    }

                                    $cadenceFinishingOperation = min($allOperationsFinishingCadences);

                                    if ($finishingPress === $press['id']) {
                                        $pressCadence = $cadenceFinishingOperation;
                                    }

                                    if ($finishingOperation['unit_cadence'] === "striking") {
                                        $timeProductionFinishingOperation = $strikingRun / $cadenceFinishingOperation;
                                    } else {
                                        $timeProductionFinishingOperation = $substrateLinear / $cadenceFinishingOperation;
                                    }
                                    $results['quantities'][$copies]['time'][] = "Roulage pour " . $finishing['name'] . " : " . $timeProductionFinishingOperation . "h";

                                    $totalTimeFinishingOperation = round($timeMakereadyFinishingOperation + $timeProductionFinishingOperation, 4);

                                    $costFinishingShape = 0;
                                    if (empty($finishing['die']['id'])) {
                                        $finishingShape = intval($finishing['die']['price']);
                                        if ($finishingShape > 0) {
                                            $costFinishingShape = $finishingShape;
                                            $results['quantities'][$copies]['cost'][] = "Outil de " . $finishing['name'] . " : " . $costFinishingShape . "€";
                                        }
                                    }

                                    $totalFixedCostFinishingOperation = $costFinishingShape + ($timeMakereadyFinishingOperation * $finishingOperation['workstation_hourly_rate']);
                                    $results['quantities'][$copies]['cost'][] = "Calage pour " . $finishing['name'] . " : " . $totalFixedCostFinishingOperation . "€";

                                    $totalCostConsumable = 0;
                                    if ($finishing['presence_consumable']) {
                                        $consumable = $finishing['consumable'];
                                        if (!empty($consumable)) {
                                            if (!empty($consumable['width']) && !empty($consumable['price'])) {
                                                $consumableWidth = intval($consumable['width']);
                                                $consumablePrice = floatval($consumable['price']);
                                                if ($consumableWidth < $finishingOperation['workstation_size_papermaxx']) {
                                                    $totalCostConsumable = $substrateLinear * ($consumableWidth / 1000 * $consumablePrice);
                                                    $results['quantities'][$copies]['cost'][] = "Consommable " . $consumable['name'] . " pour la finition " . $finishing['name'] . " : " . $totalCostConsumable . "€";
                                                } else {
                                                    $errors['errors'][] = "La laize du consommable n'est pas en corrélation avec celle de la machine";
                                                }
                                            } else {
                                                if (empty($consumable['width'])) { $errors['errors'][] = "Vous devez saisir la laize du consommable " . $consumable['name']; }
                                                if (empty($consumable['price'])) { $errors['errors'][] = "Vous devez saisir le prix du consommable " . $consumable['name']; }
                                            }
                                        } else {
                                            $errors['errors'][] = "Vous devez saisir les données relatives au consommable pour la finition " . $finishing['name'];
                                        }
                                    }

                                    $totalVariableCostFinishingOperation = $totalCostConsumable + ($timeProductionFinishingOperation * $finishingOperation['workstation_hourly_rate']);
                                    $totalCostFinishingOperation = $totalFixedCostFinishingOperation + $totalVariableCostFinishingOperation;

                                    $costWithMarginReturned = $this->handleMargin($marginFinishing, $totalCostFinishingOperation, $totalFixedCostFinishingOperation, $totalVariableCostFinishingOperation);
                                    if (!empty($costWithMarginReturned)) {
                                        $lastElement = end($costWithMarginReturned);
                                        foreach ($costWithMarginReturned as $result) {
                                            if ($result === $lastElement) {
                                                $totalCostsWithoutMargin += $totalCostFinishingOperation;
                                                $totalCostsWithMargin += $result[0];
                                                $totalFixedCostsWithoutMargin += $totalFixedCostFinishingOperation;
                                                $totalVariableCostsWithoutMargin += $totalVariableCostFinishingOperation;
                                                $totalFixedCostsWithMargin += $result[1];
                                                $totalVariableCostsWithMargin += $result[2];
                                                $totalTimes += $totalTimeFinishingOperation;

                                                $results['quantities'][$copies]['operations'][$operationId]['name'] = $finishing['name'];
                                                $results['quantities'][$copies]['operations'][$operationId]['time'] = $totalTimeFinishingOperation;
                                                $results['quantities'][$copies]['operations'][$operationId]['cost'] = $totalCostFinishingOperation;
                                                $results['quantities'][$copies]['operations'][$operationId]['fixed'] = $result[1];
                                                $results['quantities'][$copies]['operations'][$operationId]['variable'] = $result[2];
                                                $results['quantities'][$copies]['operations'][$operationId]['margin'] = $marginFinishing;
                                                $results['quantities'][$copies]['operations'][$operationId]['price'] = $result[0];

                                                $operationId++;
                                            }
                                        }
                                    }
                                    $pressMakeready[] = $finishingPress;
                                } else {
                                    $errors['errors'][] = "Veuillez renseigner la cadence de l'opération " . $finishing['name'] . " pour le poste " . $finishingPress . " dans Paramètres > Gestion production > Postes de production (onglet Calages).";
                                }
                            }
                        }
                    }



                    $prepressMinutes = intval($quantity['minute']);
                    $prepressHours = intval($quantity['hour']);
                    if ($prepressMinutes > 0 || $prepressHours > 0) {
                        $company = Company::findOrFail(1);

                        if ($company->prepress != null) {
                            $userDatas['class'] = $company->prepress;
                            $prepressWorkstation = $this->repository->getWorkstations($userDatas)[0];

                            $prepressMinutesInHour = $prepressMinutes / 60;
                            $totalTimePrepress = round($prepressHours + $prepressMinutesInHour, 4);

                            $totalCostPrepress = $totalTimePrepress * $prepressWorkstation['hourly_rate'];
                            $totalFixedCostPrepress = $totalCostPrepress;
                            $totalVariableCostPrepress = 0;

                            $marginPrepress = $margin;

                            $results['quantities'][$copies]['datas']['prepress'] = $totalTimePrepress;
                            $results['quantities'][$copies]['time'][] = "Prépresse : " . $totalTimePrepress . "h";
                            $results['quantities'][$copies]['cost'][] = "Prépresse : " . $totalCostPrepress . "€";

                            $costWithMarginReturned = $this->handleMargin($marginPrepress, $totalCostPrepress, $totalFixedCostPrepress, $totalVariableCostPrepress);
                            if (!empty($costWithMarginReturned)) {
                                $lastElement = end($costWithMarginReturned);
                                foreach ($costWithMarginReturned as $result) {
                                    if ($result === $lastElement) {
                                        $totalCostsWithoutMargin += $totalCostPrepress;
                                        $totalCostsWithMargin += $result[0];
                                        $totalFixedCostsWithoutMargin += $totalFixedCostPrepress;
                                        $totalVariableCostsWithoutMargin += $totalVariableCostPrepress;
                                        $totalFixedCostsWithMargin += $result[1];
                                        $totalVariableCostsWithMargin += $result[2];
                                        $totalTimes += $totalTimePrepress;

                                        $results['quantities'][$copies]['operations'][$operationId]['name'] = "Prépresse";
                                        $results['quantities'][$copies]['operations'][$operationId]['time'] = $totalTimePrepress;
                                        $results['quantities'][$copies]['operations'][$operationId]['cost'] = $totalCostPrepress;
                                        $results['quantities'][$copies]['operations'][$operationId]['fixed'] = $result[1];
                                        $results['quantities'][$copies]['operations'][$operationId]['variable'] = $result[2];
                                        $results['quantities'][$copies]['operations'][$operationId]['margin'] = $marginPrepress;
                                        $results['quantities'][$copies]['operations'][$operationId]['price'] = $result[0];

                                        $operationId++;
                                    }
                                }
                            }
                        } else {
                            $errors['errors'][] = "Aucun poste de prépresse n'a été renseigné dans les paramètres entreprise.";
                        }
                    } else {
                        $results['quantities'][$copies]['datas']['prepress'] = 0;
                    }


                    /*
                     * Outil de découpe
                     */
                    $cuttingShape = floatval($cutting['shape']);
                    if ($cuttingShape > 0) {
                        $costCuttingShape = $cuttingShape;
                        $results['quantities'][$copies]['cost'][] = "Outil de découpe : " . $costCuttingShape . "€";
                    } else {
                        $costCuttingShape = 0;
                    }


                    /*
                     * Impression
                     */

                    // Clichés
                    $totalCostPlates = $plates * $press['plate'];
                    $results['quantities'][$copies]['cost'][] = "Clichés : " . $totalCostPlates . "€";

                    // Métrage de calage impression
                    $meterMakereadyPrinting = $press['overlay_sheet'] * $plates;
                    $meterMakereadyPress += $meterMakereadyPrinting;
                    $results['quantities'][$copies]['wastage'][] = "Calage pour " . $press['name'] . " : " . $meterMakereadyPrinting . "m";

                    // Temps de calage impression
                    $timeMakereadyPrinting = $press['makeready_plate'] * $plates;
                    $results['quantities'][$copies]['time'][] = "Calage pour " . $press['name'] . " : " . $timeMakereadyPrinting . "h";

                    // Métrage de roulage impression
                    $results['quantities'][$copies]['wastage'][] = "Roulage pour " . $press['name'] . " : " . $substrateLinear . "m";

                    // Temps de roulage
                    if ($press['unit_cadence'] === "striking") {
                        $timeProductionPress = $strikingRun / $pressCadence;
                    } else {
                        $timeProductionPress = $substrateLinear / $pressCadence;
                    }

                    $results['quantities'][$copies]['time'][] = "Roulage pour " . $press['name'] . " : " . $timeProductionPress . "h";

                    $totalTimeProduction = round($timeMakereadyPrinting + $timeProductionPress, 4);
                    $totalFixedCostProduction = $costCuttingShape + $timeMakereadyPrinting * $press['hourly_rate'] + $totalCostPlates;
                    $totalVariableCostProduction = $timeProductionPress * $press['hourly_rate'];
                    $totalCostProduction = $totalFixedCostProduction + $totalVariableCostProduction;
                    $results['quantities'][$copies]['cost'][] = "Calage pour " . $press['name'] . " : " . $timeMakereadyPrinting * $press['hourly_rate'] . "€";
                    $results['quantities'][$copies]['cost'][] = "Roulage pour " . $press['name'] . " : " . $totalVariableCostProduction . "€";

                    $marginProduction = $margin;

                    $costWithMarginReturned = $this->handleMargin($marginProduction, $totalCostProduction, $totalFixedCostProduction, $totalVariableCostProduction);
                    if (!empty($costWithMarginReturned)) {
                        $lastElement = end($costWithMarginReturned);
                        foreach ($costWithMarginReturned as $result) {
                            if ($result === $lastElement) {
                                $totalCostsWithoutMargin += $totalCostProduction;
                                $totalCostsWithMargin += $result[0];
                                $totalFixedCostsWithoutMargin += $totalFixedCostProduction;
                                $totalVariableCostsWithoutMargin += $totalVariableCostProduction;
                                $totalFixedCostsWithMargin += $result[1];
                                $totalVariableCostsWithMargin += $result[2];
                                $totalTimes += $totalTimeProduction;

                                $results['quantities'][$copies]['operations'][$operationId]['name'] = $press['name'];
                                $results['quantities'][$copies]['operations'][$operationId]['time'] = $totalTimeProduction;
                                $results['quantities'][$copies]['operations'][$operationId]['cost'] = $totalCostProduction;
                                $results['quantities'][$copies]['operations'][$operationId]['fixed'] = $result[1];
                                $results['quantities'][$copies]['operations'][$operationId]['variable'] = $result[2];
                                $results['quantities'][$copies]['operations'][$operationId]['margin'] = $marginProduction;
                                $results['quantities'][$copies]['operations'][$operationId]['price'] = $result[0];

                                $operationId++;
                            }
                        }
                    }


                    /*
                     * Paper - Substrate
                     */
                    /* métrage calage papier */
                    $substratePriceLinearMeter = $substrateWidth / 1000 * $substratePrice;
                    $totalFixedCostSubstrate = $meterMakereadyPress * $substratePriceLinearMeter;
                    $totalVariableCostSubstrate = $substrateLinear * $substratePriceLinearMeter;
                    $totalCostSubstrate = $totalFixedCostSubstrate + $totalVariableCostSubstrate;
                    $results['quantities'][$copies]['cost'][] = "Support d'impression : " . $totalCostSubstrate . "€";

                    $totalTimeSubstrate = 0;
                    $marginSubstrate = $margin;

                    $costWithMarginReturned = $this->handleMargin($marginSubstrate, $totalCostSubstrate, $totalFixedCostSubstrate, $totalVariableCostSubstrate);
                    if (!empty($costWithMarginReturned)) {
                        $lastElement = end($costWithMarginReturned);
                        foreach ($costWithMarginReturned as $result) {
                            if ($result === $lastElement) {
                                $totalCostsWithoutMargin += $totalCostSubstrate;
                                $totalCostsWithMargin += $result[0];
                                $totalFixedCostsWithoutMargin += $totalFixedCostSubstrate;
                                $totalVariableCostsWithoutMargin += $totalVariableCostSubstrate;
                                $totalFixedCostsWithMargin += $result[1];
                                $totalVariableCostsWithMargin += $result[2];
                                $totalTimes += $totalTimeSubstrate;

                                $results['quantities'][$copies]['operations'][$operationId]['name'] = $substrateName;
                                $results['quantities'][$copies]['operations'][$operationId]['time'] = $totalTimeSubstrate;
                                $results['quantities'][$copies]['operations'][$operationId]['cost'] = $totalCostSubstrate;
                                $results['quantities'][$copies]['operations'][$operationId]['fixed'] = $result[1];
                                $results['quantities'][$copies]['operations'][$operationId]['variable'] = $result[2];
                                $results['quantities'][$copies]['operations'][$operationId]['margin'] = $marginSubstrate;
                                $results['quantities'][$copies]['operations'][$operationId]['price'] = $result[0];

                                $operationId++;
                            }
                        }
                    }


                    $weight = $copies * $labelWidth / 1000 * $labelLength / 1000 * $substrateWeight / 1000;
                    $expedition = round($totalCostsWithoutMargin * (5/100), 2);

                    $results['quantities'][$copies]['totals']['weight'] = round($weight, 2);
                    $results['quantities'][$copies]['totals']['totalTimes'] = round($totalTimes, 2);
                    $results['quantities'][$copies]['totals']['totalCosts'] = round($totalCostsWithoutMargin, 2);
                    $results['quantities'][$copies]['totals']['totalFixedCosts'] = round($totalFixedCostsWithoutMargin, 2);
                    $results['quantities'][$copies]['totals']['totalVariableCosts'] = round($totalVariableCostsWithoutMargin, 2);
                    $results['quantities'][$copies]['totals']['expedition'] = $expedition;

                    // TODO : transport (expedition)

                } else {
                    if (empty($quantity['quantity'])) { $errors['errors'][] = "Vous devez saisir une quantité d'étiquettes"; }
                    if (empty($quantity['model'])) { $errors['errors'][] = "Vous devez saisir un nombre de modèles"; }
                    if (empty($quantity['plate'])) { $errors['errors'][] = "Vous devez saisir un nombre de clichés"; }
                }
            }
        }

        if (empty($errors)) {
            return $results;
        } else {
            return $errors;
        }
    }

    private function handleMargin($margin, $totalCost, $fixedCost, $variableCost) {
        $resultToReturn = array();

//        $totalCostWithMargin = round($totalCost + ($totalCost * ($margin / 100)), 4);
//        $fixedCostWithMargin = round($fixedCost + ($fixedCost * ($margin / 100)), 4);
//        $variableCostWithMargin = round($variableCost + ($variableCost * ($margin / 100)), 4);

        $totalCostWithoutMargin = round($totalCost, 4);
        $fixedCostWithoutMargin = round($fixedCost, 4);
        $variableCostWithoutMargin = round($variableCost, 4);

        $resultToReturn[] = array($totalCostWithoutMargin, $fixedCostWithoutMargin, $variableCostWithoutMargin);

        return $resultToReturn;
    }

    public function update($id, Array $datas)
    {
        $inputs = $datas['quotation'];
        $company = $datas['company'];
        $model = Quotation::findOrFail($id);
        $description = $inputs['quotation']['description'];
        $validityDate = date('Y-m-d', strtotime("+1 months"));
        $model->update([
            'description' => $description,
            'validity' => $validityDate,
            'settlement_id' => $inputs['quotation']['settlement_id'],
            'cost' => $inputs['quotation']['cost'],
            'thousand' => $inputs['quotation']['thousand'],
            'vat_price' => $inputs['quotation']['vat_price'],
            'price' => $inputs['quotation']['price'],
            'subject_email' => $inputs['quotation']['subject_email'],
            'body_email' => $inputs['quotation']['body_email'],
        ]);

        $vat = $model->vat;

        foreach ($inputs['quotation']['quantities'] as $quantity) {
            $modelQuantity = Quantity::findOrFail($quantity['id']);
            $subtotal = $quantity['subtotal'];
            $vatPrice = $subtotal * ($vat / 100);

            $modelQuantity->update([
                'margin' => $quantity['margin'],
                'thousand' => $quantity['thousand'],
                'subtotal' => $subtotal,
                'vat_price' => $vatPrice,
                'price' => $quantity['price'],
            ]);
        }

        return $this->getById($id, $company);
    }

    public function destroy($id)
    {
        $quantities = Quantity::where('quotation_id', $id)->get();
        foreach ($quantities as $quantity) {
            $quantity->delete();
        }
        $quantities = Quantity::where('quotation_id', $id)->get();
        foreach ($quantities as $quantity) {
            $quantity->delete();
        }
        Quotation::findOrFail($id)->delete();
        return $this->getPaginate(1);
    }

    public function sendEmail($id, Array $datas)
    {
        $inputs = $datas['quotation'];
        $company = $datas['company'];

        $modelQuotation = Quotation::find($id);

        $inputsQuotation = $inputs['quotation'];

        $validator = \Validator::make($inputsQuotation, [
            'subject_email'    => 'required|string',
            'body_email' => 'required|string',
        ]);


        // TODO : ask if register if email changed
        $inputsQuotationContact = $inputsQuotation['contact'];

        $validatorEmail = \Validator::make($inputsQuotationContact, [
            'email'    => 'required|email',
        ]);

        $validatorErrors = array();
        if ($validator->fails() || $validatorEmail->fails()) {
            if ($validator->fails()) {
                $validatorErrors[] = $validator->errors();
            }

            if ($validatorEmail->fails()) {
                $validatorErrors[] = $validatorEmail->errors();
            }
            return response()->json($validatorErrors, 500);
        } else {
            if ($modelQuotation->status_id === 1) {
                $status = 2;
            } else if ($modelQuotation->status_id === 2) {
                $status = 3;
            } else {
                $status = 3;
            }

            if ($modelQuotation) {
                $modelQuotation->subject_email = $inputsQuotation['subject_email'];
                $modelQuotation->body_email = $inputsQuotation['body_email'];
                $modelQuotation->status_id = $status;
                $modelQuotation->save();
            }

            $emailTo = $inputsQuotation['contact']['email'];

            $quotation = $this->getById($id, $company);

            try {
                Mail::to($emailTo)->send(new SendMailable($quotation));
            } catch(\Tymon\JWTAuth\Exceptions\JWTException $exception) {
                $this->serverstatuscode = "0";
                $this->serverstatusdes = $exception->getMessage();
            }
            if (Mail::failures()) {
                $this->statusdesc  =   "Error sending mail";
                $this->statuscode  =   "0";

            } else{
                $this->statusdesc  =   "Message sent Succesfully";
                $this->statuscode  =   "1";
            }
            return response()->json(compact('this'), 200);
        }
    }
}
