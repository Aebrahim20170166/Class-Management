<?php

/*
 * Author : Alaa Ibrahim
 * */
namespace App\Http\Controllers\Quiz;
session_start();

use App\Http\Controllers\Choice\ChoiceController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\K_Means\KmeansController;
use App\Http\Controllers\Question\QuestionController;
use App\Http\Controllers\Traits\requestTrait;
use App\Models\Choice;
use App\Models\Grade;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\StudentCourses;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /*Create new quiz*/
    public function createQuiz(Request $request){
//        return $request;
        $courseID = $request->courseID;
        $topic = $request->quizTopic;
        $questionCount = $request -> questionsCount;
        $totalGrade = 0;
        $duration = $request->quizDuration;
        for ($i=1; $i<=$questionCount; $i++){
            $questionGrade = 'questionGrade'.$i;
            $totalGrade += (int)$request->$questionGrade;
        }
//        return $totalGrade;
        $quizID = $this->saveQuiz($courseID,$topic,$totalGrade,$duration);
        //self::addStudentsToGradesTable($courseID,$quizID);

        $request->merge(['quizID'=> $quizID]);
        QuestionController::saveQuestions($request);
       $QuizCount= Quiz::query()->select('id')
            ->where('courseID','=',$courseID)
            ->count();
        $studentIDs=StudentCourses::query()->select('student_id')
            ->where('course_id','=',$courseID)
            ->count();
        $gradesData=Grade::query()->select('student_id','grade')
            ->where('course_id','=',$courseID)
            ->count();
        if($studentIDs > 10 and $gradesData !=0) {
            if ($QuizCount == 6) {
                (KmeansController::kMeansquiz($courseID));
            }
        }

        return redirect()->route('showQuizes',['courseID'=> $courseID]);
    }

    public static function addStudentsToGradesTable($courseID,$quizID){
        $students = StudentCourses::query()
            ->select('student_id')
            ->where('course_id','=',$courseID)
            ->get();

        foreach ($students as $student){
            Grade::create([
                'student_id' => $student->student_id,
                'course_id' => $courseID,
                'quiz_id' => $quizID,
                'grade' => 0
            ]);
        }
    }

    public function saveQuiz($courseID,$topic,$totalGrade,$duration){
        $date=date('Y-m-d H:i:s');
        $quizID= Quiz::insertGetId([
            'courseID' => $courseID,
            'topic' => $topic,
            'total_grade' => $totalGrade,
            'duration' =>$duration,
            'date'=>$date
        ]);
        return $quizID;
    }

    public static function quizCorrection(Request $request){
        $requestTime = date('Y-m-d H:i:s');
        $requestTime = Carbon::parse($requestTime);
        $quiz = Quiz::query()
            ->select('publishedTime','duration')
            ->where('id',$request->quizID)
            ->get();

        $publishedTime = $quiz[0]->publishedTime;
        $publishedTime =  Carbon::parse($publishedTime);
        $duration = $requestTime->diffInMinutes($publishedTime);

        if($duration > $quiz[0]->duration){
            Grade::create(['student_id'=>$request->studentID,'quiz_id'=>$request->quizID,'course_id'=>$request->courseID,
                'grade'=>0]);
            return json_encode('late submission');
        }
        else{
            $questionIDS=$request->questionIDList;
            $answers=$request->answersList;
            $grade=0;
            for($i=0;$i<$request->count;$i++)
            {
                //return $questionIDS[$i];
                //return self::checkAnswer($request->quizID,$answers[$i],$questionIDS[$i]);
                $grade += self::checkAnswer($request->quizID,$answers[$i],$questionIDS[$i])['grade'];
            }
            if(Grade::create(['student_id'=>$request->studentID,'quiz_id'=>$request->quizID,'course_id'=>$request->courseID,
                'grade'=>$grade]))
            {
                return json_encode('Done');
            }
            else{
                json_encode('Error');
            }
        }

        //return $request->questionIDList;

    }

    public static function checkAnswer($quizID,$indicator,$questionID){
        return Question::query()
            ->select('grade')
            ->where('id', '=', $questionID)
            ->where('quiz_id','=',$quizID)
            ->where('answer_id','=',$indicator)
            ->first();
    }

    public static function getQuizzesGrades(Request $request)
    {
        return json_encode(Grade::query()->join('quiz','quiz.id','=','grades.quiz_id')
            ->where('grades.student_id','=',$request->studentID)
            ->where('grades.course_id','=',$request->courseID)
            ->select('grades.grade','quiz.topic','quiz.total_grade')
            ->get());
    }
    /*Delete quiz*/
    /*public function deleteQuiz(Request $request){
        $result=Quiz::query()
            ->where('courseID','=',$request->courseID)
            ->where('id','=',$request->ID)
            ->delete();
        if($result){
            return 'Quiz deleted successfully';
        }
        return 'Error ,quiz not deleted';
    }*/
    public static function getTopicsOfQuizzes(Request $request)
    {
        $result=Quiz::query()
            ->select('id','topic','date')
            ->where('courseID','=',$request->courseID)
            ->where('status','=',1)
            ->get();
        return json_encode($result);
    }

    public static function getQuestionsandAnswersOfQuiz(Request $request)
    {
        //this array have arrays each one has question with its answers
        $allQuestionsWithThieranswers=array();
        //count number of questions in quiz
        $countOFQuestions=1;
        $questions=QuestionController::getQuestions($request->quizID);
        foreach ($questions as $question)
        {
            $countOFAnswers=1;
            $questionWithAnswers=array();
            $questionWithAnswers['content']=$question->content;
            $questionWithAnswers['id']=$question->id;
            $answers=ChoiceController::getChoices($question->id);
            foreach ($answers as $answer) {
                $questionWithAnswers['answer'.$countOFAnswers++]=$answer->content;
            }
            $allQuestionsWithThieranswers['question'.$countOFQuestions++]=$questionWithAnswers;

        }
        if($request->wantsJson())
        {
            return json_encode($allQuestionsWithThieranswers);
        }
        return $allQuestionsWithThieranswers;

    }
    //mohammed part
    public function showQuizes($courseID){

        $quizes = Quiz::query()
            ->where('courseID' , '=' , "{$courseID}")
            ->get();



        return view('quiz/quizzes',[ 'quizes' =>$quizes]);
    }

    public function publishQuiz(Request $request){
        $publishedTime = date('Y-m-d H:i:s');
        Quiz::where('id',$request->quizID)
            ->update(['status' => 1,'publishedTime' => $publishedTime]);

        return redirect()->route('showQuizes',['courseID' => $request->courseID]);

        $publishedTime = Carbon::parse($publishedTime);
        $creationTime = Quiz::query()
            ->select('date')
            ->where('id',$request->quizID)
            ->get();

        $creationTime =  Carbon::parse($creationTime[0]->date);
        $duration = $publishedTime->diffInMinutes($creationTime);
        return $duration;

    }

    public function showQuiz($quizID){
        $questions = Question::query()
            ->where('quiz_id', '=', "{$quizID}")
            ->get();
        $allQuestions = [];
        $i = 0;
        foreach ($questions as $question) {
            $questionWithAnswer = [];
            $options = Choice::query()
                ->where('question_id', '=', "{$question->id}")
                ->get();
            $questionWithAnswer['question'] =$question->content;
            $questionWithAnswer['questionid'] =$question->id;
            $questionWithAnswer['correctAnswer'] =$question->answer;
            $questionWithAnswer['correctAnswerID'] =$question->answer_id;
            $questionWithAnswer['questionGrade'] = $question->grade;
            $j = 1;
            foreach ($options as $option){
                $questionWithAnswer['option'.$j] = $option->content;
                $questionWithAnswer['optionindicator'.$j] = $option->indicator;
                $questionWithAnswer['optionid'.$j++] = $option->id;
            }
            $questionWithAnswer['optionsCount'] = $j-1;
            $allQuestions[$i++] = $questionWithAnswer;
        }
//        return $allQuestions;
        return view("quiz/editQuiz",['questions'=>$allQuestions, 'quizID'=>$quizID]);
    }

    //show quiz function for api

    public function showQuizApi(Request $request){
        $allQuestions =array();
        $record=Quiz::query()->where('id','=',$request->quizID)
            ->select('status')->first();
        //return $record;
        if($record['status']==1) {
            $questions = Question::query()
                ->where('quiz_id', '=', $request->quizID)
                ->get();
            foreach ($questions as $question) {
                /** @var TYPE_NAME $questionWithAnswer */
                $questionWithAnswer = array();

                $options = Choice::query()
                    ->where('question_id', '=', "{$question->id}")
                    ->get();
                $questionWithAnswer['question'] = $question->content;
                $questionWithAnswer['questionID'] = $question->id;
                $questionWithAnswer['correctAnswer'] = $question->answer;
                /** @var TYPE_NAME $options_of_questions */
                $options_of_questions = [];
                foreach ($options as $option) {
                    $options_of_questions[] = $option->content;

                }
                $questionWithAnswer['options'] = $options_of_questions;
                $allQuestions[] = $questionWithAnswer;

            }
            if ($request->wantsJson()) {
                return json_encode($allQuestions);
            }
            return view("staff/editQuiz", ['questions' => $allQuestions]);
        }else{
            if ($request->wantsJson()) {
                return json_encode($allQuestions);
            }
            //return view("staff/editQuiz", ['questions' => $allQuestions]);
        }
    }

    public static function getQuizzesOfCourse($courseID){
        return Quiz::query()
            ->select('id','topic')
            ->where('courseID','=',$courseID)
            ->get();
    }

    public static function getAvgGradeForQuiz($quizzes){
        $allQuizzesWithAvgGrad = [];
        foreach ($quizzes as $quiz){
            $quizAvgGrade = Grade::query()
                ->where('quiz_id','=',$quiz->id)
                ->average('grade');
            array_push($allQuizzesWithAvgGrad,$quiz->topic,$quizAvgGrade);
        }
        return $allQuizzesWithAvgGrad;
    }


}
