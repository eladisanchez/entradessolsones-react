{{-- Cancel·lació --}}
@if(!$devolucio->dia_nou)

    <p>Hola {{$devolucio->comanda->name}},</p>

    <p>Ens posem en contacte amb vosaltres per informar-vos que l'esdeveniment o la visita <strong>{{$devolucio->producte->title}}</strong> del dia {{$devolucio->dia_cancelat->format('d-m-Y')}} a les {{$devolucio->dia_cancelat->format('H:i')}} h s'ha cancel·lat.</p>

    <p>Feu clic a l'enllaç següent per generar la devolució de {{$devolucio->total}} euros corresponent a les entrades que vau adquirir per a aquest esdeveniment o visita. La devolució es farà a la mateixa targeta de crèdit utilitzada per fer el pagament.</p>

    <p><a href="{{route('refund',['hash'=>$devolucio->hash])}}">{{route('refund',['hash'=>$devolucio->hash])}}</a></p>

    <p>Rebeu aquest missatge perquè teniu entrades per a <strong>{{$devolucio->producte->title}}</strong>. Us demanem que feu arribar aquesta informació a les persones que us acompanyin.</p>

    <p>Si teniu cap dubte, podeu enviar un correu a turisme@turismesolsones.com o bé trucar al 973 48 23 10.</p>

    <p>Moltes gràcies per la vostra comprensió.</p>

{{-- Canvi de dia --}}
@else 

    <p>Hola {{$devolucio->comanda->name}},</p>

    <p>Ens posem en contacte amb vosaltres per informar-vos que l’esdeveniment o la visita <strong>{{$devolucio->producte->title}}</strong> del dia {{$devolucio->dia_cancelat->format('d-m-Y')}} a les {{$devolucio->dia_cancelat->format('H:i')}} h se suspèn i es trasllada al dia {{$devolucio->dia_nou->format('d-m-Y')}} a les {{$devolucio->dia_nou->format('H:i')}}.</p>

    <p>Com que teníeu entrades comprades per a aquesta activitat, podeu:</p>

    <p><strong>- Acceptar el canvi de data per al dia {{$devolucio->dia_nou->format('d-m-Y')}} a les {{$devolucio->dia_nou->format('H:i')}}</strong> (us serviran les mateixes entrades).</p>

    <p>Podeu descarregar de nou el PDF de les entrades en aquest enllaç:<br><a href="{{ route('pdf-contracte',array($devolucio->comanda->sessio,$devolucio->comanda->id)) }}"></a></p>

    <p>Entenem que a hores d'ara potser no sabeu si us va bé la nova data; en aquest cas, si finalment no podeu assistir a l’activitat, us faríem la devolució més endavant.</p>

    <p><strong>- Sol·licitar la devolució de l’import de la compra.</strong></p>

    <p>Si voleu sol·licitar la devolució, feu clic a l'enllaç següent per generar la devolució de {{$devolucio->total}} euros corresponent a les entrades que vau adquirir per a aquest esdeveniment o visita. La devolució es farà a la mateixa targeta de crèdit utilitzada per fer el pagament.</p>

    <p><a href="{{route('refund',['hash'=>$devolucio->hash])}}">{{route('refund',['hash'=>$devolucio->hash])}}</a></p>

    <p>Rebeu aquest missatge perquè teniu entrades per a {{$devolucio->producte->title}}. Us demanem que feu arribar aquesta informació a les persones que us acompanyin.</p>

    <p>Si teniu cap dubte, podeu enviar un correu a turisme@turismesolsones.com o bé trucar al 973 48 23 10.</p> 

    <p>Moltes gràcies per la vostra comprensió.</p>


@endif

<p></p>

<p>
    <img src="http://entradessolsones.com/images/mail-signature-logo.png" alt="Turisme Solsonès / Consell Comarcal del Solsonès"><br>
    <strong>Entrades Solsonès</strong><br>
    C. Dominics, 12 (Pl. Consell Comarcal) – 25280 Solsona<br>
    Tel:  (00 34) 973 48 23 10
</p>

<img src="http://entradessolsones.com/images/mail-signature-footer.jpg">