<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Services\FormImportParser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function preview(Request $request, FormImportParser $parser)
    {
        $request->validate([
            'file' => 'required|file|mimes:docx,xlsx',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        $preview = [];
        if ($extension === 'docx') {
            $preview = $parser->parseDocx($file);
        }

        if ($extension === 'xlsx') {
            $preview = $parser->parseXlsx($file);
        }

        return view('import.preview', compact('preview'));
    }
}
