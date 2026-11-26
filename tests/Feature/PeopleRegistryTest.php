<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PeopleRegistryTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitors_can_access_the_people_registry(): void
    {
        $this->get(route('people.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('people/Index'));
    }

    public function test_visitors_can_register_people(): void
    {
        $this->post(route('people.store'), [
            'name' => 'Ana Oliveira',
            'phone' => '(71) 99999-1234',
            'email' => 'ana@example.com',
            'age' => 34,
        ])->assertRedirect(route('people.index'));

        $this->assertDatabaseHas('people', ['email' => 'ana@example.com']);
    }

    public function test_authenticated_users_can_register_people(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('people.store'), [
            'name' => 'Ana Oliveira',
            'phone' => '(71) 99999-1234',
            'email' => 'ana@example.com',
            'age' => 34,
        ]);

        $response->assertRedirect(route('people.index'));
        $this->assertDatabaseHas('people', [
            'name' => 'Ana Oliveira',
            'phone' => '(71) 99999-1234',
            'email' => 'ana@example.com',
            'age' => 34,
        ]);
    }

    public function test_person_fields_are_validated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('people.store'), [
                'name' => '',
                'phone' => '',
                'email' => 'not-an-email',
                'age' => -1,
            ])
            ->assertSessionHasErrors(['name', 'phone', 'email', 'age']);

        $this->assertDatabaseCount('people', 0);
    }

    public function test_visitors_can_update_each_person_field_without_changing_other_fields(): void
    {
        $person = Person::factory()->create([
            'name' => 'Ana Oliveira',
            'phone' => '(71) 99999-1234',
            'email' => 'ana@example.com',
            'age' => 34,
        ]);
        $expected = [
            'name' => 'Ana Oliveira',
            'phone' => '(71) 99999-1234',
            'email' => 'ana@example.com',
            'age' => 34,
        ];
        $updates = [
            'name' => 'Beatriz Oliveira',
            'phone' => '(71) 98888-4321',
            'email' => 'beatriz@example.com',
            'age' => 35,
        ];

        foreach ($updates as $field => $value) {
            $expected[$field] = $value;

            $this->from(route('people.index'))
                ->patch(route('people.field.update', [
                    'person' => $person,
                    'field' => $field,
                ]), ['value' => $value])
                ->assertRedirect(route('people.index'));

            $this->assertDatabaseHas('people', [
                'id' => $person->id,
                ...$expected,
            ]);
        }
    }

    public function test_invalid_person_field_updates_leave_saved_values_unchanged(): void
    {
        $person = Person::factory()->create([
            'name' => 'Ana Oliveira',
            'phone' => '(71) 99999-1234',
            'email' => 'ana@example.com',
            'age' => 34,
        ]);

        foreach (['email' => 'not-an-email', 'age' => -1] as $field => $value) {
            $this->from(route('people.index'))
                ->patch(route('people.field.update', [
                    'person' => $person,
                    'field' => $field,
                ]), ['value' => $value])
                ->assertRedirect(route('people.index'))
                ->assertSessionHasErrors('value');

            $this->assertDatabaseHas('people', [
                'id' => $person->id,
                'name' => 'Ana Oliveira',
                'phone' => '(71) 99999-1234',
                'email' => 'ana@example.com',
                'age' => 34,
            ]);
        }
    }

    public function test_people_can_be_searched_by_name_phone_and_email(): void
    {
        $user = User::factory()->create();
        $person = Person::factory()->create([
            'name' => 'Ana Oliveira',
            'phone' => '(71) 99999-1234',
            'email' => 'ana@example.com',
        ]);
        Person::factory()->create(['name' => 'Bruno Silva']);

        foreach (['ana oliv', '99999-1234', 'ANA@EXAMPLE.COM'] as $term) {
            $this->actingAs($user)
                ->get(route('people.index', ['search' => $term]))
                ->assertOk()
                ->assertInertia(fn (Assert $page) => $page
                    ->component('people/Index')
                    ->has('people.data', 1)
                    ->where('people.data.0.id', $person->id)
                    ->where('filters.search', $term));
        }
    }

    public function test_people_search_returns_no_results_for_unmatched_terms(): void
    {
        $user = User::factory()->create();
        Person::factory()->create();

        $this->actingAs($user)
            ->get(route('people.index', ['search' => 'not-found']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('people/Index')
                ->has('people.data', 0));
    }
}
