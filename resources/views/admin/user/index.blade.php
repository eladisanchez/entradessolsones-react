@extends('layouts.admin')

@section('content')

    <h1>Usuaris</h1>

    <table class="table table-striped table-condensed mb-4">

        <thead class="thead-dark">
            <tr>
                <th>Usuari</th>
                <th>Email</th>
                <th>Rol</th>
                <th style="width:20%">&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $usuari)
                <tr>
                    <th>
                        {{ $usuari->username }}
                    </th>
                    <td>
                        {{ $usuari->email }}
                    </td>
                    <td>
                        @if ($usuari->hasRole('admin'))
                            Administrador
                        @endif
                        @if ($usuari->hasRole('entitat'))
                            Organitzador
                        @endif
                        @if ($usuari->hasRole('validator'))
                            Validador QR
                        @endif
                    </td>
                    <td class="text-right">

                        <a href="{{ route('admin.user.edit', $usuari->id) }}" class="btn btn-primary btn-xs">Edita</a>
                        {{ Form::model($usuari, ['route' => ['admin.user.destroy', $usuari->id], 'method' => 'delete', 'style' => 'display:inline-block']) }}
                        {{ Form::submit('Elimina', ['class' => 'btn btn-danger btn-xs']) }}
                        {{ Form::close() }}
                        <a href="{{ route('loginas', ['id' => $usuari->id]) }}" class="btn btn-outline-primary btn-xs"><i
                                class="fa-solid fa-right-to-bracket"></i></a>

                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {{-- <p><a href="{{route('clients')}}" class="btn btn-primary">Clients</a></p> --}}

    <hr>

    <div class="card">
        <div class="card-header">
            Crea usuari
        </div>
        <div class="card-body">

            {{ Form::open(['route' => 'admin.user.store', 'class' => 'row']) }}

            <div class="form-group col-md-4">
                {{ Form::label('username', 'Nom usuari', ['class' => 'control-label']) }}
                {{ Form::text('username', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-4">
                {{ Form::label('password', 'Contrasenya', ['class' => 'control-label']) }}
                {{ Form::password('password', ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-4">
                {{ Form::label('email', 'Email', ['class' => 'control-label']) }}
                {{ Form::text('email', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-12">
                <div class="custom-control custom-radio">
                    {{ Form::radio('role', 'admin', null, ['id' => 'checkAdmin', 'class' => 'custom-control-input']) }}
                    <label class="custom-control-label" for="checkAdmin">Administrador</label>
                </div>
                <div class="custom-control custom-radio">
                    {{ Form::radio('role', 'entitat', null, ['id' => 'checkEntitat', 'class' => 'custom-control-input']) }}
                    <label class="custom-control-label" for="checkEntitat">Organitzador</label>
                </div>
                <div class="custom-control custom-radio">
                    {{ Form::radio('role', 'validator', 1, ['id' => 'checkValidator', 'class' => 'custom-control-input']) }}
                    <label class="custom-control-label" for="checkValidator">Validador QR</label>
                </div>
            </div>

            <div class="form-group col-12">
                {{ Form::submit('Crea usuari', ['class' => 'btn btn-primary']) }}
            </div>

            {{ Form::close() }}

        </div>
    </div>


@stop
