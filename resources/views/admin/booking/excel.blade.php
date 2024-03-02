
<table>

        <thead>
        <tr>
            <th>order</th>
            <th>Perfil</th>
            <th>Data order</th>
            <th>Client</th>
            <th>Email</th>
            <th>Telèfon</th>
            <th>CP</th>
            <th>Newsletter</th>
            <th>Producte</th>
            <th>Dia</th>
            <th>Hora</th>
            <th>Localitat</th>
            <th>Rate</th>
            <th>Quantitat</th>
            <th>Preu unitari</th>
            <th>Subtotal</th>
            <th>Codi descompte</th>
            <th>Forma de pagament</th>
            <th>Total order</th>
            <th>Idioma</th>
            <th>Observacions</th>
        </tr>
        </thead>
        
        <tbody>
        @foreach ( $bookings as $booking )
        @if(!$booking->order->trashed())
        <tr>
            <td>{{ $booking->order->id }}</td>
            <td>{{ $booking->order->target }}</td>
            <td>{{ $booking->order->created_at }}</td>
            <td>{{ $booking->order->name }}</td>
            <td>{{ $booking->order->email }}</td>
            <td>{{ $booking->order->telefon }}</td>
            <td>{{ $booking->order->cp }}</td>
            <td>{{ $booking->order->newsletter }}</td>
            <td>
                @if ($booking->is_pack)
                <strong>{{ $booking->product->title_ca }}</strong>
                @else
                {{ $booking->product->title_ca }}
                @endif
            </td>
            <td> 
                @if(isset($booking->day)) 
                {{ $booking->day }} 
                @endif
            </td>
            <td> 
                @if(isset($booking->hour)) 
                {{ $booking->hour }} 
                @endif
            </td>
            <td> 
                @if($booking->seat) 
                {{ \App\Helpers\Common::seient($booking->seat) }} 
                @endif
            </td>
            <td>{{ $booking->rate->title_ca }}</td>
            <td>{{ $booking->tickets }}</td>
            <td>{{ round($booking->price,2) }}</td>
            <td>{{ $booking->tickets*round($booking->preu,2) }}</td>
            <td>{{ $booking->order->coupon }}</td>
            <td>{{ $booking->order->payment }}</td>
            <td>{{ round($booking->order->total,2) }}</td>
            <td>{{ $booking->order->language }}</td>
            <td>{{ $booking->order->observations }}</td>
        </tr>
        @endif
        @endforeach
        </tbody>

</table>
