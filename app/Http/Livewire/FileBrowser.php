<?php

namespace App\Http\Livewire;

use App\Models\Obj;
use Livewire\Component;

class FileBrowser extends Component
{

    public $object;

    public $ancestors;

    public $creatingNewFolder = false;

    public $newFolderState = [
        'name' => ''
    ];
    
    public $renamingObject;
    public $renamingObjectState;
    

    // Rename an "object", (folder or file) and save to database, then reset and refresh form
    public function renameObject() {
        $this->validate([
            'renamingObjectState.name' => 'required|max:255'
        ]);

        Obj::forCurrentTeam()->find($this->renamingObject)->objectable->update($this->renamingObjectState);

        $this->object = $this->object->fresh();

        $this->renamingObject = null;
    }


    // When "renamingObject" is changed, we call this method. Set a listiner where we fetch the file/folder name from database into 
    // the input field where it will auto fill when clicking "Rename" button
    public function updatingRenamingObject($id) {

       if($id === null) {
           return;
       }

       if($object = Obj::forCurrentTeam()->find($id)) {
          $this->renamingObjectState = [
                'name' => $object->objectable->name
          ];
       }
    }


    public function createFolder() {
        $this->validate([
            'newFolderState.name' => 'required|max:255'
        ]);

        // Get the current users team with its "objects", then make a `parent_id` from the URl ID
        $object = $this->currentTeam->objects()->make([
            'parent_id' => $this->object->id
        ]);

        $object->objectable()->associate($this->currentTeam->folders()->create($this->newFolderState));

        $object->save();

        $this->creatingNewFolder = false;

        $this->newFolderState = [
            'name' => ''
        ];

        // Refresh all children
        $this->object = $this->object->fresh();
    }


    public function getCurrentTeamProperty() {
        return auth()->user()->currentTeam;
    }

    public function render()
    {
        return view('livewire.file-browser');
    }
}
