<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - {{ $purchaseOrder->po_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; line-height: 1.5; color: #333; padding: 20px; }
        .container { max-width: 850px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 18px; border-bottom: 2px solid #222; padding-bottom: 12px; }
        .header h1 { font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header p { font-size: 10px; color: #666; margin-top: 3px; }
        .section { margin-bottom: 16px; }
        .section-title { font-size: 10px; font-weight: 600; text-transform: uppercase; color: #555; margin-bottom: 6px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; }
        .info-item { margin-bottom: 2px; }
        .info-item label { font-weight: 600; font-size: 9px; color: #666; display: block; }
        .info-item span { font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f5f5f5; font-weight: 600; text-align: left; padding: 5px 4px; border: 1px solid #bbb; font-size: 9px; text-transform: uppercase; }
        td { padding: 4px; border: 1px solid #ccc; font-size: 10px; vertical-align: top; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .tfoot td { font-weight: 600; background: #f5f5f5; border: 1px solid #bbb; font-size: 10px; }
        .signature { margin-top: 35px; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .signature-box { border-top: 1px solid #333; padding-top: 6px; text-align: center; }
        .signature-box p { font-size: 9px; color: #666; margin-top: 3px; }
        .footer { margin-top: 25px; padding-top: 8px; border-top: 1px solid #ccc; font-size: 9px; color: #666; text-align: center; }
        @media print { body { padding: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Purchase Order</h1>
            <p>{{ $purchaseOrder->po_number }}</p>
        </div>

        <div class="section">
            <div class="section-title">Details</div>
            <div class="info-grid">
                <div class="info-item">
                    <label>PO NUMBER</label>
                    <span>{{ $purchaseOrder->po_number }}</span>
                </div>
                <div class="info-item">
                    <label>STATUS</label>
                    <span>{{ $purchaseOrder->status }}</span>
                </div>
                <div class="info-item">
                    <label>PROCUREMENT REF.</label>
                    <span>{{ $purchaseOrder->procurement?->procurement_number ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>SUPPLIER</label>
                    <span>{{ $purchaseOrder->supplier?->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>PREPARED BY</label>
                    <span>{{ $purchaseOrder->preparedBy?->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>DATE</label>
                    <span>{{ $purchaseOrder->date_prepared->format('F d, Y') }}</span>
                </div>
                <div class="info-item">
                    <label>DELIVERY DEADLINE</label>
                    <span>{{ $purchaseOrder->delivery_deadline?->format('F d, Y') ?? 'N/A' }}</span>
                </div>
                @if($purchaseOrder->supplier?->address)
                    <div class="info-item">
                        <label>SUPPLIER ADDRESS</label>
                        <span>{{ $purchaseOrder->supplier->address }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">Items</div>
            <table>
                <thead>
                    <tr>
                        <th class="text-center" style="width: 4%;">#</th>
                        <th style="width: 36%;">Description</th>
                        <th class="text-center" style="width: 8%;">Unit</th>
                        <th class="text-right" style="width: 12%;">Qty</th>
                        @if(!$purchaseOrder->hide_price)
                            <th class="text-right" style="width: 18%;">Unit Price</th>
                            <th class="text-right" style="width: 22%;">Total</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->items as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->item_description }}</td>
                            <td class="text-center">{{ $item->unit }}</td>
                            <td class="text-right">{{ number_format($item->quantity, 0) }}</td>
                            @if(!$purchaseOrder->hide_price)
                                <td class="text-right">₱ {{ number_format($item->unit_price ?? 0, 2) }}</td>
                                <td class="text-right">₱ {{ number_format($item->total_price ?? 0, 2) }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                @if(!$purchaseOrder->hide_price)
                    <tfoot class="tfoot">
                        <tr>
                            <td colspan="5" class="text-right">TOTAL AMOUNT:</td>
                            <td class="text-right">₱ {{ number_format($purchaseOrder->total_amount ?? 0, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        @if($purchaseOrder->notes)
            <div class="section">
                <div class="section-title">Notes</div>
                <p style="font-size: 10px;">{{ $purchaseOrder->notes }}</p>
            </div>
        @endif

        <div class="signature">
            <div class="signature-box">
                <p>Prepared By:</p>
                <p style="margin-top: 35px; font-weight: bold;">{{ $purchaseOrder->preparedBy?->name ?? 'N/A' }}</p>
                <p>Date: {{ $purchaseOrder->date_prepared->format('F d, Y') }}</p>
            </div>
            <div class="signature-box">
                <p>Approved By:</p>
                <p style="margin-top: 35px; font-weight: bold;">_______________________</p>
                <p>Date: _______________</p>
            </div>
        </div>

        <div class="footer">
            <p>Generated on {{ now()->format('F d, Y h:i A') }} | {{ $purchaseOrder->po_number }}</p>
        </div>
    </div>
</body>
</html>
