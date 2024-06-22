<p>Hola {{$qr->name}},</p>
<p>Per rebre els teus vals has de completar el pagament de 40€ amb targeta de crèdit mitjançant aquest enllaç: {{route('vals.tpv',['qr'=>$qr->id])}}</p>
<p>Per qualsevol dubte pots trucar o enviar un Whatsapp al 690 87 26 94.</p>

<p><img src="{{url('assets/ensolsonat/img/logos/ajsolsona.png')}}" style="height: 50px;
  width: auto;
  margin: 0 12px;">
  <img src="{{url('assets/ensolsonat/img/logos/adlsolcar.png')}}" style="width: 120px; height: auto;
  margin: 0 12px;">
</p>