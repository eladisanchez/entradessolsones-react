@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'products'])

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
        <h1>Productes</h1>
        <div>

            @if (request()->query('disabled'))
                <a href="{{ route('admin.product.index') }}" class="mb-2 mb-md-0 btn btn-link">
                    <i class="fas fa-toggle-on"></i> Activats
                </a>
            @else
                <a href="{{ route('admin.product.index') }}?disabled=1" class="mb-2 mb-md-0 btn btn-link">
                    <i class="fas fa-toggle-off"></i> Desactivats
                </a>
            @endif
            <a href="{{ route('admin.product.create') }}" class="mb-2 mb-md-0 btn btn-outline-primary"><i
                    class="fas fa-plus"></i> Nou producte</a>
        </div>
    </div>

    @if (request()->query('disabled'))
        <div class="alert alert-warning">
            Estàs visualitzant els productes desactivats. <a href="{{ route('admin.product.index') }}">Torna als productes
                activats</a>
        </div>
    @endif

    <table class="table table-hover products-sortable">

        <tbody>

            @foreach ($products as $product)
                <tr data-id="{{ $product->id }}">
                    <td width="100">
                        @if (file_exists('images/' . $product->name . '.jpg'))
                            <a href="{{ route('admin.product.edit', $product->id) }}"><img class="img-fluid border-rounded"
                                    src="{{ url('img/th/' . $product->name) }}.jpg" style="width: 80px;"></a>
                        @endif
                    </td>
                    <td class="align-top">
                        <a href="{{ route('admin.product.edit', $product->id) }}">
                            <strong>{{ $product->title }}</strong> | {{ $product->organizer->username ?? '' }}
                        </a><br>
                        {{ $product->category->title }}
                        <div class="mt-4">
                            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn-link mr-3"><i
                                    class="fa-solid fa-circle-info"></i> Informació</a>
                            @if (!$product->is_pack)
                                <a href="{{ route('admin.ticket.index', $product->parent_id ?? $product->id) }}"
                                    class="btn-link mr-3"><i class="fa-sharp fa-solid fa-ticket"></i> Entrades</a>
                            @endif
                            <a href="{{ route('admin.price.index', $product->id) }}" class="btn-link mr-3"><i
                                    class="fa-solid fa-tag"></i> Preus</a>
                            @if ($product->is_pack)
                                <a href="{{ route('admin.pack.index', $product->id) }}" class="btn-link mr-3"><i
                                        class="fa-solid fa-seal"></i> Productes del pack</a>
                            @endif
                        </div>
                    </td>

                    <td class="text-right">
                        @if ($product->active)
                            <a href="{{ route('admin.product.active', $product->id) }}" class="btn btn-success btn-sm"
                                title="Activar / desactivar"><i class="fas fa-toggle-on"></i></a>
                        @else
                            <a href="{{ route('admin.product.active', $product->id) }}"
                                class="btn btn-outline-success btn-sm" title="Activar / desactivar"><i
                                    class="fas fa-toggle-off"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach

            </tr>

        </tbody>

    </table>


@stop
