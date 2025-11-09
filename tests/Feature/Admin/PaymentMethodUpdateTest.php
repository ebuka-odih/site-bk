<?php

namespace Tests\Feature\Admin;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_update_crypto_wallet_configuration(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $paymentMethod = PaymentMethod::create([
            'type' => 'crypto',
            'name' => 'Crypto Deposits',
            'key' => 'crypto',
            'enabled' => true,
            'min_amount' => 1000,
            'max_amount' => null,
            'processing_time' => 'Instant',
            'fee_percentage' => null,
            'fee_fixed' => null,
            'requires_reference' => false,
            'sort_order' => 1,
            'configuration' => [
                'currencies' => [
                    'BTC' => [
                        'name' => 'Bitcoin (BTC)',
                        'address' => 'old-btc-address',
                        'network' => 'Bitcoin',
                    ],
                ],
            ],
            'instructions' => [
                'Wallet Name' => 'Existing Wallet',
            ],
            'notes' => [
                'Existing note',
            ],
        ]);

        $payload = [
            'type' => 'crypto',
            'name' => 'Crypto Deposits',
            'key' => 'crypto',
            'enabled' => true,
            'min_amount' => 1000,
            'max_amount' => null,
            'processing_time' => 'Instant',
            'fee_percentage' => null,
            'fee_fixed' => null,
            'requires_reference' => false,
            'sort_order' => 1,
            'configuration' => [
                'currencies' => [
                    'BTC' => [
                        'name' => 'Bitcoin (BTC)',
                        'address' => 'new-btc-address',
                        'network' => 'Bitcoin',
                    ],
                    'ETH' => [
                        'name' => 'Ethereum (ETH)',
                        'address' => 'eth-address-123',
                        'network' => 'Ethereum (ERC-20)',
                    ],
                ],
            ],
            'instructions' => [
                'Wallet Name' => 'Updated Wallet',
                'Support Email' => 'support@example.com',
            ],
            'notes' => [
                'Updated note',
            ],
        ];

        $response = $this->actingAs($admin)
            ->withoutMiddleware(\App\Http\Middleware\Admin\AdminMiddleware::class)
            ->put("/admin/payment-methods/{$paymentMethod->id}", $payload);

        $response->assertRedirect(route('admin.payment-methods.edit', $paymentMethod));

        $paymentMethod->refresh();

        $this->assertEquals('new-btc-address', $paymentMethod->configuration['currencies']['BTC']['address']);
        $this->assertEquals('Bitcoin (BTC)', $paymentMethod->configuration['currencies']['BTC']['name']);
        $this->assertEquals('Ethereum (ETH)', $paymentMethod->configuration['currencies']['ETH']['name']);
        $this->assertEquals('eth-address-123', $paymentMethod->configuration['currencies']['ETH']['address']);
        $this->assertEquals('Ethereum (ERC-20)', $paymentMethod->configuration['currencies']['ETH']['network']);

        $this->assertEquals('Updated Wallet', $paymentMethod->instructions['Wallet Name']);
        $this->assertEquals(['Updated note'], $paymentMethod->notes);
    }
}

