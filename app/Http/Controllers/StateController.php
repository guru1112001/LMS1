<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Resources\StateResource;

class StateController extends Controller
{
    public function index(){
        $states=State::all();
        return StateResource::collection($states);
        
    }
}
