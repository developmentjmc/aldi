@extends('backend/layouts/main', get_defined_vars())

@section('content')
    Selamat Datang {{ auth()->user()->name }} - {{ auth()->user()->role->name }}
@endsection
