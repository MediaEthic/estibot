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
        $model = Company::findOrFail(1);
        try {
            if ($datas['prepress'] != "null" && !empty($datas['prepress'])) {
                $model->prepress = $datas['prepress'];
            } else {
                $model->prepress = null;
            }

            if ($datas['winder'] != "null" && !empty($datas['winder'])) {
                $model->winder = $datas['winder'];
            } else {
                $model->winder = null;
            }

            if (count($datas['logo'])) {
                foreach ($datas['logo'] as $logo) {
                    $logo->store('images');
                }
            }

            $model->save();
            return $model;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the settings cannot be updated'
            ], 500);
        }

    }
}
