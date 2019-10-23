<?php

namespace App\Http\Controllers;

use App\Repositories\QuotationRepository;
use App\Models\ {
    Consumable,
    Cutting,
    Finishing,
    Printing,
    Quote,
    Substrate
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
        return $this->repository->getPrice($request->all());
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
    public function update(Request $request, $id)
    {
        return $this->repository->update($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }


    public function generatePDF($id)
    {
        $quotation = $this->repository->getById($id);

        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.quotation', compact('quotation'));
        $name = "Devis#-" . $quotation->id . ".pdf";
        return $pdf->stream($name);
    }

    public function sendEmail(Request $request, $id)
    {
        return $this->repository->sendEmail($id, $request->all());
    }
}
