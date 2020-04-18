<?php

namespace App\Http\Controllers\API;

use App\Spaces;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpaceController extends Controller
{
    public function getSpaces(Request $request)
    {
        $space = new Spaces();
        // dd($request->all());
        return $space->getSpaces($request->lat, $request->lng, $request->rad)->get();
    }
}
