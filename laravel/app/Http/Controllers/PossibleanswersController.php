<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Question;
use App\Possibleanswer;

class PossibleanswersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function create_possibleanswer($question_id)
    {
        return view('possibleanswers.create')->with('question_id', $question_id);
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
        'answer_en' => 'required',
        'question_id' => 'required',                
      ]);

      //create POI
      $possibleanswers = new Possibleanswer;
      $possibleanswers->answer_en=$request->input('answer_en');
      $possibleanswers->answer_gr=$request->input('answer_gr');
      $possibleanswers->answer_ru=$request->input('answer_ru');
      $possibleanswers->answer_it=$request->input('answer_it');
      $possibleanswers->answer_fr=$request->input('answer_fr');
      $possibleanswers->answer_ge=$request->input('answer_ge');
      $possibleanswers->order_num=$request->input('order_num');
      $possibleanswers->question_id=$request->input('question_id');  

      //handle video image upload
      if($request->hasFile('path_img'))
      {
        //get filename with the extension
        $filenameWithExt=$request->file('path_img')->getClientOriginalName();
        //get just $filename
        $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just the extension
        $extension= $request->file('path_img')->getClientOriginalExtension();
        //filename to store
        $fileNameToStore=$filename.'_'.time().'.'.$extension;
        //upload image
        $path= $request->file('path_img')->storeAs("public/media/PossibleAnswers/", $fileNameToStore);

        if ($possibleanswers->path_img!="")
        {
           Storage::delete('public/media/'.$possibleanswers->path_img);
        }

        $possibleanswers->path_img= "PossibleAnswers/" . $fileNameToStore;
      }

      $possibleanswers->save();

      $questions= Question::find($request->input('question_id')); 
      $possibleanswers=Possibleanswer::where('question_id', $request->input('question_id'))->get();       

      return view('questions.show')->with('questions',$questions)->with('possibleanswers',$possibleanswers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $possibleanswers= Possibleanswer::find($id);         
        return view('possibleanswers.show')->with('possibleanswers', $possibleanswers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $possibleanswers= Possibleanswer::find($id);             
        return view('possibleanswers.edit')->with('possibleanswers', $possibleanswers);
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
        'answer_en' => 'required',
        //'question_id' => 'required',                
      ]);

      //create POI
      $possibleanswers = Possibleanswer::find($id);
      $possibleanswers->answer_en=$request->input('answer_en');
      $possibleanswers->answer_gr=$request->input('answer_gr');
      $possibleanswers->answer_ru=$request->input('answer_ru');
      $possibleanswers->answer_it=$request->input('answer_it');
      $possibleanswers->answer_fr=$request->input('answer_fr');
      $possibleanswers->answer_ge=$request->input('answer_ge');
      $possibleanswers->order_num=$request->input('order_num');
      //$possibleanswers->question_id=$request->input('question_id'); 

      //handle video image upload
      if($request->hasFile('path_img'))
      {
        //get filename with the extension
        $filenameWithExt=$request->file('path_img')->getClientOriginalName();
        //get just $filename
        $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just the extension
        $extension= $request->file('path_img')->getClientOriginalExtension();
        //filename to store
        $fileNameToStore=$filename.'_'.time().'.'.$extension;
        //upload image
        $path= $request->file('path_img')->storeAs("public/media/PossibleAnswers/", $fileNameToStore);

        if ($possibleanswers->path_img!="")
        {
           Storage::delete('public/media/'.$possibleanswers->path_img);
        }

        $possibleanswers->path_img= "PossibleAnswers/" . $fileNameToStore;
      }
          
      $possibleanswers->save();

      $questions= Question::find($possibleanswers->question_id); 
      $possibleanswers=Possibleanswer::where('question_id', $possibleanswers->question_id)->get();       

      return view('questions.show')->with('questions',$questions)->with('possibleanswers',$possibleanswers);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $possibleanswers= Possibleanswer::find($id); 
      Possibleanswer::where('id', $id)->delete();       
      Storage::delete('public/media/'.$possibleanswers->path_img);
      
      $questions= Question::find($possibleanswers->question_id); 
      $possibleanswers=Possibleanswer::where('question_id', $possibleanswers->question_id)->get();   

      return view('questions.show')->with('questions',$questions)->with('possibleanswers',$possibleanswers);
    }
}
