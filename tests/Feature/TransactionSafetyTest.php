<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Delivery;
use App\Models\RiceType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_digital_payment_requires_the_exact_total_and_a_unique_reference(): void
    {
        $staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $delivery = $this->delivery($staff, 200, 1);

        $this->actingAs($staff)->post(route('staff.pos.store', $delivery), [
            'payment_method' => 'gcash',
            'amount_received' => 401,
            'reference_number' => '1234567890123',
        ])->assertSessionHasErrors('amount_received');

        $this->assertDatabaseCount('transactions', 0);

        $this->actingAs($staff)->post(route('staff.pos.store', $delivery), [
            'payment_method' => 'gcash',
            'amount_received' => 400,
            'reference_number' => '1234567890123',
        ])->assertRedirect('/staff/receipt/' . $delivery->id);

        $this->assertDatabaseHas('transactions', [
            'delivery_id' => $delivery->id,
            'user_id' => $staff->id,
            'payment_status' => 'paid',
            'change_amount' => 0,
        ]);

        $secondDelivery = $this->delivery($staff, 200, 2);
        $this->actingAs($staff)->post(route('staff.pos.store', $secondDelivery), [
            'payment_method' => 'gcash',
            'amount_received' => 400,
            'reference_number' => '1234567890123',
        ])->assertSessionHasErrors('reference_number');
    }

    public function test_adjustments_require_a_reason_and_discount_cannot_exceed_the_bill(): void
    {
        $staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $delivery = $this->delivery($staff, 200, 1);

        $this->actingAs($staff)->post(route('staff.pos.store', $delivery), [
            'other_charges' => 10,
            'discount' => 0,
            'payment_method' => 'cash',
            'amount_received' => 410,
        ])->assertSessionHasErrors('notes');

        $this->actingAs($staff)->post(route('staff.pos.store', $delivery), [
            'other_charges' => 0,
            'discount' => 401,
            'notes' => 'Invalid excessive discount test',
            'payment_method' => 'cash',
            'amount_received' => 0,
        ])->assertSessionHasErrors('discount');

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_staff_can_only_list_and_open_their_own_paid_transactions(): void
    {
        $firstStaff = User::factory()->create(['name' => 'First Cashier', 'role' => 'staff', 'is_active' => true]);
        $secondStaff = User::factory()->create(['name' => 'Second Cashier', 'role' => 'staff', 'is_active' => true]);
        $firstDelivery = $this->delivery($firstStaff, 200, 1, 'Visible Client');
        $secondDelivery = $this->delivery($secondStaff, 200, 2, 'Private Client');

        $this->transaction($firstDelivery, $firstStaff, 'cash');
        $this->transaction($secondDelivery, $secondStaff, 'maya', '987654');

        $this->actingAs($firstStaff)
            ->get(route('staff.transactions', ['date' => now()->toDateString()]))
            ->assertOk()
            ->assertSee('Visible Client')
            ->assertDontSee('Private Client');

        $this->actingAs($firstStaff)
            ->get(route('staff.receipt', $firstDelivery))
            ->assertOk()
            ->assertSee('Back to Transactions')
            ->assertSee(route('staff.transactions', ['date' => now()->toDateString()]), false);

        $this->actingAs($firstStaff)
            ->get(route('staff.receipt', $secondDelivery))
            ->assertForbidden();
    }

    public function test_owner_transaction_filters_accept_all_and_filter_by_cashier_and_milling_type(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        $firstStaff = User::factory()->create(['name' => 'First Cashier', 'role' => 'staff', 'is_active' => true]);
        $secondStaff = User::factory()->create(['name' => 'Second Cashier', 'role' => 'staff', 'is_active' => true]);
        $firstDelivery = $this->delivery($firstStaff, 200, 1, 'Menudo Client');
        $secondDelivery = $this->delivery($secondStaff, 200, 2, 'Commercial Client');

        $this->transaction($firstDelivery, $firstStaff, 'cash');
        $commercial = $this->transaction($secondDelivery, $secondStaff, 'maya', '987654');
        $commercial->update(['milling_type' => 'commercial']);

        $filters = [
            'date' => now()->toDateString(),
            'milling_type' => 'all',
            'staff_id' => 'all',
        ];

        $this->actingAs($owner)
            ->get(route('owner.payment-records', $filters))
            ->assertOk()
            ->assertSee('Menudo Client')
            ->assertSee('Commercial Client');

        $this->actingAs($owner)
            ->get(route('owner.payment-records', array_merge($filters, [
                'staff_id' => $firstStaff->id,
            ])))
            ->assertOk()
            ->assertSee('Menudo Client')
            ->assertDontSee('Commercial Client');

        $this->actingAs($owner)
            ->get(route('owner.payment-records', array_merge($filters, [
                'milling_type' => 'commercial',
                'payment_method' => 'maya',
                'search' => 'Commercial Client',
            ])))
            ->assertOk()
            ->assertSee('Commercial Client')
            ->assertDontSee('Menudo Client');

        $this->actingAs($owner)
            ->get(route('owner.payment-records', array_merge($filters, [
                'payment_method' => 'cash',
            ])))
            ->assertOk()
            ->assertSee('Menudo Client')
            ->assertDontSee('Commercial Client');
    }

    private function delivery(User $staff, float $weight, int $queue, string $clientName = 'Test Client'): Delivery
    {
        $riceType = RiceType::firstOrCreate(
            ['name' => 'White'],
            ['recovery_rate' => 70, 'status' => 'active']
        );
        $client = Client::create([
            'name' => $clientName,
            'contact_number' => '0912345678' . $queue,
        ]);

        return Delivery::create([
            'delivery_id' => 'DEL-SAFE-' . $queue,
            'staff_id' => $staff->id,
            'queue_number' => $queue,
            'client_id' => $client->id,
            'client_name' => $client->name,
            'contact_number' => $client->contact_number,
            'rice_type_id' => $riceType->id,
            'sacks' => 1,
            'palay_weight' => $weight,
            'recovery_rate' => 70,
            'estimated_rice' => $weight * .7,
            'actual_rice' => $weight * .7,
            'status' => 'completed',
            'delivered_at' => now(),
            'completed_at' => now(),
        ]);
    }

    private function transaction(Delivery $delivery, User $cashier, string $method, ?string $reference = null): Transaction
    {
        return Transaction::create([
            'delivery_id' => $delivery->id,
            'user_id' => $cashier->id,
            'milling_type' => 'menudo',
            'milling_fee_per_kg' => 2,
            'palay_weight_kg' => $delivery->palay_weight,
            'subtotal' => 400,
            'total_amount' => 400,
            'payment_method' => $method,
            'amount_received' => 400,
            'change_amount' => 0,
            'reference_number' => $reference,
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
    }
}
