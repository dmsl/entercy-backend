<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Site;
use App\Poi;
use App\Poichildren;
use App\Poimedia;
use App\Poimediatype;
use App\Chronological;
use App\Storytelling;
use App\Storytellingrate;
use DB;


//--------------------
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth; 
use Laravel\Passport\Client as OClient; 
use Illuminate\Support\Facades\URL;
//--------------------

class StorytellingratesController extends Controller
{
    public function api_getstorytellingrate(Request $request)
    {        
        $data = Storytellingrate::where('storytelling_id',$request->input('id'))->avg('rate_score');
        return response(['status' => 'success', 'data' => $data]);
    }

    public function store_storytelling_rating(Request $request)
    {
        $this->validate($request, [
        'rate_score' => 'required',
        'storytelling_id' => 'required',        
        ]);

        $rateExist = Storytellingrate::where('storytelling_id',$request->input('storytelling_id'))->where('user_id',auth('api')->user()->id)->get();
          
        if (count($rateExist)>0)
        {
            $rate = Storytellingrate::find($rateExist[0]->id);  
            $rate->rate_score=$request->input('rate_score');
            $rate->storytelling_id=$request->input('storytelling_id');         
            $rate->user_id=auth('api')->user()->id; 
            $rate->comments=$request->input('comments');      
            $rate->save();
        }
        else
        {
            $rate = new Storytellingrate;            
            $rate->rate_score=$request->input('rate_score');
            $rate->storytelling_id=$request->input('storytelling_id');         
            $rate->user_id=auth('api')->user()->id; 
            $rate->comments=$request->input('comments');      
            $rate->save();
        }

        return response(['status' => 'success', 'data' => 'Successfully rated']);
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
