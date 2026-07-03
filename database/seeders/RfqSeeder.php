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
                'status'        => 'Awarded',
                'philgeps_ref'  => '9876543',
                'items' => [
                    ['item_description' => 'Amoxicillin 500mg Capsule', 'unit' => 'capsule', 'quantity' => 500,  'unit_price' => 8.50],
                    ['item_description' => 'Paracetamol 500mg Tablet',  'unit' => 'tablet',  'quantity' => 1000, 'unit_price' => 2.75],
                    ['item_description' => 'Ibuprofen 400mg Tablet',    'unit' => 'tablet',  'quantity' => 800,  'unit_price' => 4.20],
                    ['item_description' => 'Omeprazole 20mg Capsule',   'unit' => 'capsule', 'quantity' => 600,  'unit_price' => 12.00],
                    ['item_description' => 'Ciprofloxacin 500mg Tablet','unit' => 'tablet',  'quantity' => 400,  'unit_price' => 15.50],
                    ['item_description' => 'Doxycycline 100mg Capsule', 'unit' => 'capsule', 'quantity' => 300,  'unit_price' => 6.75],
                    ['item_description' => 'Mefenamic Acid 500mg Capsule','unit' => 'capsule','quantity' => 350,  'unit_price' => 5.00],
                    ['item_description' => 'Loratadine 10mg Tablet',    'unit' => 'tablet',  'quantity' => 700,  'unit_price' => 3.80],
                    ['item_description' => 'Salbutamol Inhaler',        'unit' => 'piece',   'quantity' => 100,  'unit_price' => 145.00],
                    ['item_description' => 'Betadine Ointment 30g',     'unit' => 'tube',    'quantity' => 200,  'unit_price' => 88.00],
                ],
            ],
            [
                'rfq_number'    => 'RFQ-2025-002',
                'agency_id'     => 9,
                'date_received' => '2025-05-10',
                'deadline'      => '2025-05-25',
                'abc'           => 142000,
                'status'        => 'Awarded',
                'philgeps_ref'  => '9876544',
                'items' => [
                    ['item_description' => 'Metformin 500mg Tablet',     'unit' => 'tablet', 'quantity' => 2000, 'unit_price' => 5.00],
                    ['item_description' => 'Amlodipine 5mg Tablet',      'unit' => 'tablet', 'quantity' => 1000, 'unit_price' => 7.25],
                    ['item_description' => 'Losartan 50mg Tablet',       'unit' => 'tablet', 'quantity' => 1200, 'unit_price' => 9.50],
                    ['item_description' => 'Atorvastatin 20mg Tablet',   'unit' => 'tablet', 'quantity' => 800,  'unit_price' => 18.00],
                    ['item_description' => 'Clopidogrel 75mg Tablet',    'unit' => 'tablet', 'quantity' => 500,  'unit_price' => 35.00],
                    ['item_description' => 'ASA 100mg Tablet',           'unit' => 'tablet', 'quantity' => 1500, 'unit_price' => 2.50],
                    ['item_description' => 'Glibenclamide 5mg Tablet',   'unit' => 'tablet', 'quantity' => 600,  'unit_price' => 3.00],
                    ['item_description' => 'Carvedilol 6.25mg Tablet',   'unit' => 'tablet', 'quantity' => 400,  'unit_price' => 12.50],
                    ['item_description' => 'Insulin Glargine 10mL',      'unit' => 'vial',   'quantity' => 50,   'unit_price' => 950.00],
                    ['item_description' => 'Metformin 500mg/1g Tablet',  'unit' => 'tablet', 'quantity' => 700,  'unit_price' => 15.00],
                ],
            ],
            [
                'rfq_number'    => 'RFQ-2025-003',
                'agency_id'     => 10,
                'date_received' => '2025-05-08',
                'deadline'      => '2025-05-20',
                'abc'           => 38500,
                'status'        => 'Awarded',
                'philgeps_ref'  => null,
                'items' => [
                    ['item_description' => 'ORS Sachet',           'unit' => 'sachet', 'quantity' => 5000, 'unit_price' => 4.50],
                    ['item_description' => 'Cetirizine 10mg Tablet', 'unit' => 'tablet', 'quantity' => 500,  'unit_price' => 6.00],
                    ['item_description' => 'Dexamethasone 5mg Tablet','unit' => 'tablet','quantity' => 300,  'unit_price' => 8.50],
                    ['item_description' => 'Acetylcysteine 600mg Tablet','unit' => 'tablet','quantity' => 400, 'unit_price' => 22.00],
                    ['item_description' => 'Ambroxol 30mg Tablet',    'unit' => 'tablet', 'quantity' => 800,  'unit_price' => 5.50],
                    ['item_description' => 'Paracetamol 250mg/5mL Suspension','unit' => 'bottle','quantity' => 200, 'unit_price' => 45.00],
                    ['item_description' => 'Carbocisteine 500mg Capsule','unit' => 'capsule','quantity' => 350, 'unit_price' => 11.00],
                    ['item_description' => 'Salbutamol Sulfate Inhaler','unit' => 'piece', 'quantity' => 80,   'unit_price' => 165.00],
                    ['item_description' => 'Oxymetazoline Nasal Spray','unit' => 'piece',  'quantity' => 150,  'unit_price' => 72.00],
                    ['item_description' => 'Diclofenac Sodium 50mg Tablet','unit' => 'tablet','quantity' => 600, 'unit_price' => 4.80],
                ],
            ],
            [
                'rfq_number'    => 'RFQ-2025-004',
                'agency_id'     => 8,
                'date_received' => '2025-05-01',
                'deadline'      => '2025-05-28',
                'abc'           => 210000,
                'status'        => 'Awarded',
                'philgeps_ref'  => '9876545',
                'items' => [
                    ['item_description' => 'Cefuroxime 500mg Tablet', 'unit' => 'tablet', 'quantity' => 1000, 'unit_price' => 45.00],
                    ['item_description' => 'Omeprazole 20mg Capsule', 'unit' => 'capsule', 'quantity' => 2000, 'unit_price' => 12.00],
                ],
            ],
            [
                'rfq_number'    => 'RFQ-2025-005',
                'agency_id'     => 9,
                'date_received' => '2025-04-28',
                'deadline'      => '2025-05-18',
                'abc'           => 55000,
                'status'        => 'Lost',
                'philgeps_ref'  => null,
                'items' => [
                    ['item_description' => 'Vitamin C 500mg Tablet',  'unit' => 'tablet', 'quantity' => 3000, 'unit_price' => 3.50],
                    ['item_description' => 'Iron Supplement 325mg',   'unit' => 'tablet', 'quantity' => 1000, 'unit_price' => 8.00],
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