@extends('layouts.app')

@section('content')
    @livewire('purchase-order-form', ['procurement_id' => $procurement->id])
@endsection
