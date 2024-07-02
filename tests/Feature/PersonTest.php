<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonTest extends TestCase
{
    public function testPerson()
    {
        $person = new Person();
        $person->first_name = 'Felix';
        $person->last_name = 'Xilef';
        $person->save();

        $this->assertEquals('FELIX Xilef', $person->full_name);

        $person->full_name = 'Joko Morro';
        $person->save();

        $this->assertEquals('JOKO', $person->first_name);
        $this->assertEquals('Morro', $person->last_name);
    }

    public function testCustomCast()
    {
        $person = new Person();
        $person->full_name = "Felix Xilef";
        $person->address = new Address("Jalan Belum Jadi", "Jakarta", "Indonesia", "123123"); // manggil set di AsAddress
        $person->save();

        // manggil get di AsAddress
        $this->assertEquals("Jalan Belum Jadi", $person->address->street);
        $this->assertEquals("Jakarta", $person->address->city);
        $this->assertEquals("Indonesia", $person->address->country);
        $this->assertEquals("123123", $person->address->postal_code);
    }
}
