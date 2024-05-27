@component('mail::message')
# Remerciement

<h1>Bonjour  {{ $user->name }}</h1>
<p>
   Nous vous remercion d'avoir fait confiance a Kamer Kuisine en vous
   abonnant a notre site web, vous recevrez quotidiennement nos news
   letters pour les nouvelles recettes crées.<br>
   Votre login de connexion est {{ $user->email }}
   <hr>
   Vous pouvez vous désabonner a la new letter en cliquant sur ce bouton
</p>
@component('mail::button', ['url' => $url])
Se desabonner
@endcomponent

Merci pour votre abonnement a <h2 class=" text-red-700">Kamer<span class=" text-emerald-500">Kuisine</span></h2>,<br>

@endcomponent