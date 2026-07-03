<?php

namespace App\Livewire;

use App\Models\Procurement;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\ActivityLog;
use Livewire\Component;

class PurchaseOrderForm extends Component
{
    public string $po_number = '';
    public string $date_prepared = '';
    public string $delivery_deadline = '';
    public string $status = 'Draft';
    public string $notes = '';
    public ?int $supplier_id = null;
    public ?int $procurement_id = null;
    public array $items = [];
    public ?int $purchaseOrderId = null;

    public function mount(?int $purchaseOrderId = null, ?int $procurement_id = null): void
    {
        if ($purchaseOrderId) {
            $this->purchaseOrderId = $purchaseOrderId;
            $po = PurchaseOrder::with('items.supplier')->findOrFail($purchaseOrderId);
            $this->po_number = $po->po_number;
            $this->date_prepared = $po->date_prepared->format('Y-m-d');
            $this->delivery_deadline = $po->delivery_deadline ? $po->delivery_deadline->format('Y-m-d') : '';
            $this->status = $po->status;
            $this->notes = $po->notes ?? '';
            $this->supplier_id = $po->supplier_id;
            $this->procurement_id = $po->procurement_id;
            $this->items = $po->items->map(fn($i) => [
                'id' => $i->id,
                'supplier_id' => (string) $i->supplier_id,
                'procurement_item_id' => $i->procurement_item_id,
                'item_description' => $i->item_description,
                'unit' => $i->unit,
                'quantity' => (string) $i->quantity,
                'unit_price' => (string) ($i->unit_price ?? ''),
                'status' => $i->status,
                'notes' => $i->notes ?? '',
            ])->toArray();
        } else {
            $this->date_prepared = now()->format('Y-m-d');
            $this->po_number = PurchaseOrder::generateNumber();
            if ($procurement_id) {
                $this->procurement_id = $procurement_id;
                $procurement = Procurement::with('items')->find($procurement_id);
                if ($procurement) {
                    foreach ($procurement->items as $item) {
                        $this->items[] = [
                            'id' => null,
                            'supplier_id' => '',
                            'procurement_item_id' => $item->id,
                            'item_description' => $item->item_description,
                            'unit' => $item->unit,
                            'quantity' => (string) $item->quantity,
                            'unit_price' => (string) ($item->unit_price ?? ''),
                            'status' => 'Pending',
                            'notes' => '',
                        ];
                    }
                }
            }
        }
    }

    public function addItem(): void
    {
        $this->items[] = [
            'id' => null,
            'supplier_id' => '',
            'procurement_item_id' => null,
            'item_description' => '',
            'unit' => '',
            'quantity' => '',
            'unit_price' => '',
            'status' => 'Pending',
            'notes' => '',
        ];
    }

    public function removeItem(int $index): void
    {
        array_splice($this->items, $index, 1);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date_prepared' => 'required|date',
            'delivery_deadline' => 'nullable|date|after_or_equal:date_prepared',
            'status' => 'required|in:Draft,Submitted,Ordered,Received,Cancelled',
            'notes' => 'nullable|string',
        ]);
        $validated['total_amount'] = collect($this->items)
            ->filter(fn($item) => trim($item['item_description'] ?? '') !== '' && trim($item['unit'] ?? '') !== '' && trim($item['quantity'] ?? '') !== '')
            ->sum(fn($item) => (float) ($item['unit_price'] ?? 0) * (float) ($item['quantity']));
        if (empty($validated['delivery_deadline'])) $validated['delivery_deadline'] = null;
        if ($this->purchaseOrderId) {
            $po = PurchaseOrder::findOrFail($this->purchaseOrderId);
            $po->update($validated);
            ActivityLog::log('purchase_order.updated', $po);
     } else {
            $validated['po_number'] = $this->po_number;
            $validated['prepared_by'] = auth()->id();
            $validated['procurement_id'] = $this->procurement_id;
            $po = PurchaseOrder::create($validated)->fresh();
            ActivityLog::log('purchase_order.created', $po);
        }
        $po->items()->delete();
        foreach ($this->items as $itemData) {
            if (trim($itemData['item_description'] ?? '') === '' || trim($itemData['unit'] ?? '') === '' || trim($itemData['quantity'] ?? '') === '') continue;
            $po->items()->create([
                'procurement_item_id' => $itemData['procurement_item_id'] ?: null,
                'supplier_id' => !empty($itemData['supplier_id']) ? (int) $itemData['supplier_id'] : $po->supplier_id,
                'item_description' => $itemData['item_description'],
                'unit' => $itemData['unit'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'] ?: null,
                'total_price' => ($itemData['unit_price'] ?: 0) * ($itemData['quantity'] ?: 0),
                'status' => $itemData['status'],
                'notes' => $itemData['notes'] ?? '',
            ]);
        }
        session()->flash('message', $this->purchaseOrderId ? "PO {$po->po_number} updated." : "PO {$po->po_number} created.");
        $this->redirect(route('purchase-orders.show', $po));
    }

    public function render()
    {
        return view('livewire.purchase-order-form', ['suppliers' => Supplier::orderBy('name')->get()]);
    }
}