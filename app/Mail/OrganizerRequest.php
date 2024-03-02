<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Common;

class OrganizerRequest extends Mailable
{
    use Queueable, SerializesModels;

    private $text;
    private $inputs;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inputs)
    {
        $this->inputs = $inputs;
        $text = Common::optext('email_solicitud_alta');
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.solicitud-alta')->with('text',$this->text)
        ->subject('Sol·licitud Entrades Solsonès '.$this->inputs["entitat"]);
    }
}
