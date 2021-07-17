<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageRecibido extends Mailable
{
    use Queueable, SerializesModels;

    public $subject= 'Activación cuenta Portal de Pagos ENERCER' ;
    public $msg;
    public $msg2;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request,$request2)
    {
        //
        $this->msg= $request;
        $this->msg2= $request2;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails/mensaje');

    }
}
