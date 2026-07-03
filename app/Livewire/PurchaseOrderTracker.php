<?php

namespace App\Livewire;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseOrderTracker extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'preparedBy', 'procurement'])
            ->when($this->search, fn($q, $search) => $q->where('po_number', 'like', "%{$search}%")
                ->orWhereHas('supplier', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('procurement', fn($q) => $q->where('procurement_number', 'like', "%{$search}%")))
            ->when($this->statusFilter, fn($q, $status) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.purchase-order-tracker', [
            'purchaseOrders' => $purchaseOrders,
        ]);
    }
}