<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Scopes\IsActiveScope;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ReviewSeeder;
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
                'is_active' => true,
            ];
        }

        // $result = Category::query()->insert($categories);
        $result = Category::insert($categories);
        $this->assertTrue($result);

        // $total = Category::query()->count();
        $categories = Category::get();
        $this->assertCount(10, $categories);
    }

    public function testFind()
    {
        $this->seed(CategorySeeder::class);

        // $category = Category::query()->find("FOOD");
        $category = Category::find("FOOD");
        $this->assertNotNull($category);
        $this->assertEquals("FOOD", $category->id);
        $this->assertEquals("Food", $category->name);
        $this->assertEquals("Food Category", $category->description);
    }

    public function testUpdate()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("FOOD");
        $category->name = "Food Updated";
        $result = $category->update();

        $this->assertTrue($result);
    }

    public function testSelect()
    {
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->id = "ID $i";
            $category->name = "Category $i";
            $category->is_active = true;
            $category->save();
        }

        $categories = Category::whereNull("description")->get();
        $this->assertEquals(5, $categories->count());
        $categories->each(function ($category) {
            // $this->assertNull($category->description);
            $category->description = "Category updated";
            $category->update();
        });
    }

    public function testUpdateMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Category $i",
                "is_active" => true,
            ];
        }
        $result = Category::insert($categories);
        $this->assertTrue($result);

        Category::whereNull('description')->update([
            'description' => 'Category updated'
        ]);

        $updateCount = Category::where('description', '=', 'Category updated')->count();

        $this->assertEquals(10, $updateCount);
    }

    public function testDelete()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("FOOD");
        $result = $category->delete();
        $this->assertTrue($result);

        $count = Category::count();
        $this->assertEquals(0, $count);
    }

    public function testDeleteMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Category $i",
                "is_active" => true,
            ];
        }
        $result = Category::insert($categories);
        $this->assertTrue($result);

        $total = Category::count();
        $this->assertEquals(10, $total);

        Category::whereNull('description')->delete();

        $total = Category::count();
        $this->assertEquals(0, $total);
    }

    public function testCreate()
    {
        $request = [
            'id' => 'FOOD',
            'name' => 'Food',
            'description' => 'Food Category'
        ];

        $category = new Category($request);
        $category->save();

        $this->assertNotNull($category->id);
    }

    public function testCreateUsingQueryBuilder()
    {
        $request = [
            'id' => 'FOOD',
            'name' => 'Food',
            'description' => 'Food Category'
        ];

        $category = Category::create($request);

        $this->assertNotNull($category->id);
    }

    public function testUpdateMass()
    {
        $this->seed(CategorySeeder::class);

        $request = [
            'name' => 'Food Updated',
            'category' => 'Food Category Updated'
        ];

        $category = Category::find("FOOD");
        $category->fill($request);
        $category->save();

        $this->assertNotNull($category->id);
        // $this->assertEquals('Food Updated', $category->name);
        // $this->assertEquals('Food Category Updated', $category->category);
    }

    public function testGlobalScope()
    {
        $category = new Category();
        $category->id = 'FOOD';
        $category->name = 'Food';
        $category->description = 'Food Category';
        $category->is_active = false;
        $category->save();

        //  where is_active = 1
        $category = Category::find("FOOD");
        $this->assertNull($category);

        // tanpa where is_active = 1
        $category = Category::withoutGlobalScopes([IsActiveScope::class])->find("FOOD");
        $this->assertNotNull($category);
    }

    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find('FOOD');
        $this->assertNotNull($category);

        $products = $category->products;
        $this->assertNotNull($products);
        $this->assertCount(2, $products);
    }

    public function testOneToManyQuery()
    {
        $category = new Category();
        $category->id = 'FOOD';
        $category->name = 'Food';
        $category->description = 'Food';
        $category->is_active = true;
        $category->save();

        $product = new Product();
        $product->id = '1';
        $product->name = 'Contoh 1';
        $product->description = 'Contoh 1';

        $category->products()->save($product);

        $this->assertNotNull($product->category_id);
    }

    public function testRelationshipQuery()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find('FOOD');
        $products = $category->products;
        $this->assertCount(2, $products);

        $outOfStockProducts = $category->products()->where('stock', '<=', 0)->get();
        $this->assertCount(2, $outOfStockProducts);
    }

    // select `reviews`.*, `products`.`category_id` as `laravel_through_key` from `reviews` inner join `products` on `products`.`id` = `reviews`.`product_id` where `products`.`category_id` = ?
    public function testHasManyThrough()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, CustomerSeeder::class, ReviewSeeder::class]);

        $category = Category::find("FOOD");
        $this->assertNotNull($category);

        $reviews = $category->reviews;
        $this->assertNotNull($reviews);
        $this->assertCount(2, $reviews);
    }

    public function testQueryingRelations()
    {
        $this->seed([CategorySeeder::class,  ProductSeeder::class]);

        $category = Category::find("FOOD");
        $products = $category->products()->where("price", "=", 200)->get();

        $this->assertCount(1, $products);
    }
}
