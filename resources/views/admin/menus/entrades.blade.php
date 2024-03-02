<ul class="nav nav-tabs nav-sub">
    <li class="nav-item">
        <a href="{{ route('admin.product.index') }}" 
        class="nav-link{{ $active=='products'?' active':''}}">Productes</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.category.index') }}" 
        class="nav-link{{ $active=='categories'?' active':''}}">Categories</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.rate.index') }}" 
        class="nav-link{{ $active=='rates'?' active':''}}">Tarifes</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.venue.index') }}" 
        class="nav-link{{ $active=='venues'?' active':''}}">Espais</a>
    </li>
</ul>