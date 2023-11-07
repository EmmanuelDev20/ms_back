@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>MS Ingeniería</h1>
@stop

@section('content')
    <p>Bienvenido al panel administrativo.</p>

    <form action="{{ route('hookNetlify') }}" method="POST">
        @csrf
        <button class="btn btn-info" type="submit">Regenerar front</button>
    </form>

@stop
