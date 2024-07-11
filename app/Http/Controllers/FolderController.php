<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use App\Models\Subject;

class FolderController extends Controller
{
    public function showContents(Folder $folder,Subject $subject)
    {   $folders=$subject->folders;
        $contents = $folder->contents;
        return view('contents.index', compact('folders','contents'));
    }
    public function show(Folder $folder)
    {
        // $folder = Folder::findOrFail($folderId);
        $contents = $folder->contents; // Assuming you have a relationship set up

        return view('folders.show', compact('folder', 'contents'));
    }
    public function destroy(Folder $folder)
    {
        $folder->delete();
        
        return redirect()->back()->with('success', 'Folder deleted successfully');
    }
}
