<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Resources\CityResource;

class CityController extends Controller
{
   public function index(){
    $cities=City::all();
    return CityResource::collection($cities);

    
   }
}
