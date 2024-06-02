@if($item->espack)

    <article class="col-lg-6 col-md-8 col-sm-12 col-item">

        <a class="box box-doble" href="{{ route('product', ['name' => $item->name]) }}" style="background-image: url('{{url('img/medium/'.$item->name)}}.jpg');">

            <div class="over">
                <h4>{{ $item->title }}</h4>
                <ul>
                    @foreach($item->productesDelPack as $prod)
                    <li><i class="fas fa-ticket-alt"></i> <strong>{{ $prod->title }}</strong></li>
                    @endforeach
                </ul>
                <div class="resum-producte">
                    <p>{{ $item->resum }}</p>
                    <p class="mt-3"><i class="fas fa-shopping-cart"></i> <strong>{{ __("Compra entrades") }}</strong></p>
                </div>
            </div>

        </a>

    </article>

@else 

    <article class="col-lg-3 col-md-4 col-sm-6 col-item"><a href="{{ route('product', ['name' => $item->name]) }}" class="box" style="background-image: url('{{url('img/th/'.$item->name)}}.jpg');">
        <div class="over color_verd">
            <h4>{{ $item->title }}</h4>
            <div class="resum-producte">
                <p>{{ $item->resum }}</p>
                <p class="mt-3"><i class="fas fa-shopping-cart"></i> <strong>{{ __("Compra entrades") }}</strong></p>
            </div>
        </div>
    </a></article>

@endif