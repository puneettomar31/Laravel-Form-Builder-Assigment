<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FormController::class, 'index'])->name('forms.index');
Route::get('/forms/create', [FormController::class, 'create'])->name('forms.create');
Route::get('/forms/{form}/edit', [FormController::class, 'edit'])->name('forms.edit');
Route::get('/forms/{publicUuid}', [FormController::class, 'fill'])->name('forms.fill');
Route::post('/forms/{publicUuid}', [FormSubmissionController::class, 'submit'])->name('forms.submit');
Route::get('/forms/{form}/submissions', [FormSubmissionController::class, 'submissions'])->name('forms.submissions');
Route::get('/forms/{form}/submissions/export', [FormSubmissionController::class, 'export'])->name('forms.submissions.export');

Route::get('/import', [ImportController::class, 'index'])->name('forms.import');
Route::post('/import/preview', [ImportController::class, 'preview'])->name('forms.import.preview');

Route::get('/ai', [FormController::class, 'ai'])->name('forms.ai');
Route::post('/ai/generate', [FormController::class, 'generateAi'])->name('forms.ai.generate');
