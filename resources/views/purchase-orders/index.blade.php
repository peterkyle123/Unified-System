@extends('layouts.app')

@section('content')
    <div>
        {{-- Page header --}}
        <div class="flex items-center justify-between mb-6">
        <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">For Quotation</h1>
                <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">All purchase orders generated from procurements</p>
            </div>
        </div>

        @if (session('message'))
            <div class="mb-4 p-3 bg-green-50 dark:bg-green-950 prime:bg-green-50 border border-green-200 dark:border-green-800 prime:border-green-200 rounded-lg">
                <p class="text-sm text-green-800 dark:text-green-300 prime:text-green-800">{{ session('message') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-50 dark:bg-red-950 prime:bg-red-50 border border-red-200 dark:border-red-800 prime:border-red-200 rounded-lg">
                <p class="text-sm text-red-800 dark:text-red-300 prime:text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        @php
            $statusColors = [
                'Draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 prime:bg-gray-100 prime:text-gray-800',
                'Submitted' => 'bg-blue-50 text-blue-800 dark:bg-blue-950 dark:text-blue-300 prime:bg-blue-50 prime:text-blue-800',
                'Ordered' => 'bg-purple-50 text-purple-800 dark:bg-purple-950 dark:text-purple-300 prime:bg-purple-50 prime:text-purple-800',
                'Received' => 'bg-green-50 text-green-800 dark:bg-green-950 dark:text-green-300 prime:bg-green-50 prime:text-green-800',
                'Cancelled' => 'bg-red-50 text-red-800 dark:bg-red-950 dark:text-red-400 prime:bg-red-50 prime:text-red-700',
            ];
        @endphp

        <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6">
            @if($purchaseOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-[var(--border)] prime:border-green-900">
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">PO Number</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Procurement</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Supplier</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Date Prepared</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Total</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrders as $po)
                                <tr class="border-b border-gray-100 dark:border-[var(--border)] prime:border-green-100 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 cursor-pointer transition"
                                    onclick="window.location='{{ route('purchase-orders.show', $po) }}'">
                                    <td class="py-2 px-2 font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $po->po_number }}</td>
                                    <td class="py-2 px-2">{{ $po->procurement?->procurement_number ?? 'N/A' }}</td>
                                    <td class="py-2 px-2">{{ $po->supplier?->name ?? 'N/A' }}</td>
                                    <td class="py-2 px-2">{{ $po->date_prepared->format('M d, Y') }}</td>
                                    <td class="py-2 px-2 font-mono">₱ {{ number_format($po->total_amount ?? 0, 2) }}</td>
                                    <td class="py-2 px-2">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$po->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $po->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($purchaseOrders->hasPages())
                        <div class="flex items-center justify-between mt-4 px-2">
                            <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">
                                Showing {{ $purchaseOrders->firstItem() }} - {{ $purchaseOrders->lastItem() }} of {{ $purchaseOrders->total() }}
                            </p>
                            <div class="flex items-center gap-2">
                                @if($purchaseOrders->onFirstPage())
                                    <span class="text-sm px-3 py-1.5 rounded border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-400 opacity-40 cursor-not-allowed">← Prev</span>
                                @else
                                    <a href="{{ $purchaseOrders->previousPageUrl() }}" class="text-sm px-3 py-1.5 rounded border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-600 dark:text-[var(--text-2)] prime:text-gray-600 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">← Prev</a>
                                @endif

                                @if($purchaseOrders->hasMorePages())
                                    <a href="{{ $purchaseOrders->nextPageUrl() }}" class="text-sm px-3 py-1.5 rounded border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-600 dark:text-[var(--text-2)] prime:text-gray-600 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">Next →</a>
                                @else
                                    <span class="text-sm px-3 py-1.5 rounded border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-400 opacity-40 cursor-not-allowed">Next →</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 text-center py-8">No purchase orders yet. Create one from a procurement's detail page.</p>
            @endif
        </div>
    </div>
@endsection
