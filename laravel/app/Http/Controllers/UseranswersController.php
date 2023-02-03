<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Question;
use App\Possibleanswer;
use App\Useranswer;

class UseranswersController extends Controller
{
    public function api_postanswer(Request $request)
    {//gia to api postanswer anti na ginetai diaforetiko update kai new save na ginetai delete kai store
        $this->validate($request, [
            'questionanswer' => 'required',
        ]);

        $questionanswer_string = $request->input('questionanswer');
        $question_answers = explode('|', $questionanswer_string);

        foreach ($question_answers as  $question_answer) {

            $answers = explode(':', $question_answer);
            $user_id = auth('api')->user()->id;
            $question_id = $answers[0];
            Useranswer::where('user_id', $user_id)->where('question_id', $question_id)->delete();
            if (isset($answers[1])) {
                $useranswer = new Useranswer;
                $useranswer->question_id = $question_id;
                $useranswer->user_id = $user_id;
                $useranswer->answer = $answers[1];
                $useranswer->save();
            }
          /*  $result = Useranswer::where('user_id', $user_id)->where('question_id', $question_id)->get()->count();
            if ($result != 0) {
                $last_value = Useranswer::where('user_id', $user_id)->where('question_id', $question_id)->pluck('answer');//we use pluck to get only the column answer
                $last_value = trim(str_replace('"]', '', str_replace('["', '', $last_value)));
                //return $last_value.' '.$answers[1];
                $answers_temp = explode(',', $answers[1]);
                foreach ($answers_temp as $answer) {
                    if (!str_contains($last_value, $answer)) {
                        Useranswer::where('user_id', $user_id)->where('question_id', $question_id)->update(['answer' => $last_value . ',' . $answer]);
                    }
                }
            } else {
                if (isset($answers[1])) {
                    $useranswer = new Useranswer;
                    $useranswer->question_id = $question_id;
                    $useranswer->user_id = $user_id;
                    $useranswer->answer = $answers[1];
                    $useranswer->save();
                }*/
            }


        return response(['status' => 'success', 'data' => 'Successfully created']);
    }

    public function api_getall_useranswers(Request $request)
    {
        $user_id = auth('api')->user()->id;
        $useranswers = Useranswer::where('user_id', $user_id)->orderBy('question_id', 'asc')->get();

        return response(['status' => 'success', 'data' => $useranswers]);
    }

    public function api_get_useranswer(Request $request)
    {
        $this->validate($request, [
            'question_id' => 'required',
        ]);
        $user_id = auth('api')->user()->id;
        $useranswer = Useranswer::where('user_id', $user_id)->where('question_id', $request->input('question_id'))->orderBy('question_id', 'asc')->get();

        return response(['status' => 'success', 'data' => $useranswer]);
    }

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
