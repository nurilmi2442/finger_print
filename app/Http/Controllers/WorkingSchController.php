<?php

namespace App\Http\Controllers;
use App\Models\Masterunit;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkingSchController extends Controller
{
    public function pageWorkingsch(Request $request)
    {
            $project = $request->query('unit');

            $masterunit = Masterunit::all();

            if($project){
                $masterunit = $masterunit->where('id', '=', $project);
            }

            return Inertia::render('Finger/Schedulemaster', [
                'masterunit' => $masterunit,
            ]);

    }
}
