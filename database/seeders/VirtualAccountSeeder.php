<?php

namespace Database\Seeders;

use App\Models\VirtualAccount;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VirtualAccountSeeder extends Seeder
{
    public function run(): void
    {
        $wallet = Wallet::where('customer_id', 'XILEF')->firstOrFail();

        $virtualAccount = new VirtualAccount();
        $virtualAccount->bank = 'BCA';
        $virtualAccount->va_number = '123456789';
        $virtualAccount->wallet_id = $wallet->id;

        $virtualAccount->save();
    }
}
