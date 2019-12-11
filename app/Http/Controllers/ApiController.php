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
    public function searchCustomersForAutocomplete(Request $request)
    {
        return $this->repository->searchCustomersForAutocomplete($request->all());
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
    public function getSubstratesSearchCriteria(Request $request)
    {
        return $this->repository->getSubstratesSearchCriteria($request->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchSubstratesForAutocomplete(Request $request)
    {
        return $this->repository->searchSubstratesForAutocomplete($request->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFilteredSubstrates(Request $request)
    {
        return $this->repository->getFilteredSubstrates($request->all());
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
