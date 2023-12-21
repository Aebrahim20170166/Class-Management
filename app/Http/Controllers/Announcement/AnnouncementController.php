<?php
namespace App\Http\Controllers\Announcement;

use App\Models\Announcement;

use App\Http\Controllers\Controller;
use App\Models\StudentCourses;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{

    public static function announcements(){

    }

    public static function makepost(Request $request)
    {
//        return $request->announcement;
//        return "hi";
        $date = date('Y-m-d H:i:s');
//        if(strlen($request->announcement)<5)
//        {
//            $error='Announcement is empty or very small, please try again';
//            return view('announcement/makeAnnouncement',['error' => $error]);
//        }

        if(Announcement::create(['course_id' => $request->courseID, 'body' => $request->announcement,'date' => $date])) {
            $SERVER_API_KEY = 'AAAANbEvUEw:APA91bF0G8HwMWtKlh8fr2LAXOQn6QQGxNt_J1mD-Y53mEA8irBbxTVFsV8C5BOf3ZPwGXtqZmP46A156kgMixs7kfxZw-lTTPI-lKtdukN3ZweJkkL1VqgttFhcc-vaQREfuexWEnaj';
            $records=StudentCourses::query()->join('courses','courses.course_id','=',
                'studentcourses.course_id')
                ->select('studentcourses.token','courses.name')
                ->where('studentcourses.course_id','=',$request->courseID)
                ->get();
            $tokens= '';
            foreach ($records as $record)
            {
                $tokens=$record->token;
                $data = [

                    "registration_ids" => [
                        $tokens
                    ],

                    "notification" => [

                        "title" => $record->name,

                        "body" => $request->announcement,

                        "sound"=> "default" // required for sound on ios

                    ],

                ];

                $dataString = json_encode($data);

                $headers = [

                    'Authorization: key=' . $SERVER_API_KEY,

                    'Content-Type: application/json',

                ];

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

                curl_setopt($ch, CURLOPT_POST, true);

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

                $response = curl_exec($ch);

            }
            return redirect()->route('getpost',['courseID' => $request->courseID]);

//            return view('announcement/makeAnnouncement',['Announcements' => $Announcements]);
        }
//        $Announcements = Announcement::query()
//            ->where('course_id', '=', "$request->courseID")
//            ->get();
////        return "hi";
//        return view('announcement/makeAnnouncement',['Announcements' => $Announcements]);
    }

    public function updatepost(Request $request)
    {
        return view('announcement/updateAnnouncement', ['course_id' => $request->courseID, 'body' => $request->body, 'postid' => $request->postid]);
    }


    public function saveupdate(Request $request)
    {
        Announcement::where(['course_id'=>$request->courseID])
            ->where(['id'=>$request->postid])
            ->update([
                'body' => $request->body
            ]);
        $Announcements = Announcement::query()
            ->where([
                ['course_id', '=', $request->courseID]
            ])
            ->get();

        return redirect()->route('getpost',['courseID' => $request->courseID]);
    }


    public function deletepost(Request $request)
    {
        Announcement::where(['course_id'=>$request->courseID])
            ->where(['id'=>$request->postid])
            ->delete();
        $Announcements = Announcement::query()
            ->where([
                ['course_id', '=', $request->courseID]
            ])
            ->get();
        return redirect()->route('getpost',['courseID' => $request->courseID]);

    }
    public function getpost(Request $request){
        $Announcements = Announcement::query()
            ->where([
                ['course_id', '=', $request->courseID]
            ])
            ->get();
        return view('announcement/makeAnnouncement',['Announcements' => $Announcements,'courseID'=>$request->courseID]);
    }

    public function getAnnouncements(Request $request)
    {
        $announcements=Announcement::query()->select('body','date')
            ->where('course_id','=',$request->courseID)
            ->get();
        if($request->wantsJson())
            return json_encode($announcements);
    }

}
