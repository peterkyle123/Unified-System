<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Procurement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'procurement'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create(Procurement $procurement)
    {
        return view('purchase-orders.create', compact('procurement'));
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'procurement', 'preparedBy', 'items']);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'Draft') {
            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'Only drafts can be edited.');
        }

        return view('purchase-orders.edit', compact('purchaseOrder'));
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'Draft') {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Only drafts can be deleted.');
        }

        $purchaseOrder->delete();
        ActivityLog::log('purchase_order.deleted', $purchaseOrder);

        return redirect()->route('purchase-orders.index')
            ->with('message', "PO {$purchaseOrder->po_number} deleted successfully.");
    }

    public function print(PurchaseOrder $purchaseOrder)
    {
        return view('purchase-orders.print', compact('purchaseOrder'));
    }
}