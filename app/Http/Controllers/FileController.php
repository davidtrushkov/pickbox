<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Obj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function index(Request $request) {
        // Grab the "object" where the team ID is the current team ID of user ["forCurrentTeam()" coming from Obj model]
        // Eager load "with('children.objectable', 'ancestorsAndSelf.objectable')"
        // Select from the "objects" table where `parent_id` is NULL
        // In other words, whenever we navigate to /files in browser, grab the root folders where the `parent_id` in database in NULL

        // `breadthFirst(), ancestorsAndSelf()` is coming from package --> "https://github.com/staudenmeir/laravel-adjacency-list"
        // `breadthFirst()` ---> The trait provides query scopes to order models breadth-first or depth-first:
        // `ancestorsAndSelf()` ---> The model's recursive parents and itself.
        $object = Obj::with('children.objectable', 'ancestorsAndSelf.objectable')->forCurrentTeam()->where(
            'uuid', $request->get('uuid', Obj::forCurrentTeam()->whereNull('parent_id')->first()->uuid))->firstOrFail();

        return view('files', [
            'object' => $object,
            'ancestors' => $object->ancestorsAndSelf()->breadthFirst()->get()
        ]);
    }


    // Let users download files
    public function download(File $file) {
        // authorize request
        $this->authorize('download', $file);

        return Storage::disk('local')->download($file->path, $file->name);
    }
}
