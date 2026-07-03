@extends('layouts.app')

@section('content')
    @livewire('purchase-order-form', ['purchaseOrderId' => $purchaseOrder->id])
@endsection