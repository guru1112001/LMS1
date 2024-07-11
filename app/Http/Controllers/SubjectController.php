<?php

namespace App\Http\Controllers;


use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function showFolders(Subject $subject)
    {
        $folders = $subject->folders;
        return view('folders.index', compact('folders'));
    }
}
