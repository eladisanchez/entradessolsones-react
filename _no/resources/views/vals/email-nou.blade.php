<p>Hola {{$qr->name}},</p>

<p><a href="{{route('vals.pdf',$qr->codi)}}">Aquí tens el teu PDF amb els codis</a></p>

<p><img src="{{url('assets/ensolsonat/img/logos/ajsolsona.png')}}" style="height: 50px;
  width: auto;
  margin: 0 12px;">
  <img src="{{url('assets/ensolsonat/img/logos/adlsolcar.png')}}" style="width: 120px; height: auto;
  margin: 0 12px;">
</p>