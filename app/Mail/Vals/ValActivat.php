<?php

namespace App\Mail\Vals;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Vals\Qr;


class ValActivat extends Mailable
{
    
    use Queueable, SerializesModels;

    private $qr;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Qr $qr)
    {
        $this->qr = $qr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('vals.email-activat')
        ->with('qr',$this->qr)
        ->subject('Has utilitzat el teu val');
    }

}