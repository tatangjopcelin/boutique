@component('mail::message')
# Annonce

<h1>Bonjour  {{ $user->name }}</h1>
<p>
    Une nouvelle recette vient d'etre créée, veuillez cliquez sur le boutton
ci-dessous pour voir la recette !
</p>
@component('mail::button', ['url' => $url])
Cliquez ici pour voir la recette
@endcomponent

Merci d'avoir d'avoir lu notre mail,<br>
{{ config('app.name') }}
@endcomponent
