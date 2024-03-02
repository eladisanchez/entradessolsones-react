<?php

namespace App\Mail\Vals;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Vals\Usuari;


class ValPagamentFallit extends Mailable
{
    
    use Queueable, SerializesModels;

    private $usuari;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Usuari $usuari)
    {
        $this->usuari = $usuari;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('vals.email-fallit')
        ->with('qr',$this->usuari)
        ->subject('Completa el pagament');
    }

}