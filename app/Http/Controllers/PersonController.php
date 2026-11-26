<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($filters['search'] ?? '');

        $people = Person::query()
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->whereLike('name', "%{$search}%")
                        ->orWhereLike('phone', "%{$search}%")
                        ->orWhereLike('email', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('people/Index', [
            'people' => $people,
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'age' => ['required', 'integer', 'min:0'],
        ]);

        Person::query()->create($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Pessoa cadastrada com sucesso.']);

        return to_route('people.index');
    }

    public function updateField(Request $request, Person $person, string $field): RedirectResponse
    {
        $rules = match ($field) {
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'age' => ['required', 'integer', 'min:0'],
            default => abort(404),
        };
        $validated = $request->validate(['value' => $rules]);

        $person->update([$field => $validated['value']]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Pessoa atualizada com sucesso.']);

        return back();
    }
}
