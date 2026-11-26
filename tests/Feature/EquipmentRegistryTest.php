<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EquipmentRegistryTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitors_can_access_the_equipment_registry(): void
    {
        $this->get(route('equipment.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('equipment/Index')
                ->has('people', 0));
    }

    public function test_visitors_can_register_equipment(): void
    {
        $this->post(route('equipment.store'), [
            'name' => 'Monitor',
            'brand' => 'Marca Exemplo',
            'condition_description' => 'Sem avarias',
        ])->assertRedirect(route('equipment.index'));

        $this->assertDatabaseHas('equipments', [
            'name' => 'Monitor',
            'responsible_person_id' => null,
        ]);
    }

    public function test_equipment_page_provides_people_for_the_responsible_select(): void
    {
        $user = User::factory()->create();
        $secondPerson = Person::factory()->create(['name' => 'Zoe Example']);
        $firstPerson = Person::factory()->create(['name' => 'Alice Example']);

        $this->actingAs($user)
            ->get(route('equipment.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('equipment/Index')
                ->has('people', 2)
                ->where('people.0.id', $firstPerson->id)
                ->where('people.0.name', 'Alice Example')
                ->where('people.1.id', $secondPerson->id)
                ->where('people.1.name', 'Zoe Example'));
    }

    public function test_authenticated_users_can_register_equipment_with_a_responsible_person(): void
    {
        $user = User::factory()->create();
        $person = Person::factory()->create();

        $response = $this->actingAs($user)->post(route('equipment.store'), [
            'name' => 'Notebook',
            'brand' => 'Marca Exemplo',
            'condition_description' => 'Funcionando normalmente',
            'responsible_person_id' => $person->id,
        ]);

        $response->assertRedirect(route('equipment.index'));
        $this->assertDatabaseHas('equipments', [
            'name' => 'Notebook',
            'brand' => 'Marca Exemplo',
            'condition_description' => 'Funcionando normalmente',
            'responsible_person_id' => $person->id,
        ]);
    }

    public function test_equipment_can_be_registered_without_a_responsible_person(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('equipment.store'), [
            'name' => 'Monitor',
            'brand' => 'Marca Exemplo',
            'condition_description' => 'Sem avarias',
            'responsible_person_id' => '',
        ])->assertRedirect(route('equipment.index'));

        $this->assertDatabaseHas('equipments', [
            'name' => 'Monitor',
            'responsible_person_id' => null,
        ]);
    }

    public function test_visitors_can_update_equipment_responsible_person(): void
    {
        $previousPerson = Person::factory()->create();
        $newPerson = Person::factory()->create();
        $equipment = Equipment::factory()->create([
            'name' => 'Notebook',
            'brand' => 'Marca Exemplo',
            'condition_description' => 'Funcionando normalmente',
            'responsible_person_id' => $previousPerson->id,
        ]);

        $this->from(route('equipment.index'))
            ->patch(route('equipment.responsible.update', $equipment), [
                'responsible_person_id' => $newPerson->id,
            ])
            ->assertRedirect(route('equipment.index'));

        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'name' => 'Notebook',
            'brand' => 'Marca Exemplo',
            'condition_description' => 'Funcionando normalmente',
            'responsible_person_id' => $newPerson->id,
        ]);
    }

    public function test_visitors_can_remove_equipment_responsible_person(): void
    {
        $person = Person::factory()->create();
        $equipment = Equipment::factory()->create([
            'responsible_person_id' => $person->id,
        ]);

        $this->from(route('equipment.index'))
            ->patch(route('equipment.responsible.update', $equipment), [
                'responsible_person_id' => null,
            ])
            ->assertRedirect(route('equipment.index'));

        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'responsible_person_id' => null,
        ]);
    }

    public function test_invalid_responsible_person_does_not_change_equipment_assignment(): void
    {
        $person = Person::factory()->create();
        $equipment = Equipment::factory()->create([
            'responsible_person_id' => $person->id,
        ]);

        $this->from(route('equipment.index'))
            ->patch(route('equipment.responsible.update', $equipment), [
                'responsible_person_id' => 999,
            ])
            ->assertRedirect(route('equipment.index'))
            ->assertSessionHasErrors('responsible_person_id');

        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'responsible_person_id' => $person->id,
        ]);
    }

    public function test_visitors_can_update_each_equipment_field_without_changing_other_fields(): void
    {
        $person = Person::factory()->create();
        $equipment = Equipment::factory()->create([
            'name' => 'Notebook',
            'brand' => 'Marca A',
            'condition_description' => 'Em bom estado',
            'responsible_person_id' => $person->id,
        ]);
        $expected = [
            'name' => 'Notebook',
            'brand' => 'Marca A',
            'condition_description' => 'Em bom estado',
            'responsible_person_id' => $person->id,
        ];
        $updates = [
            'name' => 'Desktop',
            'brand' => 'Marca B',
            'condition_description' => 'Necessita manutenção',
        ];

        foreach ($updates as $field => $value) {
            $expected[$field] = $value;

            $this->from(route('equipment.index'))
                ->patch(route('equipment.field.update', [
                    'equipment' => $equipment,
                    'field' => $field,
                ]), ['value' => $value])
                ->assertRedirect(route('equipment.index'));

            $this->assertDatabaseHas('equipments', [
                'id' => $equipment->id,
                ...$expected,
            ]);
        }
    }

    public function test_invalid_equipment_field_updates_leave_saved_values_unchanged(): void
    {
        $person = Person::factory()->create();
        $equipment = Equipment::factory()->create([
            'name' => 'Notebook',
            'brand' => 'Marca A',
            'condition_description' => 'Em bom estado',
            'responsible_person_id' => $person->id,
        ]);
        $invalidValues = [
            'name' => '',
            'brand' => ['Marca B'],
            'condition_description' => ['Texto inválido'],
        ];

        foreach ($invalidValues as $field => $value) {
            $this->from(route('equipment.index'))
                ->patch(route('equipment.field.update', [
                    'equipment' => $equipment,
                    'field' => $field,
                ]), ['value' => $value])
                ->assertRedirect(route('equipment.index'))
                ->assertSessionHasErrors('value');

            $this->assertDatabaseHas('equipments', [
                'id' => $equipment->id,
                'name' => 'Notebook',
                'brand' => 'Marca A',
                'condition_description' => 'Em bom estado',
                'responsible_person_id' => $person->id,
            ]);
        }
    }

    public function test_equipment_fields_and_responsible_person_are_validated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('equipment.store'), [
                'name' => '',
                'brand' => '',
                'condition_description' => '',
                'responsible_person_id' => 999,
            ])
            ->assertSessionHasErrors([
                'name',
                'brand',
                'condition_description',
                'responsible_person_id',
            ]);

        $this->assertDatabaseCount('equipments', 0);
    }

    public function test_equipment_can_be_searched_by_fields_and_responsible_person_name(): void
    {
        $user = User::factory()->create();
        $person = Person::factory()->create(['name' => 'Alice Responsible']);
        $equipment = Equipment::factory()->create([
            'name' => 'Notebook',
            'brand' => 'Brand-needle',
            'condition_description' => 'Condition-needle',
            'responsible_person_id' => $person->id,
        ]);
        Equipment::factory()->create(['name' => 'Unrelated printer']);

        foreach (['note', 'Brand-needle', 'Condition-needle', 'Alice Responsible'] as $term) {
            $this->actingAs($user)
                ->get(route('equipment.index', ['search' => $term]))
                ->assertOk()
                ->assertInertia(fn (Assert $page) => $page
                    ->component('equipment/Index')
                    ->has('equipment.data', 1)
                    ->where('equipment.data.0.id', $equipment->id)
                    ->where('equipment.data.0.responsible_person', [
                        'id' => $person->id,
                        'name' => 'Alice Responsible',
                    ])
                    ->where('filters.search', $term));
        }
    }

    public function test_equipment_search_returns_no_results_for_unmatched_terms(): void
    {
        $user = User::factory()->create();
        Equipment::factory()->create();

        $this->actingAs($user)
            ->get(route('equipment.index', ['search' => 'not-found']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('equipment/Index')
                ->has('equipment.data', 0));
    }
}
