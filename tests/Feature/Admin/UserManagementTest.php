<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\InvestmentPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_user_and_cascades_work(): void
    {
        // 1. Create Admin and regular User
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        // 2. Give the user a wallet, kyc request, and transaction
        $wallet = $user->wallet()->create(['currency' => 'USD', 'balance' => 100.00]);
        $tx = $user->transactions()->create([
            'currency' => 'USD',
            'type' => 'deposit',
            'status' => 'approved',
            'amount' => 100.00,
        ]);
        $kyc = $user->kycRequest()->create([
            'id_front_path' => 'front.jpg',
            'id_back_path' => 'back.jpg',
            'selfie_path' => 'selfie.jpg',
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('wallets', ['user_id' => $user->id]);
        $this->assertDatabaseHas('wallet_transactions', ['user_id' => $user->id]);
        $this->assertDatabaseHas('kyc_requests', ['user_id' => $user->id]);

        // 3. Act as admin and delete user
        $response = $this->actingAs($admin)->delete(route('admin.users.delete', $user->id));

        $response->assertRedirect(route('admin.users'));
        $response->assertSessionHas('status', "User account {$user->name} has been deleted successfully.");

        // 4. Assert cascading deletes
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('wallets', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('wallet_transactions', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('kyc_requests', ['user_id' => $user->id]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('admin.users.delete', $admin->id));

        $response->assertSessionHasErrors('delete');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_regular_user_cannot_delete_users(): void
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user1)->delete(route('admin.users.delete', $user2->id));

        $response->assertStatus(403); // MiddlewareIsAdmin should reject
        $this->assertDatabaseHas('users', ['id' => $user2->id]);
    }
}
