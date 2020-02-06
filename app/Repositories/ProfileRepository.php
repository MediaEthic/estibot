<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\ApiRepository;

class ProfileRepository
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

    public function getCompany()
    {
        return Company::findOrFail(1);
    }

    public function getWorkstations(Array $datas)
    {
        return $this->repository->getWorkstations($datas);
    }

    public function updateCompany(Array $datas)
    {
        $company = $datas['company'];
        $model = Company::findOrFail(1);
        if ($company['prepress'] != "null" && !empty($company['prepress'])) {
            $model->prepress = $company['prepress'];
        } else {
            $model->prepress = null;
        }

        if ($company['winder'] != "null" && !empty($company['winder'])) {
            $model->winder = $company['winder'];
        } else {
            $model->winder = null;
        }

        $model->head_quotation = $company['head_quotation'];
        $model->foot_quotation = $company['foot_quotation'];

//            if (count($datas['logo'])) {
//                foreach ($datas['logo'] as $logo) {
//                    $logo->store('images');
//                }
//            }

            $model->save();
            return $model;

    }
}
