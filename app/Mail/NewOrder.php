<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\order;
use App\Helpers\Common;

class NewOrder extends Mailable
{
    use Queueable, SerializesModels;

    private $text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $text = Common::optext('email_comanda');
        $this->text = strtr($text,[
            '[nom_client]' => $order->name,
            '[link_pdf]' => route('pdf-contracte',array($order->session,$order->id))
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.comanda')->with('text',$this->text)
        ->subject('Les teves entrades');
    }
}
