@component('mail::message')
# Compte bloqué

<h1>Bonjour  {{ $a->prenom }}</h1>
<p>
   Nous vous informons que votre compte a été bloqué
   <hr>
   Vous pouvez demander un examen pour réactiver votre compte.
</p>
@component('mail::button', ['url' => $url])
M'examiner ?
@endcomponent

 <h2 class=" text-red-700">Kamer<span class=" text-emerald-500">Kuisine</span></h2>,<br>

@endcomponent