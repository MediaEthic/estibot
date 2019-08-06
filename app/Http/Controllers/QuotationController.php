<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use App\Models\Quotation;

class QuotationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        return Quotation::with('third')
            ->latest()
            ->paginate(15);
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
    public function store(EstablishmentRequest $request)
    {
//        $establishment = $this->repository->store($request->all());
//
//        return redirect('parameters/corporation/establishments')->withOk("L'établissement " . $establishment->name . " a bien été créé.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $establishment = $this->repository->getById($id);
//        $corporations = DB::table('corporations')->select('id', 'name')->get();
//
//        return view('parameters.corporation.establishments.edit',  compact('establishment', 'corporations'));
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
