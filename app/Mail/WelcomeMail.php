<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url = 'http://localhost:3000/news-letters/desabonnement';

    public $user ;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
         $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    
    public function build()
    {
        return  $this->from('noreply@projet.univ')
        ->subject('Votre Abonnement est a Kamer Kuisine est accepté')
        ->markdown('emails.welcome');
    }
}
