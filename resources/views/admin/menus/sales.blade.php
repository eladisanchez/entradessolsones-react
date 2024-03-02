<ul class="nav nav-tabs nav-sub">
    <li class="nav-item">
        <a href="{{ route('admin.order.index') }}" 
            class="nav-link{{$active=='orders'?' active':''}}">
            Comandes
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.booking.index') }}"
            class="nav-link{{$active=='bookings'?' active':''}}">
            Reserves
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.extract.index') }}"
            class="nav-link{{$active=='extracts'?' active':''}}">
            Extractes
        </a>
    </li>
</ul>