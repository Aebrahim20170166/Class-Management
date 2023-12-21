<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Session\SessionController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Quiz\QuizController;
use App\Http\Controllers\Question\QuestionController;
use App\Http\Controllers\Choice\ChoiceController;
use App\Http\Controllers\Prediction\PredictionController;
use App\Http\Controllers\Announcement\AnnouncementController;
use App\Http\Controllers\Teach\TeachController;
use App\Http\Controllers\Reports\report;
use App\Http\Controllers\MailController\MailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------quizChart
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['verify'=>true]);
Route::get('/', function () {

    return view('staff/FirstPage');
});
Route::post('createAccount','User\InstructorController@sign_up')->name("createAccount");
Route::view('signup','Registration.SignUp')->name('signup');
Route::post('validate','User\InstructorController@login')->name('validate');
Route::view('Login','Registration.Login')->name('Login');
Route::group(['middleware' => 'loggedin'],function (){

    Route::view('quizChart','charts.quizChart');
    Route::get('getDataForAttChart','Charts\AttendanceChart@prepareData')->name('getDataForAttChart');
    Route::get('attendanceChart','Charts\AttendanceChart@returnview')->name('attendanceChart');

    Route::get('getDataForQuizChart','Charts\QuizChart@prepareData')->name('getDataForQuizChart');
    Route::get('QuizChart','Charts\QuizChart@returnview')->name('quizChart');

    Route::view('chart','testChart');

    Route::get('home', function () {
        return view('staff/Home');
    });
    //Route:get('announcements', 'Announcement\AnnouncementController@getpost')->name('Announcements' ) ;



    //Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');



    Route::view('course','staff/course');
    //Route::get('/courseView/{courseID}','Course\CourseController@showCourse');
    Route::get('home','Teach\TeachController@getTeachedCourses')->name('home');
    Route::get('getEnrolledCourses','Teach\TeachController@getTeachedCourses')->name('getEnrolledCourses');

    Route::view('mainHome','staff.FirstPage')->name('mainHome');
    //validate quiz part


    //quiz part
    //!!

    Route::post('saveQuestion',[QuizController::class,'createQuiz'])->name('saveQuestion');
    Route::post('saveQuestion',[QuestionController::class,'saveQuestion'])->name('saveQuestion');
    Route::post('saveQuiz',[QuizController::class,'createQuiz'])->name('saveQuiz');
    Route::view('createQuiz','Quiz.createQuiz')->name('createQuiz');
    Route::view('addQuestion','Quiz.addQuestion')->name('addQuestion');
    Route::view('addAnswer','Quiz.addAnswer')->name('addAnswer');
    Route::get('/showQuizes/{courseID}','Quiz\QuizController@showQuizes')->name('showQuizes');
    Route::get('/showQuiz/{quizID}','Quiz\QuizController@showQuiz')->name('showQuiz');
    Route::view('createquiz','staff/quiz')->name('createquiz');
    Route::view('/createquiz/{courseID}','staff/quiz');
    Route::get('createQuiz/{courseID}',function ($courseID){
        return view('staff/quiz')->with('courseID',$courseID);
    })->name('createQuiz');
    Route::post('savequiz',[QuizController::class,'createQuiz'])->name('savequiz');
    Route::post('removeQuestion',[QuestionController::class,'destroy'])->name('removeQuestion');
    Route::get('deleteQuiz/{CourseID}',[QuizController::class,'deleteQuiz']);
    Route::post('saveNewQuestions',[QuestionController::class,'saveQuestions'])->name('saveNewQuestions');
    Route::post('removeChoice',[ChoiceController::class,'removeChoice'])->name('removeChoice');
    Route::post('addOption',[ChoiceController::class,'addChoice'])->name('addOption');
    Route::post('updatequestion', [QuestionController::class,'update'])->name('updateQuestion');
    Route::post('publishQuiz','Quiz\QuizController@publishQuiz')->name('publishQuiz');
    //Route::post('savequiz',[QuizController::class,'createQuiz'])->name('savequiz');

    //Session part
    Route::view('sessions','staff.sessions')->name('sessions');
    Route::view('newSession','staff.createNewSession')->name('newSession');
    Route::get('getSessions/{courseID}',[SessionController::class,'getSessionsOfCourse']);
    Route::view('createSession','staff.createSession')->name('createSession');
    Route::post('createSession','Session\SessionController@createSession')->name('create_session');
    Route::post('get_session','Session\SessionController@getSessionsOfCourse')->name('get_sessions');


    Route::view('QrCode','attendance/QrCode')->name('QrCode');

    Route::get("getannounce",'Announcement\AnnouncementController@getpost')->name('getpost');

    //Course part
    Route::get('/courseView/{courseID}',[CourseController::class,'showCourse'])->name('showCourse');
    Route::view('courses','course.Courses')->name('courses');
    Route::view('create_course','course.createCourse')->name('create_course');
    Route::post('addCourse',[CourseController::class,'createCourse'])->name('addCourse');
    Route::view('join_course','course/joinCourse')->name('join_course');
    Route::view('course','course/course');
    Route::get('getCourses/{studentID}',[CourseController::class,'getEnrolledCourses']);
    Route::post('joinCourse',[CourseController::class,'joinCourse'])->name('joinCourse');
    Route::post('delete_course',[CourseController::class,'deleteCourse'])->name('delete_course');
    Route::post('delete_instructor_course',[TeachController::class,'deleteInstructorCourse'])->name('delete_instructor_course');
    //Course\CourseController@showCourse
    //test

    Route::get('getAttendance',[AttendanceController::class,'getAttendanceOfSession'])->name('getAttendance');
    Route::get('getNumOfAbsents',[AttendanceController::class,'getNumOfAbsenceAndLecturesNamesِInCourse']);

    //AttendanceController
    //logout
    Route::get('/flush', function () {
        Session::flush();
        return redirect()->route('mainHome');
    })->name('logout');


    Route::view('/createquiz/{courseID}','quiz/quiz');
    Route::get('createQuiz/{courseID}',function ($courseID){
        return view('quiz/quiz')->with('courseID',$courseID);
    })->name('createQuiz');
    //Route::get('removeQuestion','Quiz\QuestionController@destroy')->name('removeQuestion');

    //generate data for predection
    Route::get('generateFinal',[PredictionController::class,'generateStudentResults']);
    Route::get('predict',[PredictionController::class,'predictFinalGrade']);
    //predictFinalGrade

    //last
    //Route::post('saveNewQuestions','Quiz\QuestionController@saveQuestions')->name('saveNewQuestions');

    //Route::post('removeQuestion','Quiz\QuestionController@destroy')->name('removeQuestion');
    //Route::post('removeChoice','Quiz\QuestionController@removeChoice')->name('removeChoice');
    //Route::post('addOption','Quiz\QuestionController@addOption')->name('addOption');

    //Route::post('updatequestion', 'Quiz\QuestionController@update')->name('updateQuestion');

    //predection

    Route::get('getAccuracy','Predection\PredectionController@getAccuracy');
    Route::get('predict','Predection\PredectionController@predictFinalGrades');
    Route::get('getData','K_Means\KmeansController@kMeansquiz');
    Route::get('getData','K_Means\KmeansController@readData');
    Route::get('update','Grade\GradeController@update');
    Route::get('generate','Grade\GradeController@generateAttendanceData');
    Route::get('addData','Grade\GradeController@addData');
    //Naive algorithm
    Route::get('Data','Naeve\NaeveController@naeve');

    //reports
    Route::get('attendancereport','Reports\report@attendancereport')->name('attendancereport');
    Route::get('quizreport','Reports\report@quizreport')->name('quizreport');

    //Announcement
    Route::post('announcements','Announcement\AnnouncementController@announcementsannouncements');
    Route::view('Announcements','announcement.makeAnnouncement')->name('announcements');
    Route::get("getannounce",'Announcement\AnnouncementController@getpost')->name('getpost');
    Route::post('makepost','Announcement\AnnouncementController@makepost')->name('makepost');
    Route::get('updatepost','Announcement\AnnouncementController@updatepost')->name('updatepost');
    Route::get('saveupdate','Announcement\AnnouncementController@saveupdate')->name('saveupdate');
    Route::get('deletepost','Announcement\AnnouncementController@deletepost')->name('deletepost');
    //Email
    Route::get('sendemail', 'MailController\MailController@mail')->name('sendemail');
    // prediction
    Route::post('kMeansquiz','K_Means\KmeansController@kMeansquiz')->name('kMeansquiz');

    Route::get('kMeansattendance','K_Means\KmeansController@kMeansattendance')->name('kMeansattendance');
    Route::get('naeve','Naeve\NaeveController@naeve')->name('naeve');
    Route::post('endSession','Session\SessionController@endSession')->name('endSession');
});
