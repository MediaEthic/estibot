<?php

namespace App\Http\Controllers;

use App\Repositories\QuotationRepository;
use App\Models\ {
    Consumable,
    Cutting,
    Finishing,
    Label,
    Printing,
    Quote,
    Substrate,
    Third,
    Quotation
};

use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $repository;

    public function __construct(QuotationRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Show the quote.
     *
     * @return \Illuminate\Http\Response
     */
    public function getQuote()
    {
        return Quote::inRandomOrder()->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->repository->getPaginate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPrintings()
    {
        return Printing::get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function getSubstrates()
//    {
//        return Substrate::get();
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFinishings()
    {
        return Finishing::get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function getConsumables()
//    {
//        return Consumable::get();
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function getCuttings()
//    {
//        return Cutting::get();
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPrice(Request $request)
    {
//        return $request;
        return $this->repository->getPrice($request->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $corporations = DB::table('corporations')->select('id', 'name')->get();
//
//        return view('parameters.corporation.establishments.create', compact('corporations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->repository->store($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->repository->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EstablishmentRequest $request, $id)
    {
//        Establishment::find($id)->update($request->all());
//
//        return redirect('parameters/corporation/establishments')->withOk("L'établissement " . $request->input('name') . " a bien été modifié.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $this->repository->destroy($id);
//
//        return response()->json();
    }
}
