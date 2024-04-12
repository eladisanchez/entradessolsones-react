@extends('layouts.admin', ['menu' => 'sales'])

@section('content')

    @include('admin.menus.sales', ['active' => 'extracts'])

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>Extractes</h1>
    </div>

    @if (isset($extracts))


        <table class="table table-condensed table-bordered mb-0">

            <thead class="thead-light">
                <tr>
                    <th>Excel</th>
                    <th>Organitzador/Producte</th>
                    <th>Data inici</th>
                    <th>Data fi</th>
                    <th>Vendes</th>
                    <th>Total</th>
                    <th style="width: 110px;"></th>
                </tr>
            </thead>

            <tbody class="tab-content" id="pills-tabContent">
                @foreach ($extracts as $extract)
                    <tr>

                        <td width=10>
                            <a href="{{ route('admin.extract.excel', ['id' => $extract->id]) }}"
                                class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-file-excel"></i>
                            </a>
                        </td>

                        <td>
                            @if ($extract->product)
                                <i class="fas fa-ticket-alt mr-2"></i> {{ $extract->product->title }}
                            @else
                                <i class="fas fa-user mr-2"></i> {{ $extract->user->username ?? '' }}
                            @endif
                        </td>

                        <td>
                            {{ $extract->date_start->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ $extract->date_end->format('d/m/Y') }}
                        </td>

                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach ($extract->sales as $sale)
                                    <li>{{ $sale['product'] }}: <strong>{{ $sales['liquidar'] }} €</strong></li>
                                @endforeach
                            </ul>
                        </td>

                        <td>
                            <strong>{{ $extract->total_sales - $extract->totalRefunds }} €</strong>
                        </td>

                        <td class="text-right">

                            <a href="{{ route('admin.extract.paid', $extract->id) }}" class="btn btn-info mr-2">
                                @if ($extract->paid)
                                    <i class="fas fa-check"></i>
                                @else
                                    <i class="fas fa-clock"></i>
                                @endif
                            </a>

                            <a href="{{ route('admin.extract.destroy', $extract->id) }}" class="btn btn-danger"><i
                                    class="far fa-trash-alt"></i></a>

                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>

        <div class="mt-4">
            {{ $extracts->appends(request()->except('page'))->links() }}
        </div>

    @endif

    <div class="card mt-4">
        <div class="card-header bg-light">Nou extracte</div>
        <div class="card-body">

            {{ Form::open(['route' => 'admin.extract.store', 'role' => 'form']) }}

            <div class="row">

                <div class="form-group col-md-3">
                    {{ Form::label('user_id', 'Promotor') }}
                    {{ Form::select('user_id', $users, null, [
                        'class' => 'custom-select',
                    ]) }}
                </div>

                <div class="form-group col-md-3">
                    {{ Form::label('producte_id', 'Producte') }}
                    {{ Form::select('producte_id', $products, null, [
                        'class' => 'custom-select',
                    ]) }}
                </div>

                <div class="form-group col-md-2">
                    {{ Form::label('date_start', 'Des de') }}
                    {{ Form::date('date_start', null, [
                        'class' => 'date form-control',
                    ]) }}
                </div>

                <div class="form-group col-md-2">
                    {{ Form::label('date_end', 'Fins a') }}
                    {{ Form::date('date_end', null, [
                        'class' => 'date form-control',
                    ]) }}
                </div>

                <div class="col-md-2">
                    <label>&nbsp;</label>
                    {{ Form::button('<i class="fas fa-table mr-2"></i> Generar', [
                        'class' => 'btn btn-primary btn-block',
                        'type' => 'submit',
                    ]) }}
                </div>

            </div>

            {{ Form::close() }}

        </div>
    </div>

@stop
