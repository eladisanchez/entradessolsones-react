<p>Benvolgut/da {{$order->name}},</p>

<p>Gràcies per RESERVAR entrades per als nostres centres turístics i/o esdeveniments. Per realitzar els serveis contractats  serà necessari presentar imprès el document següent:</p>

<p><strong style="font-size:16px;"><a href="{{ route('pdf-contracte',array($order->sessio,$order->id)) }}" style="text-decoration: none; color: #393F43;">Descarregui aquí les seves entrades electròniques</a></strong></p>

<p>Si no pot obrir l'enllaç de dalt, copii i enganxi aquesta adreça sencera al seu navegador: {{ route('pdf-contracte',array($order->sessio,$order->id)) }}</p>

<p>Si té alguna pregunta o necessita ajuda, contacti amb nosaltres a reserves@cardonaturisme.cat o al telèfon 93 869 24 75 (departament de reserves).</p>

<p>Recordi que vostè és el responsable del correcte ús de les entrades, en el cas que es fessin més d'una còpia de cadascuna d'elles, solament es permetria l'accés al recinte a la primera persona que accedís independentment de qui hagi efectuat la compra.</p>

<p>La compra d'aquestes entrades està sotmesa a les <a href="{{ url('ca/condicions') }}">Condicions d'ús</a> acceptades al realitzar la compra.</p>

<p>Recordi que totes les vendes d'entrades són definitives. No s'efectuen reemborsaments, canvis ni cancel·lacions.</p>

<p>Ha rebut aquest missatge de correu electrònic com a conseqüència de la seva compra a https://entrades.cardonaturisme.cat</p>

<p>Esperem que gaudeixi de la visita!</p>

{{--
<p><strong>Properament:</strong></p>
<p><img src="http://entrades.cardonaturisme.cat/images/properament.jpg" alt="Els Pastorets de Cardona"></p>
--}}