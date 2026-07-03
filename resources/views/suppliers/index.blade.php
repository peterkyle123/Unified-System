@extends('layouts.app')

@section('content')
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Suppliers</h1>
                <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">Manage supplier records used on purchase orders</p>
            </div>
            <a href="{{ route('suppliers.create') }}"
               class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 dark:bg-[var(--accent)] dark:hover:bg-[var(--accent-h)] prime:bg-green-600 prime:hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                + Add Supplier
            </a>
        </div>

        @if (session('message'))
            <div class="mb-4 p-3 bg-green-50 dark:bg-green-950 prime:bg-green-50 border border-green-200 dark:border-green-800 prime:border-green-200 rounded-lg">
                <p class="text-sm text-green-800 dark:text-green-300 prime:text-green-800">{{ session('message') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6">
            @if($suppliers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-[var(--border)] prime:border-green-900">
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Name</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Contact Person</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Email</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Phone</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">TIN</th>
                                <th class="py-2 px-2 w-24"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr class="border-b border-gray-100 dark:border-[var(--border)] prime:border-green-100">
                                    <td class="py-2 px-2 font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $supplier->name }}</td>
                                    <td class="py-2 px-2">{{ $supplier->contact_person ?? '-' }}</td>
                                    <td class="py-2 px-2">{{ $supplier->contact_email ?? '-' }}</td>
                                    <td class="py-2 px-2">{{ $supplier->contact_phone ?? '-' }}</td>
                                    <td class="py-2 px-2">{{ $supplier->tin ?? '-' }}</td>
                                    <td class="py-2 px-2 text-right">
                                        <a href="{{ route('suppliers.edit', $supplier) }}"
                                           class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 hover:text-gray-900 dark:hover:text-[var(--text-1)] prime:hover:text-gray-900 transition">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($suppliers->hasPages())
                        <div class="flex items-center justify-between mt-4 px-2">
                            <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">
                                Showing {{ $suppliers->firstItem() }} - {{ $suppliers->lastItem() }} of {{ $suppliers->total() }}
                            </p>
                            <div class="flex items-center gap-2">
                                @if($suppliers->onFirstPage())
                                    <span class="text-sm px-3 py-1.5 rounded border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-400 opacity-40 cursor-not-allowed">← Prev</span>
                                @else
                                    <a href="{{ $suppliers->previousPageUrl() }}" class="text-sm px-3 py-1.5 rounded border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-600 dark:text-[var(--text-2)] prime:text-gray-600 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">← Prev</a>
                                @endif

                                @if($suppliers->hasMorePages())
                                    <a href="{{ $suppliers->nextPageUrl() }}" class="text-sm px-3 py-1.5 rounded border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-600 dark:text-[var(--text-2)] prime:text-gray-600 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">Next →</a>
                                @else
                                    <span class="text-sm px-3 py-1.5 rounded border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-400 opacity-40 cursor-not-allowed">Next →</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 text-center py-8">No suppliers yet.</p>
            @endif
        </div>
    </div>
@endsection
