<html>

<table>

        <thead>
        <tr>
            <th width="300">Producte - Rate</th>
            <th>Entrades venudes</th>
            <th>Total</th>
            <th>Devolucions</th>
            <th>Total a liquidar</th>
            <th>Total a liquidar (-5,5%)</th>
        </tr>
        </thead>

        @php 
        $liquidar = 0;
        $total = 0;
        $retornat = 0;
        @endphp
        
        <tbody>

        @foreach ($extract->vendes as $venda)
        <tr>
            <td>{{ $venda["producte"] }}</td>
            <td>{{ $venda["numEntrades"] }}</td>
            <td>{{ $venda["total"] }}</td>@php $total = $total + $venda["total"]; @endphp
            <td>{{ $venda["devolucio"] }}</td>@php $retornat = $retornat + $venda["devolucio"]; @endphp
            <td>{{ $venda["liquidar"] }}</td>
            <td>{{ $venda["liquidar"]*0.945 }}</td>@php $liquidar = $liquidar + $venda["liquidar"]; @endphp
        </tr>
        @endforeach

        <tr><th colspan="5"></th></tr>
        <tr>
            <th colspan="2" style="font-size: 14px">Total a liquidar ({{$extract->date_start->format('d-m-Y')}} al {{$extract->date_end->format('d-m-Y')}}) </th>
            <th><strong>{{ $total }} €</strong></th>
            <th><strong>{{ $retornat }} €</strong></th>
            <th><strong>{{ $liquidar }} €</strong></th>
            <th><strong>{{ $liquidar*0.945 }} €</strong></th>
        </tr>

        </tbody>

</table>

</html>
