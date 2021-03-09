@extends('admin._layout')

@section('title','Dashboard')

@section('description','')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">

            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
