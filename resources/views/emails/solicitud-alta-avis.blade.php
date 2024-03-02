<p>Nova sol·licitud d'entitat:</p>

<p>
    <strong>Nom de l'entitat:</strong> {{ $request['entitat'] }}<br>
    <strong>Persona de contacte:</strong> {{ $request["nom"] }}<br>
    <strong>Email:</strong> {{ $request["email"] }}<br>
    <strong>Telèfon:</strong> {{ $request["telefon"] }}
</p>

@if(isset($request["ong"]))
<p>És una entitat sense ànim de lucre</p>
@endif