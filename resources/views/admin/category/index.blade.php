@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'categories'])

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>Categories</h1>
        <a href="{{ route('admin.category.create') }}" class="mb-2 mb-md-0 btn btn-outline-primary"><i class="fas fa-plus"></i>
            Nova categoria</a>
    </div>


    <div class="card-columns mt-5">
        @foreach ($categories as $cat)
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $cat->title }}</h4>
                    <div class="text-muted small">{!! $cat->summary !!}</div>
                </div>
                @if (count($cat->products))
                    <ul class="small list-group list-group-flush">
                        @foreach ($cat->products as $prod)
                            <li class="list-group-item"><a
                                    href="{{ route('admin.product.edit', $prod->id) }}">{{ $prod->title }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-light small">Aquesta categoria no té productes</div>
                @endif
                <div class="card-body">
                    <a href="{{ route('admin.category.edit', $cat->id) }}" class="btn btn-primary btn-sm"><i
                            class="fas fa-edit"></i> Edita</a>
                </div>
            </div>
        @endforeach
    </div>

@stop
