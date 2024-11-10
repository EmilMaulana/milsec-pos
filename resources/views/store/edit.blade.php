@extends('layouts.admin')

@section('main-content')
    @livewire('store.edit', ['store' => $store])
@endsection
