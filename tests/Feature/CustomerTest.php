<?php

namespace Tests\Feature;

use App\Models\Customer;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function testOneToOne()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class]);

        $customer = Customer::find('XILEF');
        $wallet = $customer->wallet;

        $this->assertNotNull($wallet);
        $this->assertEquals(1000000, $wallet->amount);
        $this->assertEquals($customer->id, $wallet->customer_id);
    }
}
