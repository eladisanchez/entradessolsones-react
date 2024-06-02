@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'venues'])

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>Espais</h1>
        <a href="{{ route('admin.venue.create') }}" class="mb-2 mb-md-0 btn btn-outline-primary"><i class="fas fa-plus"></i>
            Nou espai</a>
    </div>

    <div class="card-columns mt-5">
        @foreach ($venues as $venue)
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $venue->name }}</h4>
                    <div class="text-muted small">{{ $venue->address }}</div>
                    @if (count($venue->products))
                        <div class="text-muted small">{{ count($venue->products) }} productes</div>
                    @else
                        <div class="text-muted small">Aquest espai no té productes</div>
                    @endif
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.venue.edit', $venue->id) }}" class="btn btn-primary btn-sm"><i
                            class="fas fa-edit"></i> Edita espai</a>
                </div>
            </div>
        @endforeach
    </div>

@stop
