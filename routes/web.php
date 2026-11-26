<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::get('people', [PersonController::class, 'index'])->name('people.index');
Route::post('people', [PersonController::class, 'store'])->name('people.store');
Route::patch('people/{person}/fields/{field}', [PersonController::class, 'updateField'])
    ->whereIn('field', ['name', 'phone', 'email', 'age'])
    ->name('people.field.update');
Route::get('equipment', [EquipmentController::class, 'index'])->name('equipment.index');
Route::post('equipment', [EquipmentController::class, 'store'])->name('equipment.store');
Route::patch('equipment/{equipment}/responsible-person', [EquipmentController::class, 'updateResponsiblePerson'])
    ->name('equipment.responsible.update');
Route::patch('equipment/{equipment}/fields/{field}', [EquipmentController::class, 'updateField'])
    ->whereIn('field', ['name', 'brand', 'condition_description'])
    ->name('equipment.field.update');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'equipmentUrl' => route('equipment.index', absolute: false),
        'peopleUrl' => route('people.index', absolute: false),
    ]);
})->name('home');

require __DIR__.'/settings.php';
