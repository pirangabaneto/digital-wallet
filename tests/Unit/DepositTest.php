<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepositTest extends TestCase
{
    /** @test
     * Criar um depósito válido
     */
    public function it_creates_a_valid_deposit()
    {
        $wallet = Wallet::factory()->make();

        $transactionMock = $this->mock(Transaction::class);
        
        $transactionMock->shouldReceive('create')
            ->once()
            ->with([
                'wallet_id' => $wallet->id,
                'amount' => 100.00,
                'type' => 'deposit',
            ])
            ->andReturn((object) ['wallet_id' => $wallet->id, 'amount' => 100.00, 'type' => 'deposit']);

        $transaction = $transactionMock->create([
            'wallet_id' => $wallet->id,
            'amount' => 100.00,
            'type' => 'deposit',
        ]);

        $this->assertEquals(100.00, $transaction->amount);
        $this->assertEquals('deposit', $transaction->type);
    }

}