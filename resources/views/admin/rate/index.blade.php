@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'rates'])

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>Tarifes</h1>
        <a href="{{ route('admin.rate.create') }}" class="mb-2 mb-md-0 btn btn-outline-primary"><i class="fas fa-plus"></i>
            Nova Rate</a>
    </div>

    <table class="table table-striped table-condensed">

        <thead class="thead-dark">
            <tr>
                <th>Ordre</th>
                <th>Nom</th>
                <th>Productes associats</th>
                <th width=200>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($rates as $rate)
                <tr>
                    <td>{{ $rate->order }}</td>
                    <th class="th">{{ $rate->title_ca }}</th>
                    <td class="small">
                        @foreach ($rate->product as $prod)
                            <a href="{{ route('admin.price.index', $prod->id) }}">{{ $prod->title }}:</a>
                            {{ $prod->price }}<br>
                        @endforeach
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.rate.edit', $rate->id) }}" class="btn btn-primary btn-sm"><i
                                class="fas fa-edit"></i> Edita</a>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>


@stop
