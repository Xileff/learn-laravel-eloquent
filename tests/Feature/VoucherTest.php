<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    public function testCreate()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->voucher_code = '123412341234';
        $voucher->save();

        $this->assertNotNull($voucher->id);
    }

    public function testCreateWithUUID()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->save();

        $this->assertNotNull($voucher->id);
    }

    public function testSoftDelete()
    {
        $this->seed(VoucherSeeder::class);
        $voucher =  Voucher::where('name', '=', 'Sample Voucher')->first();
        $voucher->delete();

        $voucher =  Voucher::where('name', '=', 'Sample Voucher')->first();
        $this->assertNull($voucher);

        $voucher =  Voucher::withTrashed()->where('name', '=', 'Sample Voucher')->first();
        $this->assertNotNull($voucher);
    }

    public function testLocalScope()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->is_active = true;
        $voucher->save();

        // where is_active = 1
        $total = Voucher::active()->count();
        $this->assertEquals(1, $total);

        // where is_active = 0
        $total = Voucher::nonActive()->count();
        $this->assertEquals(0, $total);
    }
}
