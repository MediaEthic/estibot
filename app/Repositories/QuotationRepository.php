<?php

namespace App\Repositories;

use App\Models\{Consumable,
    Contact,
    Cutting,
    Finishing,
    FinishingLabel,
    Label,
    Packing,
    Printing,
    Quantity,
    Quotation,
    Substrate,
    Third};

class QuotationRepository
{
    protected $model;

    public function __construct(Quotation $model)
    {
        $this->model = $model;
    }

    public function getPaginate()
    {
        return $this->model->with('third')
            ->latest()
            ->paginate(15);
    }

    public function getById($id)
    {
        return $this->model->with('third')
            ->with('quantities')
            ->findOrFail($id);
    }

    private function saveProspect(Third $model, Array $inputs)
    {
        if (!empty($inputs['name'])) $model->name = $inputs['name'];
        if (!empty($inputs['address'])) $model->address = $inputs['address'];
        if (!empty($inputs['zipcode'])) $model->zipcode = $inputs['zipcode'];
        if (!empty($inputs['city'])) $model->city = $inputs['city'];
        $model->save();

        return $model;
    }

    private function saveContact(Contact $model, Array $inputs, $third)
    {
        $model->third_id = $third;
        if (!empty($inputs['civility'])) $model->civility = $inputs['civility'];
        if (!empty($inputs['name'])) $model->name = $inputs['name'];
        if (!empty($inputs['surname'])) $model->surname = $inputs['surname'];
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

    private function saveLabel(Label $model, Array $inputs, $printing, $substrate, $cutting, $packing)
    {
        if (!empty($inputs['name'])) $model->name = $inputs['name'];
        if (!empty($inputs['width'])) $model->width = $inputs['width'];
        if (!empty($inputs['length'])) $model->length = $inputs['length'];
        if (!empty($printing['press'])) $model->printing_id = $printing['press'];
        if (!empty($printing['colors'])) $model->number_colors = $printing['colors'];
        if (!empty($printing['quadri'])) $model->quadri = $printing['quadri'];
        if (!empty($substrate)) $model->substrate_id = $substrate;
        if (!empty($cutting)) $model->cutting_id = $cutting;
        if (!empty($packing['direction'])) $model->winding = $packing['direction'];
        if (!empty($packing['packing'])) $model->packing = $packing['packing'];
        $model->save();

        return $model;
    }

    private function saveFinishingLabel(FinishingLabel $model, Array $inputs, $label)
    {
        if (!empty($inputs['type'])) $model->finishing_id = $inputs['type'];
        if (!empty($label)) $model->label_id = $label;
        if (!empty($inputs['shape'])) $model->shape = $inputs['shape'];
        if (!empty($inputs['reworking'])) $model->reworking = $inputs['reworking'];
        return $model;
    }

    private function saveConsumable(Consumable $model, Array $inputs, $finishingLabel)
    {
        if (!empty($finishingLabel)) $model->finishing_label = $finishingLabel;
        if (!empty($inputs['name'])) $model->name = $inputs['name'];
        if (!empty($inputs['width'])) $model->width = $inputs['width'];
        if (!empty($inputs['price'])) $model->price = $inputs['price'];
        return $model;
    }

    private function saveQuotation(Quotation $model, Array $inputs, $price, $third, $contact, $label)
    {
        if (!empty($inputs['summary'])) $model->description = $inputs['summary'];
        $images = ["undraw_Credit_card_3ed6.svg", "undraw_make_it_rain_iwk4.svg", "undraw_printing_invoices_5r4r.svg", "undraw_Savings_dwkw.svg"];
        $model->image = $images[array_rand($images)];
        $model->third_type = $inputs['identification']['third']['type'];
        if (!empty($third)) $model->third_id = $third;
        if (!empty($contact)) $model->contact_id = $contact;
        $model->label_type = $inputs['description']['label']['type'];
        if (!empty($label)) $model->label_id = $label;
        $validityDate = date('Y-m-d', strtotime("+3 months"));
        $model->validity = $validityDate;

        $allTotalCosts = array();
        $allQuantities = array();
        foreach ($price['quantities'] as $key => $quantity) {
            $allTotalCosts[] = $quantity['totals']['totalCosts'];
            $allQuantities[] = $key;
        }
        $minQuantity = min($allQuantities);

        $model->cost = $price['quantities'][$minQuantity]['totals']['totalCosts'];
        $thousandPrice = ($price['quantities'][$minQuantity]['totals']['totalCosts'] / $minQuantity) * 1000;
        $model->thousand = $thousandPrice;
        $model->quantity = $minQuantity;
        $model->shipping = $price['quantities'][$minQuantity]['totals']['expedition'];
        $model->vat = 20;
        $model->workflow = serialize($inputs);
        $model->price = serialize($price);
        $model->save();
        return $model;
    }

    private function saveQuantities(Quantity $model, Array $inputs, $quotation)
    {
        $model->quotation_id = $quotation['id'];
        $model->quantity = $inputs['datas']['copies'];
        $model->time = $inputs['totals']['totalTimes'];
        $model->weight = $inputs['totals']['weight'];
        $model->margin = 20;
        $model->cost = $inputs['totals']['totalCosts'];
        $model->thousand = ($inputs['totals']['totalCosts'] / $inputs['datas']['copies']) * 1000;
        $model->shipping = $inputs['totals']['expedition'];
        $subtotal = $inputs['totals']['totalCosts'] + $inputs['totals']['expedition'];
        $vat_price = $subtotal * ($quotation['vat'] / 100);
        $model->vat_price = $subtotal * ($quotation['vat'] / 100);
        $model->price = $subtotal + $vat_price;
        $model->save();
        return $model;
    }

    public function store(Array $inputs)
    {
        $workflow = $inputs['workflow'];
        $identification = $workflow['identification'];
        $description = $workflow['description'];
        $printing = $workflow['printing'];
        $finishing = $workflow['finishing'];
        $packing = $workflow['packing'];

        $third = null;
        $contact = null;
        if ($identification['third']['type'] === "new") {
            $third = $this->saveProspect(new Third(), $identification['third']);
            $contact = $this->saveContact(new Contact(), $identification['contact'], $third['id']);
        }

        $substrate = null;
        if ($printing['substrate']['type'] === "new") {
            $substrate = $this->saveSubstrate(new Substrate(), $printing['substrate']);
        }

        $cutting = null;
        if ($finishing['cutting']['type'] === "new") {
            $cutting = $this->saveCutting(new Cutting(), $finishing['cutting']);
        }

        $label = null;
        if ($description['label']['type'] === "new") {
            $label = $this->saveLabel(new Label(), $description['label'], $printing, $substrate['id'], $cutting['id'], $packing);
        }

        foreach ($finishing['finishings'] as $finishing) {
            $finishingID = $finishing['type'];
            $finishingLabel = $this->saveFinishingLabel(new FinishingLabel(), $finishing, $label['id']);
            if ($finishing['presence_consumable']) {
                $consumable = $this->saveConsumable(new Consumable(), $finishing['consumable'], $finishingLabel['id']);
            }
        }

        $quotation = $this->saveQuotation(new Quotation(), $workflow, $inputs['price'], $third['id'], $contact['id'], $label['id']);

        foreach ($inputs['price']['quantities'] as $workflowQuantity) {
            $quantities = $this->saveQuantities(new Quantity(), $workflowQuantity, $quotation);
        }

        return $quotation;
    }

    public function getPrice($inputs) {
        $results = array();
        $errors = array();

        $results['workflow'] = $inputs['workflow'];

        $margin = 20;

        $workflow = $inputs['workflow'];
        $identification = $workflow['identification'];
        $third = $identification['third'];
        $contact = $identification['contact'];

        if ($third['type'] === "old") {
            $costumer = Third::find($third['id']);
        } else {
            if (empty($third['name'])) { $errors['errors'][] = "Vous devez saisir le nom du prospect"; }
            if (empty($third['zipcode'])) { $errors['errors'][] = "Vous devez saisir le code postal du prospect"; }
            if (empty($contact['email'])) { $errors['errors'][] = "Vous devez saisir l'adresse e-mail du prospect"; }
        }


        /*
         * Substrate
         */
        $printing = $workflow['printing'];
        $substrate = $printing['substrate'];
        if ($substrate['type'] === 'old') {

        } else {
            $substrateName = $substrate['name'];
            $substrateWidth = $substrate['width'];
            $substrateWeight = $substrate['weight'];
            $substratePrice = $substrate['price'];
        }

        if (empty($substrateWidth)) { $errors['errors'][] = "Vous devez saisir la laize du support d'impression"; }
        if (empty($substrateWeight)) { $errors['errors'][] = "Vous devez saisir le grammage du support d'impression"; }
        if (empty($substratePrice)) { $errors['errors'][] = "Vous devez saisir le prix au mètre linéaire du support d'impression"; }

        /*
         * Printing
        */
        $pressID = $printing['press'];
        if (!empty($pressID)) {
            $press = Printing::find($pressID);
        } else {
            $errors['errors'][] = "Vous devez choisir une machine d'impression";
        }

        if (empty($errors)) {
            if (!empty($press->number_colors)) {
                if ($printing['colors'] > $press->number_colors) { $errors['errors'][] = "La machine ne peut imprimer que $press->number_colors couleurs"; }
            }
            if (!empty($press->size_paperminx) && !empty($press->size_papermaxx)) {
                if ($substrateWidth <= $press->size_paperminx) { $errors['errors'][] = "La laize du support d'impression est inférieure à la laize minimum de la machine"; }
                if ($substrateWidth >= $press->size_papermaxx) { $errors['errors'][] = "La laize du support d'impression est supérieure à la laize maximum de la machine"; }
            } else {
                $results['warnings'][] = "Veuillez vérifier que la laize du support d'impression correspond à celle de la machine";
            }
        }

        $description = $workflow['description'];
        /*
         * Label
        */
        $label = $description['label'];

        if ($label['type'] === "old") {
            $labelID = $label['id'];
            if (!empty($labelID)) {
                $label = Label::find($labelID);
                $labelName = $label->name;
                $labelWidth = $label->width;
                $labelLength = $label->length;
            }
        } else {
            $labelName = $label['name'];
            $labelWidth = $label['width'];
            $labelLength = $label['length'];
        }

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

        if ($cutting['type'] === "old") {
            $cuttingID = $cutting['id'];
            if (!empty($cuttingID)) {
                $cutting = Cutting::find($cuttingID);
                $cuttingName = $cutting->name;
                $cuttingDimensionWidth = $cutting->dimension_width;
                $cuttingDimensionLength = $cutting->dimension_length;
                $cuttingBleedWidth = $cutting->bleed_width;
                $cuttingBleedLength = $cutting->bleed_length;
                $cuttingPoseWidth = $cutting->pose_width;
                $cuttingPoseLength = $cutting->pose_length;
            }
        } else {
            $cuttingName = $cutting['name'];
            $cuttingDimensionWidth = $cutting['dimension_width'];
            $cuttingDimensionLength = $cutting['dimension_length'];
            $cuttingBleedWidth = $cutting['bleed_width'];
            $cuttingBleedLength = $cutting['bleed_length'];
            $cuttingPoseWidth = $cutting['pose_width'];
            $cuttingPoseLength = $cutting['pose_length'];
        }

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
                $labelSizeWidthWithBleed = $labelSizeWidth + $cuttingBleedWidth;
                $labelSizeLengthWithBleed = $labelSizeLength + $cuttingBleedLength;
//            }

            $numberPosesWidth = $labelSizeWidthWithBleed * $cuttingPoseWidth;
//            $numberPosesLength = $labelSizeLengthWithBleed * $cuttingPoseLength;

            if (!empty($press->printable_areax)) {
                if ($numberPosesWidth > $press->printable_areax) {
                    $errors['errors'][] = "La laize de l'outil excède celle du support d'impression.";
                }
            } else {
                $results['warnings'][] = "Veuillez vérifier avec l'atelier de production que la laize du support d'impression est bien inférieure à celle de la machine.";
            }
        }

        /*
         * Finishings
         */
        if (empty($errors)) {
            if (!empty($press->cadence)) {
                $pressCadence = $press->cadence;
            } else {
                $errors['errors'][] = "Aucune cadence n'a été renseignée pour la machine d'impression";
            }
        }

        if (empty($errors)) {
            $slowerCadence = $pressCadence;
            $finishings = $finishing['finishings'];
            foreach ($finishings as $finishing) {
                $finishingPress = Finishing::find($finishing['type']);
//                    TODO : voir si gestion des finitions sur autre machine
                    if ($finishingPress->cadence < $slowerCadence) {
                        $slowerCadence = $finishingPress->cadence;
                    }

            }
        }

        $quantities = $description['quantities'];
        if (empty($errors)) {
            $substratePriceLinearMeter = $substrateWidth / 1000 * $substratePrice;

            foreach ($quantities as $quantity) {
                if (!empty($quantity['quantity']) && !empty($quantity['model']) && !empty($quantity['plate'])) {
                    $totalFixedCosts = 0;
                    $totalVariableCosts = 0;
                    $totalCosts = 0;
                    $totalTimes = 0;

                    $copies = intval($quantity['quantity']);
                    $models = intval($quantity['model']);
                    $plates = intval($quantity['plate']);

                    $results['quantities'][$copies]['datas']['copies'] = $copies;
                    $results['quantities'][$copies]['datas']['models'] = $models;
                    $results['quantities'][$copies]['datas']['plates'] = $plates;

                    $operationId = 0;

                    $prepressMinutes = intval($quantity['minute']);
                    $prepressHours = intval($quantity['hour']);

                    if ($prepressMinutes > 0 || $prepressHours > 0) {
                        $prepressMinutesInHour = $prepressMinutes / 60;
                        $totalTimePrepress = round($prepressHours + $prepressMinutesInHour, 4);
                        $totalCostPrepress = $totalTimePrepress * 40; // update hourly rate
                        $totalFixedCostPrepress = $totalCostPrepress;
                        $totalVariableCostPrepress = 0;

                        $marginPrepress = $margin;

                        $results['quantities'][$copies]['time'][] = "Prépresse : " . $totalTimePrepress . "h";
                        $results['quantities'][$copies]['cost'][] = "Prépresse : " . $totalCostPrepress . "€";

                        $costWithMarginReturned = $this->handleMargin($marginPrepress, $totalCostPrepress, $totalFixedCostPrepress, $totalVariableCostPrepress);
                        if (!empty($costWithMarginReturned)) {
                            $lastElement = end($costWithMarginReturned);
                            foreach ($costWithMarginReturned as $result) {
                                if ($result === $lastElement) {
                                    $totalCosts += $result[0];
                                    $totalFixedCosts += $result[1];
                                    $totalVariableCosts += $result[2];
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
                    }


                    // Clichés
                    $totalCostPlates = $plates * $press->plate;
                    $results['quantities'][$copies]['cost'][] = "Clichés : " . $totalCostPlates . "€";

                    // Outil de découpe
                    $cuttingShape = floatval($cutting['shape']);
                    if ($cuttingShape > 0) {
                        $costCuttingShape = $cuttingShape;
                        $results['quantities'][$copies]['cost'][] = "Outil de découpe : " . $costCuttingShape . "€";
                    } else {
                        $costCuttingShape = 0;
                    }

                    // Métrage de calage impression
                    $meterMakereadyPrinting = $press->overlay_sheet + ($press->overlay_sheet_color * $plates);
                    $meterMakereadyPress = $meterMakereadyPrinting;
                    $results['quantities'][$copies]['wastage'][] = "Métrage de calage pour l'impression : " . $meterMakereadyPrinting;

                    // Temps de calage impression
                    $timeMakereadyPrinting = $press->makeready_times + ($press->makeready_times_color * $plates);
                    $results['quantities'][$copies]['time'][] = "Calage de l'impression : " . $timeMakereadyPrinting . "h";


                    if ($press->unit_cadence === "striking") {
                        // Nombre de frappes de roulage
                        $strikingRun = $copies / ($cuttingPoseWidth * $cuttingPoseLength);
                        $results['quantities'][$copies]['wastage'][] = "Nombre de frappes : " . $strikingRun;

                        // Métrage de roulage impression
                        $substrateLinear = $strikingRun * $labelSizeLengthWithBleed * $cuttingPoseLength / 1000;
                    } else {
                        $substrateLinear = (($copies / $cuttingPoseWidth) * $labelSizeLengthWithBleed / 1000); // longueur papier en mL
                    }


                    // Temps de roulage
                    if ($press->unit_cadence === "striking") {
                        $timeProductionPress = $strikingRun / $slowerCadence;
                    } else {
                        $timeProductionPress = $substrateLinear / $slowerCadence;
                    }

                    $results['quantities'][$copies]['time'][] = "Roulage : " . $timeProductionPress . "h";

                    $totalTimeProduction = round($timeMakereadyPrinting + $timeProductionPress, 4);
                    $totalFixedCostProduction = $costCuttingShape + ($timeMakereadyPrinting * $press->hourly_rate) + $totalCostPlates;
                    $totalVariableCostProduction = $timeProductionPress * $press->hourly_rate;
                    $totalCostProduction = $totalFixedCostProduction + $totalVariableCostProduction;
                    $results['quantities'][$copies]['cost'][] = "Calage de la machine : " . $timeMakereadyPrinting * $press->hourly_rate . "€";
                    $results['quantities'][$copies]['cost'][] = "Roulage de la machine : " . $totalVariableCostProduction . "€";

                    $marginProduction = $margin;

                    $costWithMarginReturned = $this->handleMargin($marginProduction, $totalCostProduction, $totalFixedCostProduction, $totalVariableCostProduction);
                    if (!empty($costWithMarginReturned)) {
                        $lastElement = end($costWithMarginReturned);
                        foreach ($costWithMarginReturned as $result) {
                            if ($result === $lastElement) {
                                $totalCosts += $result[0];
                                $totalFixedCosts += $result[1];
                                $totalVariableCosts += $result[2];
                                $totalTimes += $totalTimeProduction;

                                $results['quantities'][$copies]['operations'][$operationId]['name'] = "Production";
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

                    $marginFinishing = $margin;
                    foreach ($finishings as $finishing) {
//                        TODO : reworking reprise sur machine
                        $finishingID = intval($finishing['type']);
                        $finishingPress = Finishing::find($finishingID);
                        $meterMakereadyFinishing = $finishingPress->overlay_sheet;
                        $meterMakereadyPress += $meterMakereadyFinishing;
                        $results['quantities'][$copies]['wastage'][] = "Métrage de calage pour " . $finishingPress->name . " : " . $meterMakereadyFinishing;

                        // Temps de calage finition
                        $totalTimeFinishing = round($finishingPress->makeready_times, 4);
                        $results['quantities'][$copies]['time'][] = "Calage pour " . $finishingPress->name . " : " . $totalTimeFinishing . "h";

                        $finishingShape = intval($finishing['shape']);
                        if ($finishingShape > 0) {
                            $costFinishingShape = $finishingShape;
                            $results['quantities'][$copies]['cost'][] = "Outil de $finishingPress->name : " . $costCuttingShape . "€";
                        } else {
                            $costFinishingShape = 0;
                        }

                        $totalFixedCostFinishing = $costFinishingShape + ($totalTimeFinishing * $press->hourly_rate);
                        $results['quantities'][$copies]['cost'][] = "Calage pour " . $finishingPress->name . " : " . $totalFixedCostFinishing . "€";

                        $totalCostConsumable = 0;
                        if ($finishing['presence_consumable']) {
                            $consumable = $finishing['consumable'];
                            if (!empty($consumable)) {
                                if (!empty($consumable['width']) && !empty($consumable['price'])) {
                                    $consumableWidth = intval($consumable['width']);
                                    $consumablePrice = floatval($consumable['price']);
                                    if ($consumableWidth >= $press->size_paperminx && $consumableWidth <= $press->size_papermaxx) {
                                        $totalCostConsumable = $substrateLinear * ($consumableWidth / 1000 * $consumablePrice);
                                        $results['quantities'][$copies]['cost'][] = "Consommable " . $consumable['name'] . " pour la finition " . $finishingPress->name . " : " . $totalCostConsumable . "€";
                                    } else {
                                        $errors['errors'][] = "La laize du consommable n'est pas en corrélation avec celle de la machine";
                                    }
                                } else {
                                    if (empty($consumable['width'])) { $errors['errors'][] = "Vous devez saisir la laize du consommable " . $consumable['name']; }
                                    if (empty($consumable['price'])) { $errors['errors'][] = "Vous devez saisir le prix du consommable " . $consumable['name']; }
                                }
                            } else {
                                $errors['errors'][] = "Vous devez saisir les données relatives au consommable pour la finition " . $finishingPress->name;
                            }
                        }

                        $totalVariableCostFinishing = $totalCostConsumable;
                        $totalCostFinishing = $totalFixedCostFinishing + $totalVariableCostFinishing;

                        $costWithMarginReturned = $this->handleMargin($marginFinishing, $totalCostFinishing, $totalFixedCostFinishing, $totalVariableCostFinishing);
                        if (!empty($costWithMarginReturned)) {
                            $lastElement = end($costWithMarginReturned);
                            foreach ($costWithMarginReturned as $result) {
                                if ($result === $lastElement) {
                                    $totalCosts += $result[0];
                                    $totalFixedCosts += $result[1];
                                    $totalVariableCosts += $result[2];
                                    $totalTimes += $totalTimeFinishing;

                                    $results['quantities'][$copies]['operations'][$operationId]['name'] = $finishingPress->name;
                                    $results['quantities'][$copies]['operations'][$operationId]['time'] = $totalTimeFinishing;
                                    $results['quantities'][$copies]['operations'][$operationId]['cost'] = $totalCostFinishing;
                                    $results['quantities'][$copies]['operations'][$operationId]['fixed'] = $result[1];
                                    $results['quantities'][$copies]['operations'][$operationId]['variable'] = $result[2];
                                    $results['quantities'][$copies]['operations'][$operationId]['margin'] = $marginFinishing;
                                    $results['quantities'][$copies]['operations'][$operationId]['price'] = $result[0];

                                    $operationId++;
                                }
                            }
                        }
                    }


                    // Prix total papier
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
                                $totalCosts += $result[0];
                                $totalFixedCosts += $result[1];
                                $totalVariableCosts += $result[2];
                                $totalTimes += $totalTimeSubstrate;

                                $results['quantities'][$copies]['operations'][$operationId]['name'] = "Support d'impression";
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


                    // Conditionnement - bonbineuse
                    $conditioning = intval($packing['packing']);

                    if ($conditioning > 0) {
                        $windingMachine = Packing::first();
                        // Temps de calage bobineuse
                        $timeMakereadyWinding = $windingMachine->makeready_times;
                        $results['quantities'][$copies]['time'][] = "Calage de la bobineuse : " . $timeMakereadyWinding . "h";

                        // Temps de production bobineuse
                        $timeProductionWinding = $substrateLinear / $windingMachine->cadence / 60 + $copies / $conditioning * $windingMachine->duration;
                        $results['quantities'][$copies]['time'][] = "Roulage de la bobineuse : " . $timeProductionWinding . "h";

                        $totalTimeWinding = round($timeMakereadyWinding + $timeProductionWinding, 4);

                        // Coût de production bobineuse
                        $totalFixedCostWinding = $timeMakereadyWinding * $windingMachine->hourly_rate;
                        $results['quantities'][$copies]['cost'][] = "Calage de la bobineuse : " . $totalFixedCostWinding . "€";
                        $totalVariableCostWinding = $timeProductionWinding * $windingMachine->hourly_rate;
                        $results['quantities'][$copies]['cost'][] = "Roulage de la bobineuse : " . $totalVariableCostWinding . "€";
                        $totalCostWinding = $totalFixedCostWinding + $totalVariableCostWinding;

                        $marginWinding = $margin;

                        $costWithMarginReturned = $this->handleMargin($marginWinding, $totalCostWinding, $totalFixedCostWinding, $totalVariableCostWinding);
                        if (!empty($costWithMarginReturned)) {
                            $lastElement = end($costWithMarginReturned);
                            foreach ($costWithMarginReturned as $result) {
                                if ($result === $lastElement) {
                                    $totalCosts += $result[0];
                                    $totalFixedCosts += $result[1];
                                    $totalVariableCosts += $result[2];
                                    $totalTimes += $totalTimeWinding;

                                    $results['quantities'][$copies]['operations'][$operationId]['name'] = "Conditionnement";
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
                    }

                    $weight = $copies * $labelWidth / 1000 * $labelLength / 1000 * $substrateWeight / 1000;
                    $expedition = round($totalCosts * (5/100), 2);

                    $results['quantities'][$copies]['totals']['weight'] = $weight;
                    $results['quantities'][$copies]['totals']['totalTimes'] = round($totalTimes, 2);
                    $results['quantities'][$copies]['totals']['totalCosts'] = round($totalCosts, 2);
                    $results['quantities'][$copies]['totals']['totalFixedCosts'] = $totalFixedCosts;
                    $results['quantities'][$copies]['totals']['totalVariableCosts'] = $totalVariableCosts;
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

        $totalCostWithMargin = round($totalCost + ($totalCost * ($margin / 100)), 4);
        $fixedCostWithMargin = round($fixedCost + ($fixedCost * ($margin / 100)), 4);
        $variableCostWithMargin = round($variableCost + ($variableCost * ($margin / 100)), 4);

        $resultToReturn[] = array($totalCostWithMargin, $fixedCostWithMargin, $variableCostWithMargin);

        return $resultToReturn;
    }

    public function update($id, Array $inputs)
    {
        $this->save($this->getById($id), $inputs);
    }

    public function destroy($id)
    {
        $this->getById($id)->delete();
        return response()->json();
    }
}
