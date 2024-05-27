<?php

namespace App\Mail;

use App\Models\Abonne;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountBloquedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $a;
    public $url = 'http://www.projet.univ/examination';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Abonne $a)
    {
       $this->a = $a;
       $this->url = $this->url.'/'.$a;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return  $this->from('noreply@projet.univ')
        ->subject('Votre Compte a été bloqué.')
        ->markdown('emails.account-bloqued');
    }
}
