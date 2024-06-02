<nav class="nav nav-pills">
    <a href="{{ route('admin.product.edit',$product->id) }}" 
        class="nav-link{{ $active=='info'?' active':''}}">
        Informació
    </a>
    @if($product->is_pack)
        <a href="{{ route('admin.pack.index',$product->id) }}" 
            class="nav-link{{$active=='pack'?' active':''}}">
            Productes del pack
        </a>
	@else
        @if(!$product->parent)
        <a href="{{ route('admin.ticket.index',$product->id) }}" 
            class="nav-link{{ $active=='tickets'?' active':''}}">
            Entrades
        </a>
        @endif
    @endif
    <a href="{{ route('admin.price.index',$product->id) }}" 
        class="nav-link{{ $active=='prices'?' active':''}}">
        Preus
    </a>
</nav>