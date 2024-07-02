<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\VirtualAccountSeeder;
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

    public function testOneToOneQuery()
    {
        $customer = new Customer();
        $customer->id = 'XILEF';
        $customer->name = 'Xilef';
        $customer->email = 'xilef@gmail.com';
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 1000000;

        $customer->wallet()->save($wallet);

        $this->assertNotNull($wallet->customer_id);
    }

    // Relation : Customer -> Wallet -> Virtual Account
    // select `virtual_accounts`.*, `wallets`.`customer_id` as `laravel_through_key` from `virtual_accounts` inner join `wallets` on `wallets`.`id` = `virtual_accounts`.`wallet_id` where `wallets`.`customer_id` = ? limit 1  
    public function testHasOneThrough()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, VirtualAccountSeeder::class]);

        $customer = Customer::find('XILEF');
        $this->assertNotNull($customer);

        $virtualAccount = $customer->virtualAccount;
        $this->assertNotNull($virtualAccount);
        $this->assertEquals($virtualAccount->wallet_id, $customer->wallet->id);
    }
}
