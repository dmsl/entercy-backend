<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Question;
use App\Possibleanswer;
use App\Useranswer;

class QuestionsController extends Controller
{

    public function api_getquestionsanswers(Request $request)
    {
      //TO DO: REMOVE THE IF STATEMENT AND PUT THIS :  $questions= DB::table('questions')->orderBy('order_num', 'asc')->get();
        //$over25=$request->input('over25');

        //if ($over25==True)
        //{
          $questions= DB::table('questions')->where('id','>', 25)->orderBy('order_num', 'asc')->get();
        //}
        //else 
        //{
        //  $questions= DB::table('questions')->where('id','<', 26)->orderBy('order_num', 'asc')->get();
        //}
        
        $array =[];

        foreach($questions as $question)
        {
            $possibleanswers=Possibleanswer::where('question_id', $question->id)->orderBy('order_num', 'asc')->get();
            $array[sizeof($array)] = array('question'=> $question,'answers' => $possibleanswers);
        }

        return response(['status' => 'success', 'data' => $array]);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   //this shows all questions. However ids<25 are not display in production apps
        $questions=Question::get();
        return view('questions.index')->with('questions',$questions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
        'question_en' => 'required',
        'type' => 'required',
        'compulsory' => 'required'
      ]);

      //create POI
      $questions = new Question;
      $questions->question_en=$request->input('question_en');
      $questions->question_gr=$request->input('question_gr');
      $questions->question_ru=$request->input('question_ru');
      $questions->question_it=$request->input('question_it');
      $questions->question_fr=$request->input('question_fr');
      $questions->question_ge=$request->input('question_ge');
      $questions->type=$request->input('type');
      $questions->order_num=$request->input('order_num');
      $questions->compulsory=$request->input('compulsory');
      $questions->save();
      return redirect('/questions')->with('success', 'Question created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $questions= Question::find($id);
        $possibleanswers=Possibleanswer::where('question_id', $id)->get();

        return view('questions.show')->with('questions',$questions)->with('possibleanswers',$possibleanswers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $questions= Question::find($id);
        return view('questions.edit')->with('questions', $questions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
        'question_en' => 'required',
        'type' => 'required',
        'compulsory' => 'required'
      ]);

      $questions = Question::find($id);
      $questions->question_en=$request->input('question_en');
      $questions->question_gr=$request->input('question_gr');
      $questions->question_ru=$request->input('question_ru');
      $questions->question_it=$request->input('question_it');
      $questions->question_fr=$request->input('question_fr');
      $questions->question_ge=$request->input('question_ge');
      $questions->type=$request->input('type');
      $questions->order_num=$request->input('order_num');
      $questions->compulsory=$request->input('compulsory');
      $questions->save();
      return redirect('/questions')->with('success', 'Question updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Question::where('id', $id)->delete();
         Possibleanswer::where('question_id', $id)->delete();
         Useranswer::where('question_id', $id)->delete();
         return redirect('/questions')->with('success', 'Question removed!');
    }
}
