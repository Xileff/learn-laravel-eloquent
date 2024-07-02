<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    public function testFactory()
    {
        $employee1 = Employee::factory()->programmer()->create([
            'id' => '1',
            'name' => 'Felix'
        ]);

        $this->assertNotNull($employee1);
        $this->assertNotNull(Employee::where('id', '1')->first());

        $employee2 = Employee::factory()->seniorProgrammer()->create([
            'id' => '2',
            'name' => 'Xilef'
        ]);

        $this->assertNotNull($employee2);
        $this->assertNotNull(Employee::where('id', '2')->first());
    }
}
