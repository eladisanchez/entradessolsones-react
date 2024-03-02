<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Refund;

class RefundMail extends Mailable
{
    use Queueable, SerializesModels;

    private $refund;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.devolucio')->with('devolucio',$this->refund)
        ->subject('Devolució esdeveniment cancel·lat');
    }
}
