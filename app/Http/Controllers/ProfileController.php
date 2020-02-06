<?php

namespace App\Http\Controllers;

use App\Repositories\ProfileRepository;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $repository;

    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Show the company.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompany()
    {
        return $this->repository->getCompany();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWorkstations(Request $request)
    {
        return $this->repository->getWorkstations($request->all());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCompany(Request $request)
    {
        return $this->repository->updateCompany($request->all());
    }
}
