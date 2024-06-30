<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testInsert()
    {
        $category = new Category();
        $category->id = 'GADGET';
        $category->name = 'Gadget';
        $result = $category->save();

        $this->assertTrue($result);
    }

    // INSERT data sekaligus, jadi cepet
    public function testInsertMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Name $i",
            ];
        }

        // $result = Category::query()->insert($categories);
        $result = Category::insert($categories);
        $this->assertTrue($result);

        // $total = Category::query()->count();
        $total = Category::count();
        $this->assertEquals(10, $total);
    }
}
