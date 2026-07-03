<?php

namespace App\Livewire;

use App\Models\Procurement;
use App\Models\ProcurementItem;
use App\Models\Agency;
use App\Models\Rfq;
use App\Models\RfqItem;
use App\Models\ActivityLog;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class ProcurementForm extends Component
{
    public string $procurement_number = '';
    public string $date_prepared = '';
    public string $delivery_deadline = '';
    public string $status = 'Draft';
    public string $notes = '';
    public string $prepared_by = '';

    public array $items = [
        [
            'agency_id' => '',
            'rfq_item_id' => '',
            'brand' => '',
            'item_description' => '',
            'unit' => '',
            'quantity' => '',
            'unit_price' => '',
            'status' => 'Pending',
            'notes' => '',
        ],
    ];

    public string $rfqSearch = '';
    public ?int $selectedRfqId = null;
    public bool $showRfqPicker = false;
    public bool $showDuplicateWarning = false;
    public bool $addDuplicateMode = false;
    public ?int $procurementId = null;
    public string $itemSortBy = 'rfq_item_id';
    public string $itemSortDirection = 'asc';
    public array $itemOrder = [];
    public int $itemsPerPage = 10;
    public int $currentPage = 1;

    public function mount(?int $procurementId = null): void
    {
        if ($procurementId) {
            $this->procurementId = $procurementId;
            $procurement = Procurement::with('items.agency')->findOrFail($procurementId);

            $this->procurement_number = $procurement->procurement_number;
            $this->date_prepared = $procurement->date_prepared->format('Y-m-d');
            $this->delivery_deadline = $procurement->delivery_deadline ? $procurement->delivery_deadline->format('Y-m-d') : '';
            $this->status = $procurement->status;
            $this->notes = $procurement->notes ?? '';
            $this->prepared_by = $procurement->preparedBy?->name ?? '';

            $this->items = array_values($procurement->items->map(fn($i) => [
                'agency_id' => (string) ($i->agency_id ?? ''),
                'rfq_item_id' => (string) ($i->rfq_item_id ?? ''),
                'brand' => $i->brand ?? '',
                'item_description' => $i->item_description,
                'unit' => $i->unit,
                'quantity' => (string) $i->quantity,
                'unit_price' => (string) ($i->unit_price ?? ''),
                'status' => $i->status,
                'notes' => $i->notes ?? '',
            ])->toArray());
        } else {
            $this->date_prepared = now()->format('Y-m-d');
            $this->procurement_number = Procurement::generateNumber();
            $this->prepared_by = auth()->user()?->name ?? '';
        }
        
        $this->resetItemOrder();
    }
    
    protected function resetItemOrder(): void
    {
        $this->itemOrder = array_keys($this->items);
        $this->currentPage = 1;
    }

    public function toggleRfqPicker(): void
    {
        $this->showRfqPicker = !$this->showRfqPicker;
        if (!$this->showRfqPicker) {
            $this->selectedRfqId = null;
            $this->rfqSearch = '';
        }
    }

    public function selectRfq(int $rfqId): void
    {
        $this->selectedRfqId = $rfqId;
        $this->showDuplicateWarning = false;
        $this->rfqSearch = '';

        $rfq = Rfq::with('items')->find($rfqId);
        if ($rfq) {
            $alreadyAllAdded = $rfq->items->every(fn($rfqItem) =>
                collect($this->items)->contains(fn($item) =>
                    $item['rfq_item_id'] == $rfqItem->id && $item['agency_id'] == $rfq->agency_id
                )
            );
            if ($alreadyAllAdded && $rfq->items->isNotEmpty()) {
                $this->showDuplicateWarning = true;
            }
        }
    }

    public function confirmAddRfq(): void
    {
        $this->showDuplicateWarning = false;
        $this->addDuplicateMode = true;
        $this->addRfqItems();
        $this->addDuplicateMode = false;
    }

    public function cancelAddRfq(): void
    {
        $this->showDuplicateWarning = false;
        $this->selectedRfqId = null;
    }

    public function addRfqItems(): void
    {
        if (!$this->selectedRfqId) {
            return;
        }

        // Remove empty placeholder rows before adding RFQ items
        $this->items = array_values(array_filter($this->items, function ($item) {
            return !(trim($item['item_description'] ?? '') === ''
                && trim($item['unit'] ?? '') === ''
                && trim($item['quantity'] ?? '') === '');
        }));

        $rfq = Rfq::with('items')->findOrFail($this->selectedRfqId);

        foreach ($rfq->items as $rfqItem) {
            $existingIndex = collect($this->items)->search(fn($item) =>
                $item['rfq_item_id'] == $rfqItem->id && $item['agency_id'] == $rfq->agency_id
            );

            if ($existingIndex !== false) {
                if ($this->addDuplicateMode) {
                    $this->items[$existingIndex]['quantity'] = (string) ((float) ($this->items[$existingIndex]['quantity'] ?? 0) + (float) $rfqItem->quantity);
                    $this->resetItemOrder();
                }
            } else {
                $this->items[] = [
                    'agency_id' => (string) $rfq->agency_id,
                    'rfq_item_id' => (string) $rfqItem->id,
                    'brand' => $rfqItem->brand ?? '',
                    'item_description' => $rfqItem->item_description,
                    'unit' => $rfqItem->unit,
                    'quantity' => (string) $rfqItem->quantity,
                    'unit_price' => (string) ($rfqItem->unit_price ?? ''),
                    'status' => 'Pending',
                    'notes' => '',
                ];
            }
        }

        $this->selectedRfqId = null;
        $this->showRfqPicker = false;
        $this->rfqSearch = '';
        $this->resetItemOrder();
        $this->dispatch('item-added');
    }

    public function clearItems(): void
    {
        $this->items = [
            [
                'agency_id' => '',
                'rfq_item_id' => '',
                'brand' => '',
                'item_description' => '',
                'unit' => '',
                'quantity' => '',
                'unit_price' => '',
                'status' => 'Pending',
                'notes' => '',
            ],
        ];
        $this->resetItemOrder();
    }

    public function addItem(): void
    {
        $hasEmpty = collect($this->items)->some(
            fn($item) => trim($item['item_description'] ?? '') === '' ||
                         trim($item['unit'] ?? '') === '' ||
                         trim($item['quantity'] ?? '') === ''
        );

        if ($hasEmpty) {
            session()->flash('error', 'Please fill in all existing item fields before adding a new one.');
            return;
        }

        $this->items[] = [
            'agency_id' => '',
            'rfq_item_id' => '',
            'brand' => '',
            'item_description' => '',
            'unit' => '',
            'quantity' => '',
            'unit_price' => '',
            'status' => 'Pending',
            'notes' => '',
        ];
        $this->resetItemOrder();
    }

    public function removeItem(int $index): void
    {
        // Update itemOrder to remove the index
        $this->itemOrder = array_values(array_filter($this->itemOrder, fn($i) => $i !== $index));
        
        // Re-index remaining indices in itemOrder that were greater than the removed index
        $this->itemOrder = array_map(fn($i) => $i > $index ? $i - 1 : $i, $this->itemOrder);
        
        array_splice($this->items, $index, 1);

        if (empty($this->items)) {
            $this->items = [
                [
                    'agency_id' => '',
                    'rfq_item_id' => '',
                    'brand' => '',
                    'item_description' => '',
                    'unit' => '',
                    'quantity' => '',
                    'unit_price' => '',
                    'status' => 'Pending',
                    'notes' => '',
                ],
            ];
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'date_prepared' => 'required|date',
            'delivery_deadline' => 'nullable|date|after_or_equal:date_prepared',
            'status' => 'required|in:Draft,Submitted,Approved,Ordered,Delivered,Cancelled',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['delivery_deadline'])) {
            $validated['delivery_deadline'] = null;
        }

        $validated['total_amount'] = collect($this->items)->sum(fn($item) => (float) $item['unit_price'] * (float) $item['quantity']);

        if ($this->procurementId) {
            $procurement = Procurement::findOrFail($this->procurementId);
            $procurement->update($validated);
            $oldStatus = $procurement->getOriginal('status');
            $oldStatus !== $procurement->status
                ? ActivityLog::log('procurement.status_changed', $procurement, ['status' => $oldStatus], ['status' => $procurement->status])
                : ActivityLog::log('procurement.updated', $procurement);
        } else {
            $validated['procurement_number'] = Procurement::generateNumber();
            $validated['prepared_by'] = auth()->id();
            $procurement = Procurement::create($validated);
            ActivityLog::log('procurement.created', $procurement);
        }

        $procurement->items()->delete();

        foreach ($this->items as $itemData) {
            if (trim($itemData['item_description'] ?? '') === '' || trim($itemData['unit'] ?? '') === '' || trim($itemData['quantity'] ?? '') === '') {
                continue;
            }

            $procurement->items()->create([
                'agency_id' => $itemData['agency_id'] ?: null,
                'rfq_item_id' => $itemData['rfq_item_id'] ?: null,
                'brand' => $itemData['brand'] ?? '',
                'item_description' => $itemData['item_description'],
                'unit' => $itemData['unit'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'] ?: null,
                'total_price' => ($itemData['unit_price'] ?: 0) * ($itemData['quantity'] ?: 0),
                'status' => $itemData['status'],
                'notes' => $itemData['notes'] ?? '',
            ]);
        }

        session()->flash('message', $this->procurementId
            ? "Procurement {$procurement->procurement_number} updated successfully."
            : "Procurement {$procurement->procurement_number} created successfully."
        );

        $this->redirect(route('procurements.show', $procurement));
    }

    public function getAwardedRfqsProperty()
    {
        return Rfq::whereIn('status', ['Awarded', 'Quoted'])
            ->when($this->rfqSearch, fn($q, $search) => $q->where('rfq_number', 'like', "%{$search}%")
                ->orWhereHas('agency', fn($q) => $q->where('name', 'like', "%{$search}%")))
            ->orderBy('rfq_number')
            ->get();
    }

    public function goToPage(int $page): void
    {
        $this->currentPage = max(1, min($page, $this->totalPages));
    }

    public function nextPage(): void
    {
        $this->goToPage($this->currentPage + 1);
    }

    public function prevPage(): void
    {
        $this->goToPage($this->currentPage - 1);
    }

    public function getTotalPagesProperty(): int
    {
        if ($this->itemsPerPage <= 0) {
            return 1;
        }
        return max(1, (int) ceil(count($this->itemOrder) / $this->itemsPerPage));
    }

    public function getPaginatedOrderProperty(): array
    {
        if ($this->itemsPerPage <= 0) {
            return $this->itemOrder;
        }
        return array_slice($this->itemOrder, ($this->currentPage - 1) * $this->itemsPerPage, $this->itemsPerPage);
    }

    public function sortBy(string $field): void
    {
        if ($this->itemSortBy === $field) {
            $this->itemSortDirection = $this->itemSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->itemSortBy = $field;
            $this->itemSortDirection = 'asc';
        }
        
        $this->sortItems();
    }
    
    protected function sortItems(): void
    {
        $sortBy = $this->itemSortBy;
        $sortDirection = $this->itemSortDirection;
        $indices = $this->itemOrder;
        
        usort($indices, function ($a, $b) use ($sortBy, $sortDirection) {
            $valueA = (string) ($this->items[$a][$sortBy] ?? '');
            $valueB = (string) ($this->items[$b][$sortBy] ?? '');
            
            if ($sortBy === 'quantity' || $sortBy === 'unit_price') {
                $numA = (float) ($valueA ?: 0);
                $numB = (float) ($valueB ?: 0);
                return $sortDirection === 'asc' ? $numA <=> $numB : $numB <=> $numA;
            }
            
            $cmp = strcmp(strtolower($valueA), strtolower($valueB));
            return $sortDirection === 'asc' ? $cmp : -$cmp;
        });
        
        $this->itemOrder = $indices;
    }

    public function render()
    {
        $selectedRfq = $this->selectedRfqId
            ? Rfq::with('items')->find($this->selectedRfqId)
            : null;

        return view('livewire.procurement-form', [
            'agencies' => Agency::orderBy('name')->get(),
            'awardedRfqs' => $this->awardedRfqs,
            'selectedRfq' => $selectedRfq,
            'itemOrder' => $this->itemOrder,
            'paginatedOrder' => $this->paginatedOrder,
            'totalPages' => $this->totalPages,
        ]);
    }
}