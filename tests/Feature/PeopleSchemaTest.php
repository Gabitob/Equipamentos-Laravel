<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PeopleSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_people_table_has_the_registration_fields(): void
    {
        $this->assertTrue(Schema::hasColumns('people', [
            'id',
            'name',
            'phone',
            'email',
            'age',
            'created_at',
            'updated_at',
        ]));
    }
}
