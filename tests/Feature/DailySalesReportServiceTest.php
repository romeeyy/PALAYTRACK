<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Delivery;
use App\Models\RiceType;
use App\Models\Transaction;
use App\Models\User;
use App\Services\DailySalesReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailySalesReportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_is_paid_only_grouped_by_client_and_attributed_to_cashier(): void
    {
        $cashier = User::factory()->create(['name' => 'Cashier One', 'role' => 'staff', 'is_active' => true]);
        $otherCashier = User::factory()->create(['name' => 'Cashier Two', 'role' => 'staff', 'is_active' => true]);
        $riceType = RiceType::create(['name' => 'White', 'recovery_rate' => 70, 'status' => 'active']);
        $clientOne = Client::create(['name' => 'Juan Dela Cruz', 'contact_number' => '09111111111']);
        $clientTwo = Client::create(['name' => 'Juan Dela Cruz', 'contact_number' => '09222222222']);

        $first = $this->delivery($clientOne, $riceType, $otherCashier, 1);
        $second = $this->delivery($clientOne, $riceType, $otherCashier, 2);
        $sameNameDifferentClient = $this->delivery($clientTwo, $riceType, $otherCashier, 3);
        $unpaid = $this->delivery($clientOne, $riceType, $otherCashier, 4);

        $this->transaction($first, $cashier, 200, 400, 20, 10, 'cash', 'paid', '2026-07-21 08:00:00');
        $this->transaction($second, $cashier, 300, 600, 0, 50, 'gcash', 'paid', '2026-07-21 15:00:00');
        $this->transaction($sameNameDifferentClient, $cashier, 100, 200, 0, 0, 'maya', 'paid', '2026-07-21 16:00:00');
        $this->transaction($unpaid, $cashier, 500, 1000, 0, 0, 'cash', 'pending', null);

        $report = app(DailySalesReportService::class)->generate('2026-07-21', $cashier->id);

        $this->assertSame(3, $report['totalTransactions']);
        $this->assertSame(1160.0, $report['totalIncome']);
        $this->assertSame(1200.0, $report['subtotal']);
        $this->assertSame(20.0, $report['otherCharges']);
        $this->assertSame(60.0, $report['discounts']);
        $this->assertSame(410.0, $report['cashIncome']);
        $this->assertSame(750.0, $report['digitalIncome']);
        $this->assertCount(2, $report['groupedSales']);

        $clientOneGroup = $report['groupedSales']->firstWhere('client_id', $clientOne->id);
        $this->assertSame(2, (int) $clientOneGroup->transaction_count);
        $this->assertSame('Cashier One', $clientOneGroup->staff_name);

        $this->actingAs($cashier)
            ->get(route('staff.reports', ['date' => '2026-07-21']))
            ->assertOk()
            ->assertDontSee('<th>Cashier</th>', false)
            ->assertSee('Prepared By:');
    }

    private function delivery(Client $client, RiceType $riceType, User $recorder, int $queue): Delivery
    {
        return Delivery::create([
            'delivery_id' => 'DEL-REPORT-' . $queue,
            'staff_id' => $recorder->id,
            'queue_number' => $queue,
            'client_id' => $client->id,
            'client_name' => $client->name,
            'contact_number' => $client->contact_number,
            'rice_type_id' => $riceType->id,
            'sacks' => 1,
            'palay_weight' => 500,
            'recovery_rate' => 70,
            'estimated_rice' => 350,
            'actual_rice' => 350,
            'status' => 'completed',
            'delivered_at' => now(),
        ]);
    }

    private function transaction(
        Delivery $delivery,
        User $cashier,
        float $weight,
        float $subtotal,
        float $charges,
        float $discount,
        string $method,
        string $status,
        ?string $paidAt
    ): Transaction {
        return Transaction::create([
            'delivery_id' => $delivery->id,
            'user_id' => $cashier->id,
            'milling_type' => 'menudo',
            'milling_fee_per_kg' => 2,
            'palay_weight_kg' => $weight,
            'subtotal' => $subtotal,
            'other_charges' => $charges,
            'discount' => $discount,
            'total_amount' => $subtotal + $charges - $discount,
            'payment_method' => $method,
            'amount_received' => $subtotal + $charges - $discount,
            'payment_status' => $status,
            'paid_at' => $paidAt,
        ]);
    }
}
