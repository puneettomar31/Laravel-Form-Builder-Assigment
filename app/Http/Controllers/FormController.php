<?php

namespace App\Http\Controllers;

use App\Jobs\RunAiFormGeneration;
use App\Models\AiTask;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::latest()->paginate(12);

        return view('forms.index', compact('forms'));
    }

    public function create()
    {
        return view('forms.create');
    }

    public function edit(Form $form)
    {
        return view('forms.edit', compact('form'));
    }

    public function fill(string $publicUuid)
    {
        $form = Form::where('public_uuid', $publicUuid)
            ->where('status', 'published')
            ->firstOrFail();

        return view('forms.fill', compact('form'));
    }

    public function ai()
    {
        $forms = Form::orderBy('title')->get();
        $tasks = AiTask::latest()->paginate(10);

        return view('forms.ai', compact('forms', 'tasks'));
    }

    public function generateAi(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1200',
            'form_id' => 'nullable|exists:forms,id',
        ]);

        $task = AiTask::create([
            'form_id' => $request->input('form_id'),
            'action' => 'generate',
            'prompt' => $request->input('prompt'),
            'status' => 'pending',
        ]);

        RunAiFormGeneration::dispatch($task);

        return redirect()->route('forms.ai')->with('message', 'AI generation queued.');
    }
}
