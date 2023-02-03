<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Site;
use App\Poi;
use App\Poirate;
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

class PoiratesController extends Controller
{
     public function api_getpoirate(Request $request)
    {        
        $data = Poirate::where('poi_id',$request->input('id'))->avg('rate_score');
        return response(['status' => 'success', 'data' => $data]);
    }

    public function store_poi_rating(Request $request)
    {
        $this->validate($request, [
        'rate_score' => 'required',
        'poi_id' => 'required',        
        ]);

        $rateExist = Poirate::where('poi_id',$request->input('poi_id'))->where('user_id',auth('api')->user()->id)->get();
          
        if (count($rateExist)>0)
        {
            $rate = Poirate::find($rateExist[0]->id);  
            $rate->rate_score=$request->input('rate_score');
            $rate->poi_id=$request->input('poi_id');         
            $rate->user_id=auth('api')->user()->id; 
            $rate->comments=$request->input('comments');      
            $rate->save();
        }
        else
        {
            $rate = new Poirate;            
            $rate->rate_score=$request->input('rate_score');
            $rate->poi_id=$request->input('poi_id');         
            $rate->user_id=auth('api')->user()->id; 
            $rate->comments=$request->input('comments');      
            $rate->save();
        }

        return response(['status' => 'success', 'data' => 'POI successfully rated']);
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
