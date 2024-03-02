@extends('layouts.admin')

@section('content')

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>Usuari <small>{{ $user->username }}</small></h1>
    </div>

    <div class="row">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <h3 class="my-0">Informació de l'usuari</h3>
                </div>
                <div class="card-body">

                    {{ Form::model($user, ['route' => ['admin.user.update', $user->id], 'class' => 'form-horizontal']) }}

                    <div class="form-group">
                        {{ Form::label('username', 'Nom d\'usuari', ['class' => 'control-label']) }}
                        {{ Form::text('username', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('email', 'Email', ['class' => 'control-label']) }}
                        {{ Form::text('email', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            {{ Form::label('password', 'Nova contrasenya') }}
                            {{ Form::password('password', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('password_confirmation', 'Repeteix la contrasenya') }}
                            {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            {{ Form::radio('role', 'admin', $user->hasRole('admin'), ['id' => 'checkAdmin', 'class' => 'custom-control-input']) }}
                            <label class="custom-control-label" for="checkAdmin">Administrador</label>
                        </div>
                        <div class="custom-control custom-radio">
                            {{ Form::radio('role', 'entity', $user->hasRole('entitat'), ['id' => 'checkEntitat', 'class' => 'custom-control-input']) }}
                            <label class="custom-control-label" for="checkEntitat">Organitzador</label>
                        </div>
                        <div class="custom-control custom-radio">
                            {{ Form::radio('role', 'validator', $user->hasRole('validator'), ['id' => 'checkValidator', 'class' => 'custom-control-input']) }}
                            <label class="custom-control-label" for="checkValidator">Validador QR</label>
                        </div>
                    </div>

                    @if ($user->hasRole('entitat'))
                        <div class="form-group">
                            <label for="condicions">Condicions de l'organitzador</label>
                            {{ Form::textarea('condicions', null, [
                                'class' => 'form-control raw',
                                'rows' => 4,
                            ]) }}
                        </div>
                    @endif

                    <div class="form-group">
                        {{ Form::submit('Edita usuari', ['class' => 'btn btn-primary']) }}
                    </div>

                    {{ Form::close() }}

                </div>
            </div>

        </div>
        <div class="col-md-4">

            @if ($user->hasRole('entity'))

                <div class="card">

                    <div class="card-header">
                        <h3 class="my-0">Productes</h3>
                    </div>

                    <div class="card-body">
                        L'usuari pot controlar les vendes dels següents productes:
                    </div>

                    @if ($user->productes->count() > 0)

                        <ul class="list-group list-group-flush small">

                            @foreach ($user->productes as $product)
                                <li class="list-group-item"><a
                                        href="{{ route('admin.product.edit', $product->id) }}">{{ $product->title_ca }}</a>
                                </li>
                            @endforeach

                        </ul>

                    @endif

                </div>

            @endif

        </div>
    </div>

@stop
