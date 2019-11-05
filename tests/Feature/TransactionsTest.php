<?php

namespace Tests\Feature;

use App\Transaction;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchAllTransactionsFiltering ()
    {

        $transaction1 = factory(Transaction::class)->create(['description' => "transaction1"]);
        $transaction2 = factory(Transaction::class)->create(['description' => "transaction2"]);
        $transaction3 = factory(Transaction::class)->create(['description' => "transaction4"]);
        $transaction4 = factory(Transaction::class)->create(['description' => "transaction44"]);
        $transaction5 = factory(Transaction::class)->create(['description' => "transaction45"]);
        $transaction6 = factory(Transaction::class)->create(['description' => "transaction6"]);
        $transaction7 = factory(Transaction::class)->create(['description' => "transaction7"]);
        $transaction8 = factory(Transaction::class)->create(['description' => "transaction8"]);
        $transaction9 = factory(Transaction::class)->create(['description' => "transaction9"]);
        $transaction10 = factory(Transaction::class)->create(['description' => "transaction10"]);

        $page = 1;

        $response = $this->json(
            'GET',
            'api/transactions?q=transaction4'
        );

        $response->assertJsonFragment([
            'current_page' => $page,
        ]);

        $response->assertSee($transaction3->description);
        $response->assertSee($transaction4->description);
        $response->assertSee($transaction5->description);
        $response->assertDontSee($transaction1->description);
        $response->assertDontSee($transaction2->description);
        $response->assertDontSee($transaction7->description);
        $response->assertDontSee($transaction9->description);
        $response->assertSee('Successfully fetched transactions');

        $page = 2;

        $response = $this->json(
            'GET',
            'api/transactions?q=transaction&page='.$page
        );

        $response->assertJsonFragment([
            'current_page' => $page,
        ]);

        $response->assertSee($transaction10->description);
        $response->assertSee($transaction9->description);
        $response->assertSee($transaction8->description);
        $response->assertSee($transaction7->description);
        $response->assertDontSee($transaction6->description);
        $response->assertDontSee($transaction5->description);
        $response->assertDontSee($transaction3->description);
        $response->assertDontSee($transaction2->description);
        $response->assertSee('Successfully fetched transactions');

    }



    public function testDownloadCsv()
    {
        Transaction::unsetEventDispatcher();

        $transaction1 = factory(Transaction::class)->create();

        $transaction2 = factory(Transaction::class)->create();

        $response = $this->json(
            'GET',
            'api/transactions/download'
        );

        $response->assertJson([
            ['Date', 'Invoice ID', 'Name', 'Email Address', 'Description', 'Amount'],
            ["{$transaction1->date}", "{$transaction1->invoice_id}", "{$transaction1->name}", "{$transaction1->email}", "{$transaction1->description}", "{$transaction1->amount}"],
            ["{$transaction2->date}", "{$transaction2->invoice_id}", "{$transaction2->name}", "{$transaction2->email}", "{$transaction2->description}", "{$transaction2->amount}"],
        ]);

        $response->assertStatus(200);
    }
}
