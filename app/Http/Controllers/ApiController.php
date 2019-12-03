<?php

namespace App\Http\Controllers;

use App\Repositories\ApiRepository;

use Illuminate\Http\Request;

class ApiController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCustomers(Request $request)
    {
        return $this->repository->getCustomers($request->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchCustomers(Request $request)
    {
        return $this->repository->searchCustomers($request->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getThirdContacts(Request $request)
    {
        return $this->repository->getThirdContacts($request->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getThirdLabels(Request $request)
    {
        return $this->repository->getThirdLabels($request->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFinishings(Request $request)
    {
        return $this->repository->getFinishings($request->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPrintings(Request $request)
    {
        return $this->repository->getPrintings($request->all());
    }
}
