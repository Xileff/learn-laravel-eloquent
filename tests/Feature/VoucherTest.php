<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    public function testCreate()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        // $voucher->voucher_code = '123412341234';
        $voucher->save();

        $this->assertNotNull($voucher->id);
    }
}
