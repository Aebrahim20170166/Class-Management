<?php

namespace App\Http\Controllers\Prediction;

use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Session\SessionController;
use App\Models\Grade;
use App\Models\Quiz;
use App\Models\StudentResult;
use App\Models\TrainingData;
use Illuminate\Http\Request;
use Phpml\Regression;
use Phpml\Helper\Predictable;
use Phpml\Math\Matrix;

use function Sodium\randombytes_uniform;

class PredictionController extends Controller
{

    public static function checkprediction(Request $request)
    {
        if(!SessionController::checkNoSessions($request->courseID))
        {
            $response="Early";
            return json_encode($response);
        }
        else{
            $response='Done';
            return json_encode($response);
        }
    }
    public static function buildRegressionModel()
    {
        $samples=array();
        $targets=array();
        $trainingDataRecords=TrainingData::all();
        foreach($trainingDataRecords as $record)
        {
            $sample[]=[$record->quizzesAvg,$record->absence];
            $samples=$sample;
            $targets[]=$record->final_grade;
        }

        $regression = new Regression\LeastSquares();
        $regression->train($samples, $targets);
        return $regression;
    }
    //this function will take request from flutter
    /*public static function predictFinalGrades(Request $request)
    {
        $courseID=$request->courseID;
        $studentID=$request->studentID;
        //$studentID=20170001;
        $quizzes=Quiz::query()->where('courseID','=',$courseID)
            ->select('id')->get();

        $count=0;
        $totalGrade=0;
        foreach($quizzes as $quiz)
        {
            //echo $quiz;
            $record=Grade::query()->select('grade')
                ->where('student_id','=',$studentID)
                ->where('course_id','=',$courseID)
                ->where('quiz_id','=',$quiz->id)
                ->first();
            //echo $record['grade'];
            $totalGrade+=$record['grade'];
            $count++;
        }

        $quizzesAvg=$totalGrade/$count;
        $absence=AttendanceController::getAbsenceOfStudentInCourse($courseID,$studentID);
        $record=[$quizzesAvg,$absence];
        $regression=self::buildRegressionModel();
        $predicted_grade=$regression->predict($record);
        $grade='';
        if($predicted_grade<30)
            $grade="F";
        else if($predicted_grade>30 && $predicted_grade<37)
            $grade="D";
        else if($predicted_grade>37 && $predicted_grade<45)
            $grade="C";
        else if($predicted_grade>45 && $predicted_grade<52)
            $grade="B";
        else
            $grade="A";

        return json_encode($grade);
    }*/
    public static function predictFinalGrades(Request $request)
    {
        $record=self::getDataFromDB($request);

        $regression=self::buildRegressionModel();
        $predicted_grade=$regression->predict($record);
        //return $predicted_grade;
        $quizzes=array();
        if(ceil($predicted_grade)<45)
        {
            return $quizzes=self::getQuizzesGrades($request);
        }
        else
            return json_encode($quizzes);
    }
    public static function getQuizzesGrades(Request $request)
    {
        $results=Grade::query()->join('quiz','quiz.id','=','grades.quiz_id')
            ->where('grades.student_id','=',$request->studentID)
            ->where('grades.course_id','=',$request->courseID)
            ->where('grades.grade','<',7)
            ->select('quiz.topic','quiz.courseID')
            ->get();
        return $results;
    }
    public static function getDataFromDB(Request $request)
    {
        $courseID=$request->courseID;
        $studentID=$request->studentID;
        //$studentID=20170001;
        $quizzes=Quiz::query()->where('courseID','=',$courseID)
            ->where('quiz.status','=',1)
            ->select('id')->get();

        $count=0;
        $totalGrade=0;
        $variable = array();
        foreach($quizzes as $quiz)
        {
            //echo $quiz;
            $record=Grade::query()->join('quiz','quiz.id','=','grades.quiz_id')
                ->select('grades.grade','quiz.total_grade')
                ->where('grades.student_id','=',$studentID)
                ->where('grades.course_id','=',$courseID)
                ->where('grades.quiz_id','=',$quiz->id)
                ->first();
            //$variable[] = $record['total_grade'];
           $totalGrade+=($record['grade']/$record['total_grade'])*10;
            $count++;
            //break;
        }
        $quizzesAvg=$totalGrade/$count;
        $absence=AttendanceController::getAbsenceOfStudentInCourse($courseID,$studentID);
        $record=[$quizzesAvg,$absence];
        return $record;
        //return $count;
        //return $variable;
    }
    /*public static function predictFinalGrade()
    {
        $samples=array();
        $targets=array();
        for($id=20170001;$id<=20170400;$id++)
        {
            $results=StudentResult::query()->select('number_of_attendance','year_works','finalresult')
                ->where('student_id','=',$id)->first();
            //return $results;
            $sample[]=[$results->number_of_attendance,$results->year_works];
            $samples=$sample;
            $targets[]=$results->finalresult;
        }
        $regression = new Regression\LeastSquares();
        $regression->train($samples, $targets);
        $count=0;
        for($ID=20170401;$ID<=20170500;$ID++)
        {
            $results2=StudentResult::query()->select('number_of_attendance','year_works','finalresult')
                ->where('student_id','=',$ID)->first();
            //return $results;
            $sample2[]=[$results2->number_of_attendance,$results2->year_works];
            echo "Student ".$ID." can get ".$regression->predict([$results2->number_of_attendance,$results2->year_works])." final exam";
            echo "<br><br>";
            //$targets2[]=$results2->finalresult;
        }
        //return $predicted;
        //return $samples;
    }*/
}
