@extends('layouts.admin')

@section('main-content')
    @livewire('product.edit', ['product' => $product])
@endsection
