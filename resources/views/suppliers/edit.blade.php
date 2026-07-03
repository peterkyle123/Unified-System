@extends('layouts.app')

@section('content')
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Edit Supplier</h1>
                <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">{{ $supplier->name }}</p>
            </div>
            <a href="{{ route('suppliers.index') }}"
               class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 hover:text-gray-900 dark:hover:text-[var(--text-1)] prime:hover:text-gray-900 border border-gray-200 dark:border-[var(--border)] prime:border-green-900 px-4 py-2 rounded-lg transition">
                ← Back to suppliers
            </a>
        </div>

        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                               class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                               class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                        @error('contact_person') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">TIN</label>
                        <input type="text" name="tin" value="{{ old('tin', $supplier->tin) }}"
                               class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                        @error('tin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $supplier->contact_email) }}"
                               class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                        @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Contact Phone</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $supplier->contact_phone) }}"
                               class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                        @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Address</label>
                        <textarea name="address" rows="3"
                                  class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">{{ old('address', $supplier->address) }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button type="button" onclick="document.getElementById('delete-supplier-form').submit()"
                        class="px-4 py-2 rounded-lg border border-red-200 dark:border-red-900 prime:border-red-200 text-red-600 dark:text-red-400 prime:text-red-700 hover:bg-red-50 dark:hover:bg-red-950 prime:hover:bg-red-50 transition text-sm font-medium">
                    Delete Supplier
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('suppliers.index') }}"
                       class="px-4 py-2 rounded-lg border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition text-sm">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm font-medium">
                        Update Supplier
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-supplier-form" action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
              onsubmit="return confirm('Delete this supplier? This cannot be undone.');" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection
