<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Student;
use App\GroupStudent;
use App\Group;
use App\Document;
class StudentController extends Controller
{   
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('role:STUDENT');
    // }
    //
    public function addScheduelContent()
    {   
        $user_id = Auth::id();
        
 
        return view('pages.listProject');
    }

    public function projectDetail($group_id) {

    }

    public function getDocument($group_id) {
        $document = DB::table('group')->join('document', 'group.id', '=', 'document.id_group')
                        ->select("document.path, document.evaluate, document.user_upload, document.created_at")
                        ->where('group.id', '=', $group_id)->get();
        return response()->json(array('document'=> $document), 200);
    } 
}
