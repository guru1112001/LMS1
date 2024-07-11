<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;


class ContentController extends Controller
{
    
    public function preview($id)
    {
        $content = Content::findOrFail($id);

        // If the content is not published, you may want to handle permissions or display an error.
        if (!$content->published) {
            abort(404);
        }

        // You can pass the $content object to your view and handle the preview there.
        return view('contents.preview', compact('content'));
    }
    
}
