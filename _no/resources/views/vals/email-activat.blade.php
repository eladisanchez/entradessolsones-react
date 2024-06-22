<p>Hola {{$qr->usuari->name}},</p>

<p>Acabes d'utilitzar un dels teus vals de 10 € a {{$qr->comerc->name}}.</p>

<p>Has utilitzat {{$qr->usuari->qr_count}} de {{$qr->usuari->premi}} vals.</p>

<p><img src="{{url('assets/ensolsonat/img/logos/ajsolsona.png')}}" style="height: 50px;
  width: auto;
  margin: 0 12px;">
  <img src="{{url('assets/ensolsonat/img/logos/adlsolcar.png')}}" style="width: 120px; height: auto;
  margin: 0 12px;">
</p>