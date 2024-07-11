<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qualification;
use App\Http\Resources\QualificationResource;

class QualificationController extends Controller
{
    public function index()
    {
        $qualifications=Qualification::all();
        return QualificationResource::collection($qualifications);
    }
}
