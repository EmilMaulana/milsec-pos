@extends('layouts.admin')

@section('main-content')
    @livewire('staff.edit', ['user' => $user])
@endsection
