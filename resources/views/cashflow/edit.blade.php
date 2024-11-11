@extends('layouts.admin')

@section('main-content')
    @livewire('cashflow.edit', ['cashflow' => $cashflow])
@endsection
