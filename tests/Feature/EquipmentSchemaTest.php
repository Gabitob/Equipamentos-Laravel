<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EquipmentSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_equipment_table_has_the_registration_fields(): void
    {
        $this->assertTrue(Schema::hasColumns('equipments', [
            'id',
            'name',
            'brand',
            'condition_description',
            'responsible_person_id',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_equipment_can_be_saved_without_a_responsible_person(): void
    {
        $equipment = Equipment::factory()->create(['responsible_person_id' => null]);

        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'responsible_person_id' => null,
        ]);
    }

    public function test_deleting_a_person_keeps_their_equipment_unassigned(): void
    {
        $person = Person::factory()->create();
        $equipment = Equipment::factory()->create(['responsible_person_id' => $person->id]);

        $person->delete();

        $this->assertNull($equipment->fresh()->responsible_person_id);
    }
}
