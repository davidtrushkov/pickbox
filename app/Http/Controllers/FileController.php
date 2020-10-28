<?php

namespace App\Http\Controllers;

use App\Models\Obj;
use Illuminate\Http\Request;

class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function index(Request $request) {
        // Grab the "object" where the team ID is the current team ID of user ["forCurrentTeam()" coming from Obj model], then grab the uuid from URl
        // Select from the "objects" table where `parent_id` is NULL
        // In other words, whenever we navigate to /files in browser, default to the root folder
        $object = Obj::forCurrentTeam()->where(
            'uuid', $request->get('uuid', Obj::forCurrentTeam()->whereNull('parent_id')->first()->uuid))->firstOrFail();

           // dd($object->children);
        return view('files', [
            'object' => $object
        ]);
    }
}
