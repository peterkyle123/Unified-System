<?php

namespace Database\Seeders;

use App\Models\Rfq;
use App\Models\RfqItem;
use Illuminate\Database\Seeder;

class RfqSeeder extends Seeder
{
    public function run(): void
    {
        $rfqs = [
            [
                'rfq_number'    => 'RFQ-2025-001',
                'agency_id'     => 8,
                'date_received' => '2025-05-15',
                'deadline'      => '2025-05-22',
                'abc'           => 85000,
                'status'        => 'Reviewing',
                'notes'         => 'Urgent procurement for hospital supplies',
                'philgeps_ref'  => '9876543',
                'items' => [
                    ['item_description' => 'Amoxicillin 500mg Capsule', 'unit' => 'capsule', 'quantity' => 500,  'unit_price' => 8.50, 'brand' => 'Biogesic'],
                    ['item_description' => 'Paracetamol 500mg Tablet',  'unit' => 'tablet',  'quantity' => 1000, 'unit_price' => 2.75, 'brand' => 'Bioflu'],
                    ['item_description' => 'Ibuprofen 400mg Tablet',    'unit' => 'tablet',  'quantity' => 800,  'unit_price' => 4.20, 'brand' => 'Advil'],
                ],
            ],
            [
                'rfq_number'    => 'RFQ-2025-002',
                'agency_id'     => 9,
                'date_received' => '2025-05-10',
                'deadline'      => '2025-05-25',
                'abc'           => 142000,
                'status'        => 'Quoted',
                'notes'         => 'Cardiovascular medications for Q3',
                'philgeps_ref'  => '9876544',
                'items' => [
                    ['item_description' => 'Metformin 500mg Tablet',   'unit' => 'tablet', 'quantity' => 2000, 'unit_price' => 5.00, 'brand' => 'Glucophage'],
                    ['item_description' => 'Amlodipine 5mg Tablet',    'unit' => 'tablet', 'quantity' => 1000, 'unit_price' => 7.25, 'brand' => 'Norvasc'],
                    ['item_description' => 'Losartan 50mg Tablet',     'unit' => 'tablet', 'quantity' => 1200, 'unit_price' => 9.50, 'brand' => 'Cozaar'],
                ],
            ],
            [
                'rfq_number'    => 'RFQ-2025-003',
                'agency_id'     => 10,
                'date_received' => '2025-05-08',
                'deadline'      => '2025-05-20',
                'abc'           => 38500,
                'status'        => 'Received',
                'notes'         => 'Pediatric supplies needed',
                'philgeps_ref'  => null,
                'items' => [
                    ['item_description' => 'ORS Sachet',                'unit' => 'sachet', 'quantity' => 5000, 'unit_price' => 4.50, 'brand' => 'Hydrite'],
                    ['item_description' => 'Cetirizine 10mg Tablet',    'unit' => 'tablet', 'quantity' => 500,  'unit_price' => 6.00, 'brand' => 'Zyrtec'],
                    ['item_description' => 'Paracetamol 250mg/5mL Suspension','unit' => 'bottle','quantity' => 200, 'unit_price' => 45.00, 'brand' => 'Tylenol'],
                ],
            ],
        ];

        foreach ($rfqs as $data) {
            $items = $data['items'];
            unset($data['items']);

            $rfq = Rfq::create($data);

            foreach ($items as $item) {
                $item['total_price'] = $item['unit_price'] * $item['quantity'];
                $rfq->items()->create($item);
            }
        }
    }
}