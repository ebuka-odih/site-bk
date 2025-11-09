<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        // Ensure the middleware sees an admin role even if the column is absent.
        $admin->setAttribute('role', 'admin');

        $this->actingAs($admin);

        return $admin;
    }

    public function test_admin_can_create_user_with_full_profile(): void
    {
        $this->actingAsAdmin();

        $payload = [
            'name' => 'Jane Customer',
            'email' => 'jane.customer@example.com',
            'phone' => '+12025550123',
            'password' => 'securePass123',
            'password_confirmation' => 'securePass123',
            'date_of_birth' => now()->subYears(29)->format('Y-m-d'),
            'gender' => 'female',
            'nationality' => 'United States',
            'address_line1' => '123 Market Street',
            'address_line2' => 'Apt 4B',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postal_code' => '94105',
            'country' => 'United States',
            'passport_number' => 'X1234567',
            'passport_country' => 'United States',
            'passport_expiry' => now()->addYears(5)->format('Y-m-d'),
            'tax_identification_number' => 'SSN-001-55-9999',
            'occupation' => 'Product Manager',
            'employment_status' => 'employed',
            'source_of_funds' => 'salary',
            'branch_code' => 'SF001',
            'preferred_currency' => 'USD',
            'account_type' => 'savings',
            'balance' => '1250.75',
            'is_admin' => false,
        ];

        $response = $this->post('/admin/users', $payload);

        $response->assertRedirect(route('admin.users.index'));

        $storedUser = User::where('email', $payload['email'])->first();

        $this->assertNotNull($storedUser, 'User record should exist.');

        $this->assertSame($payload['phone'], $storedUser->phone);
        $this->assertSame($payload['nationality'], $storedUser->nationality);
        $this->assertSame($payload['passport_number'], $storedUser->passport_number);
        $this->assertSame($payload['preferred_currency'], $storedUser->preferred_currency);
        $this->assertSame('savings', $storedUser->account_type);
        $this->assertSame(125075, $storedUser->balance);
        $this->assertFalse($storedUser->is_admin);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $storedUser->id,
            'currency' => $payload['preferred_currency'],
            'balance' => 125075,
            'ledger_balance' => 125075,
        ]);
    }

    public function test_missing_passport_information_returns_validation_error(): void
    {
        $this->actingAsAdmin();

        $payload = [
            'name' => 'John Example',
            'email' => 'john@example.com',
            'phone' => '+442071838750',
            'password' => 'secretPass123',
            'password_confirmation' => 'secretPass123',
            'date_of_birth' => now()->subYears(31)->format('Y-m-d'),
            'gender' => '',
            'nationality' => 'United Kingdom',
            'address_line1' => '10 Downing Street',
            'address_line2' => '',
            'city' => 'London',
            'state' => '',
            'postal_code' => 'SW1A 2AA',
            'country' => 'United Kingdom',
            'passport_number' => '',
            'passport_country' => 'United Kingdom',
            'passport_expiry' => now()->addYears(3)->format('Y-m-d'),
            'tax_identification_number' => '',
            'occupation' => 'Analyst',
            'employment_status' => 'employed',
            'source_of_funds' => 'salary',
            'branch_code' => '',
            'preferred_currency' => 'GBP',
            'account_type' => 'current',
            'balance' => '0',
            'is_admin' => false,
        ];

        $response = $this->from('/admin/users/create')->post('/admin/users', $payload);

        $response->assertSessionHasErrors(['passport_number']);
        $response->assertRedirect('/admin/users/create');

        $this->assertDatabaseMissing('users', ['email' => $payload['email']]);
    }

    public function test_passport_expiry_allows_slash_formatted_future_date(): void
    {
        $this->actingAsAdmin();

        $payload = [
            'name' => 'Format Test',
            'email' => 'passport.format@example.com',
            'phone' => '+61255501234',
            'password' => 'securePass123',
            'password_confirmation' => 'securePass123',
            'date_of_birth' => now()->subYears(35)->format('Y-m-d'),
            'gender' => 'other',
            'nationality' => 'Australia',
            'address_line1' => '1 Harbour Street',
            'address_line2' => '',
            'city' => 'Sydney',
            'state' => 'NSW',
            'postal_code' => '2000',
            'country' => 'Australia',
            'passport_number' => 'N12345678',
            'passport_country' => 'Australia',
            'passport_expiry' => '25/12/2030',
            'tax_identification_number' => '',
            'occupation' => 'Engineer',
            'employment_status' => 'employed',
            'source_of_funds' => 'salary',
            'branch_code' => '',
            'preferred_currency' => 'AUD',
            'account_type' => 'current',
            'balance' => '0',
            'is_admin' => false,
        ];

        $response = $this->post('/admin/users', $payload);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
            'passport_expiry' => '2030-12-25',
            'preferred_currency' => 'AUD',
        ]);
    }

    public function test_passport_expiry_can_be_left_blank(): void
    {
        $this->actingAsAdmin();

        $payload = [
            'name' => 'Optional Passport',
            'email' => 'optional.passport@example.com',
            'phone' => '+33123456789',
            'password' => 'securePass123',
            'password_confirmation' => 'securePass123',
            'date_of_birth' => now()->subYears(28)->format('Y-m-d'),
            'gender' => '',
            'nationality' => 'France',
            'address_line1' => '50 Rue de Rivoli',
            'address_line2' => '',
            'city' => 'Paris',
            'state' => '',
            'postal_code' => '75004',
            'country' => 'France',
            'passport_number' => 'AA1234567',
            'passport_country' => 'France',
            'passport_expiry' => '',
            'tax_identification_number' => '',
            'occupation' => 'Designer',
            'employment_status' => 'self_employed',
            'source_of_funds' => 'business_income',
            'branch_code' => '',
            'preferred_currency' => 'EUR',
            'account_type' => 'business',
            'balance' => '500.50',
            'is_admin' => false,
        ];

        $response = $this->post('/admin/users', $payload);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
            'passport_expiry' => null,
            'preferred_currency' => 'EUR',
        ]);
    }
}


