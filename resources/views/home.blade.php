@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-10">
            <h3 style="font-weight:600; font-size:35px">Welcome, {{ auth()->user()->name }}</h3>
        </div>
    </div>
@endsection
