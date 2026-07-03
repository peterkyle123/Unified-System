<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'procurement_id',
        'supplier_id',
        'prepared_by',
        'date_prepared',
        'delivery_deadline',
        'status',
        'notes',
        'total_amount',
    ];

    protected $casts = [
        'date_prepared' => 'date',
        'delivery_deadline' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function procurement(): BelongsTo
    {
        return $this->belongsTo(Procurement::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public static function generateNumber(): string
    {
        $year = now()->year;
        $last = static::whereYear('created_at', $year)
            ->orderByRaw('CAST(SUBSTRING_INDEX(po_number, "-", -1) AS UNSIGNED) DESC')
            ->value('po_number');

        $next = $last ? (int) explode('-', $last)[2] + 1 : 1;

        return sprintf('PO-%d-%03d', $year, $next);
    }
}