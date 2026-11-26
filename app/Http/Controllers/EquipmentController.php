<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($filters['search'] ?? '');

        $equipment = Equipment::query()
            ->with('responsiblePerson:id,name')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->whereLike('name', "%{$search}%")
                        ->orWhereLike('brand', "%{$search}%")
                        ->orWhereLike('condition_description', "%{$search}%")
                        ->orWhereHas('responsiblePerson', function (Builder $personQuery) use ($search): void {
                            $personQuery->whereLike('name', "%{$search}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('equipment/Index', [
            'equipment' => $equipment,
            'people' => Person::query()->orderBy('name')->get(['id', 'name']),
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'condition_description' => ['required', 'string'],
            'responsible_person_id' => ['nullable', 'integer', 'exists:people,id'],
        ]);

        Equipment::query()->create($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Equipamento cadastrado com sucesso.']);

        return to_route('equipment.index');
    }

    public function updateResponsiblePerson(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validate([
            'responsible_person_id' => ['nullable', 'integer', 'exists:people,id'],
        ]);

        $equipment->update($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Responsável atualizado com sucesso.']);

        return back();
    }

    public function updateField(Request $request, Equipment $equipment, string $field): RedirectResponse
    {
        $rules = match ($field) {
            'name', 'brand' => ['required', 'string', 'max:255'],
            'condition_description' => ['required', 'string'],
            default => abort(404),
        };
        $validated = $request->validate(['value' => $rules]);

        $equipment->update([$field => $validated['value']]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Equipamento atualizado com sucesso.']);

        return back();
    }
}
