<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade as PDF;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $quotation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quotation)
    {
        $this->quotation = $quotation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emailFrom = $this->quotation->user->company->email;
        $emailSubject = $this->quotation->subject_email;

        $quotation['quotation'] = $this->quotation;
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.quotation', $quotation);
        $name = "Devis#" . $this->quotation->id . "-" . $this->quotation->updated_at . "-" . $this->quotation->third->name . ".pdf";

        return $this->from($emailFrom)
                    ->subject($emailSubject)
                    ->attachData($pdf->output(), $name)
                    ->markdown('emails.quotation');
    }
}
