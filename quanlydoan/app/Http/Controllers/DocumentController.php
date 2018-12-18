<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Student;
use App\GroupStudent;
use App\Group;
use App\Document;
use DateTime;
use Validator;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class DocumentController extends Controller
{   

    public function getDocument($id_group) {
        $semester = Group::find($id_group)->semester;
        $subject = Group::find($id_group)->id_subject;

        $studentDocuments = DB::table('group')->join('document', 'group.id_group', '=', 'document.id_group')->join('user','document.user_upload','=','user.username')
                        ->select('document.id_document','document.type','document.path', 'document.evaluate', 'user.full_name', 'document.created_at')
                        ->where('group.id_group', '=', $id_group)->where('user.position','=',1)->get();

        $teacherDocuments = DB::table('group')->join('document', 'group.id_group', '=', 'document.id_group')->join('user','document.user_upload','=','user.username')
                        ->select('document.id_document','document.type','document.path', 'document.evaluate', 'user.full_name', 'document.created_at')
                        ->where('group.id_group', '=', $id_group)->where('user.position','=',2)->get();  
        $require_sub = DB::table('subject')->join('subject_scheduel', 'subject.id_subject', '=', 'subject_scheduel.id_subject')
                ->join('content_sub_scheduel', 'subject_scheduel.id_subject_scheduel', '=', 'content_sub_scheduel.id_subject_scheduel')
                ->select('content_sub_scheduel.require')
                ->where('subject_scheduel.semester', '=', $semester)
                ->where('subject.id_subject', '=', $subject);

        $requires = DB::table('group')->join('group_scheduel','group.id_group','=','group_scheduel.id_group')
                   ->join('content_group_scheduel','group_scheduel.id_scheduel','=','content_group_scheduel.id_scheduel')
                   ->select('content_group_scheduel.require')
                   ->where('group.id_group', '=', $id_group)
                   ->union($require_sub)->get();
                        
        $project = DB::table('group')->join('teacher', 'group.id_teacher', '=', 'teacher.id_teacher')
                        ->join('user', 'user.username', '=', 'teacher.username')
                        ->select('group.*', 'user.full_name')
                        ->where('group.id_group', '=', $id_group)->first();
                        #print($project);
        if(Auth::user()->position==1)                
           return view('student.document', ['requires'=>$requires,'project'=>$project,'studentDocuments'=> $studentDocuments,'teacherDocuments'=> $teacherDocuments]);
        elseif(Auth::user()->position==2)                
         return view('teacher.document', ['project'=>$project,'studentDocuments'=> $studentDocuments,'teacherDocuments'=> $teacherDocuments]);
    } 

    public function uploadFile(Request $request,$id_group) {
        $group=DB::table('group')->join('subject','group.id_subject', '=','subject.id_subject')->select('group.*','subject.subject_name')->where('group.id_group', '=', $id_group)->first();
        #$group=Group::find($id_group)->first();
       
        if(Auth::user()->position==1){
           $path = $group->semester.'/'.$group->subject_name.'/'.$id_group.'/student/';
           $destination_path = storage_path().'/'.'app/'.$group->semester.'/'.$group->subject_name.'/'.$id_group.'/student';
        }
        elseif(Auth::user()->position==2){
           $path = $group->semester.'/'.$group->subject_name.'/'.$id_group.'/teacher/';
           $destination_path = storage_path().'/'.'app/'.$group->semester.'/'.$group->subject_name.'/'.$id_group.'/teacher';
        }
      
      
        $file = $request->file('file');
       if( $file == null )
       {

        Session::flash('thongbao','Chưa thực hiện chọn vào file' );
        if(Auth::user()->position==1)
            return redirect('/student/project/'.$id_group.'/document');
        elseif(Auth::user()->position==2)
            return redirect('/teacher/project/'.$id_group.'/document');
        
       }
        $validator = Validator::make(
            #private $document_ext = ['doc', 'docx', 'pdf', 'odt'];
                        [
                            'file' => $file,
                            'extension' => Str::lower($file->getClientOriginalExtension()),
                        ],
                        [
                            'file' => 'required|max:10240',
                            'extension' => 'required|in: doc,docx,odt,pdf,pptx,xlsx,xls,csv,zip,rar'
                        ],
                        [
                                                      
                            'file.max'      => 'File upload kích thước nhỏ hơn 10 MB',
                            'extension.in' => 'Không phải định dạng cho phép upload',
                            
                        ]
                    );

            if($validator->passes()){
                $filename = $file->getClientOriginalName();
                $filenamenoext = pathinfo($filename, PATHINFO_FILENAME);
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                

                $count = 0;
                #$document=Document::where('path',$path.$filename)->get();
                while(Document::where('path',$path.$filename)->get()->count() > 0){
                    $count++;
                    $filename = $filenamenoext.'('.$count.').'.$extension;

                }
                $upload_success = $file->move($destination_path, $filename);
                if ($upload_success) {
                        
                        $document = new Document();
                        $document->id_group = $id_group;
                        $document->path = $path.$filename;
                  
                        $document->user_upload =Auth::user()->username;
                        if(Auth::user()->position==1)
                            $document->type = $request->Input('type');
                        elseif(Auth::user()->position==2)
                            $document->type = 'TLHD';
                        $document->evaluate =0.0;
                        $document->created_at = new DateTime();
                        $document->save();
                        Session::flash('thanhcong','Upload file thành công' );
                        if(Auth::user()->position==1)
                            return redirect('/student/project/'.$id_group.'/document');
                        elseif(Auth::user()->position==2)
                            return redirect('/teacher/project/'.$id_group.'/document');
                                        
                }
            }else{
               
                return back()->withErrors($validator)->withInput();
            
        }
            }
    

    public function downloadFile($id_group,$id_document)
    {  
        $document = Document::where('id_document',$id_document)->first();
        #$file_path = storage_path('') . "/" . $filename;
        return Response::download(storage_path('app/'.$document->path));
    }

    
    public function deleteFile($id_group,$id_document){
        $document = Document::where('id_document',$id_document)->first();
        
        $result = Storage::delete($document->path);
        Document::where('id_document',$id_document)->delete();
        if(Auth::user()->position==1)
            return redirect('/student/project/'.$id_group.'/document');
        elseif(Auth::user()->position==2)
            return redirect('/teacher/project/'.$id_group.'/document');

        }
    public function evaluateFile($id_group,$id_document,$point){
        #if($point>10)  {print("Error"); return;}
        $validator = Validator::make(
            #private $document_ext = ['doc', 'docx', 'pdf', 'odt'];
                        [
                            'evaluate' => $point,
                            
                        ],
                        [
                            'evaluate' =>'required|numeric|between:0,10',
                        ],
                        [
                                                      
                            
                            'evaluate.between' => 'Điểm đánh giá cần trong khoảng 0 đến 10',
                            
                        ]
                    );
        print( " Thực hiện update");
        if($validator->passes()){
            $document = Document::where('id_document',$id_document)->first();
            $document->evaluate =$point;
            $document->save();
            return redirect('/teacher/project/'.$id_group.'/document');

        }else{
            Session::flash('messenger','Điểm đánh giá cần trong khoảng 0 đến 10' );
            return redirect('/teacher/project/'.$id_group.'/document');
          
        }


    }
}
