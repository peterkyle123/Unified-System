@extends('layouts.app')

@section('content')
    <div>
        {{-- Page header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Purchase Order Details</h1>
                <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">{{ $purchaseOrder->po_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('purchase-orders.print', $purchaseOrder) }}"
                   target="_blank"
                   class="text-sm px-4 py-2 rounded-lg border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                    🖨 Print
                </a>
                <a href="{{ route('purchase-orders.index') }}"
                   class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 hover:text-gray-900 dark:hover:text-[var(--text-1)] prime:hover:text-gray-900 border border-gray-200 dark:border-[var(--border)] prime:border-green-900 px-4 py-2 rounded-lg transition">
                    ← Back
                </a>
            </div>
        </div>

        {{-- Status --}}
        <div class="mb-6">
            @php
                $statusColors = [
                    'Draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 prime:bg-gray-100 prime:text-gray-800',
                    'Submitted' => 'bg-blue-50 text-blue-800 dark:bg-blue-950 dark:text-blue-300 prime:bg-blue-50 prime:text-blue-800',
                    'Ordered' => 'bg-purple-50 text-purple-800 dark:bg-purple-950 dark:text-purple-300 prime:bg-purple-50 prime:text-purple-800',
                    'Received' => 'bg-green-50 text-green-800 dark:bg-green-950 dark:text-green-300 prime:bg-green-50 prime:text-green-800',
                    'Cancelled' => 'bg-red-50 text-red-800 dark:bg-red-950 dark:text-red-400 prime:bg-red-50 prime:text-red-700',
                ];
            @endphp
            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusColors[$purchaseOrder->status] ?? 'bg-gray-100 text-gray-600' }}">
                {{ $purchaseOrder->status }}
            </span>
        </div>

        {{-- PO Info --}}
        <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6 mb-6">
            <p class="text-xs font-medium text-gray-400 dark:text-[var(--accent)] prime:text-green-700 uppercase tracking-wide mb-4">Purchase Order Information</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">PO Number</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $purchaseOrder->po_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Procurement</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">
                        @if($purchaseOrder->procurement)
                            <a href="{{ route('procurements.show', $purchaseOrder->procurement) }}" class="hover:underline">{{ $purchaseOrder->procurement->procurement_number }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Supplier</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $purchaseOrder->supplier?->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Prepared By</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $purchaseOrder->preparedBy?->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Date Prepared</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $purchaseOrder->date_prepared->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Delivery Deadline</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $purchaseOrder->delivery_deadline?->format('F d, Y') ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Total Amount</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">₱ {{ number_format($purchaseOrder->total_amount ?? 0, 2) }}</p>
                </div>
                @if($purchaseOrder->notes)
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Notes</p>
                        <p class="text-sm text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $purchaseOrder->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Items --}}
        <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6">
            @php
                $itemStatusColors = [
                    'Pending' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 prime:bg-gray-100 prime:text-gray-800',
                    'Ordered' => 'bg-blue-50 text-blue-800 dark:bg-blue-950 dark:text-blue-300 prime:bg-blue-50 prime:text-blue-800',
                    'Delivered' => 'bg-purple-50 text-purple-800 dark:bg-purple-950 dark:text-purple-300 prime:bg-purple-50 prime:text-purple-800',
                    'Received' => 'bg-green-50 text-green-800 dark:bg-green-950 dark:text-green-300 prime:bg-green-50 prime:text-green-800',
                    'Cancelled' => 'bg-red-50 text-red-800 dark:bg-red-950 dark:text-red-400 prime:bg-red-50 prime:text-red-700',
                ];
            @endphp

            <p class="text-xs font-medium text-gray-400 dark:text-[var(--accent)] prime:text-green-700 uppercase tracking-wide mb-4">Items ({{ $purchaseOrder->items->count() }})</p>

            @if($purchaseOrder->items->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-[var(--border)] prime:border-green-900">
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Description</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Unit</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Qty</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Unit Price</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Total</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrder->items as $item)
                                <tr class="border-b border-gray-100 dark:border-[var(--border)] prime:border-green-100">
                                    <td class="py-2 px-2">{{ $item->item_description }}</td>
                                    <td class="py-2 px-2">{{ $item->unit }}</td>
                                    <td class="py-2 px-2 text-right">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="py-2 px-2 text-right">{{ $item->unit_price ? '₱ ' . number_format($item->unit_price, 2) : '-' }}</td>
                                    <td class="py-2 px-2 text-right font-mono">₱ {{ number_format($item->total_price, 2) }}</td>
                                    <td class="py-2 px-2">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $itemStatusColors[$item->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-200 dark:border-[var(--border)] prime:border-green-900">
                                <td colspan="4" class="py-3 px-2 text-right font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Total Amount:</td>
                                <td class="py-3 px-2 text-right font-mono font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">
                                    ₱ {{ number_format($purchaseOrder->total_amount ?? 0, 2) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 text-center py-8">No items in this purchase order.</p>
            @endif
        </div>

        @if($purchaseOrder->status === 'Draft')
            <div class="mt-6 flex items-center gap-3">
                <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}"
                   class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm font-medium">
                    Edit Purchase Order
                </a>
                <form action="{{ route('purchase-orders.destroy', $purchaseOrder) }}" method="POST"
                      onsubmit="return confirm('Delete this purchase order? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 rounded-lg border border-red-200 dark:border-red-900 prime:border-red-200 text-red-600 dark:text-red-400 prime:text-red-700 hover:bg-red-50 dark:hover:bg-red-950 prime:hover:bg-red-50 transition text-sm font-medium">
                        Delete
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
