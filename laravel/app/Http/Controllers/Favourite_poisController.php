<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Favourite_poi;

class Favourite_poisController extends Controller
{

    public function post_favourite_poi(Request $request)
    {        
      $data= Favourite_poi::where('user_id', auth('api')->user()->id)->where('poi_id', $request->input('poi_id'))->get();

      if (count($data)>0)
      {
        return response(['status' => 'success', 'data' => 'Favourite poi already added!']);
      }
      else
      {
        $favouritepoi = new Favourite_poi;
        $favouritepoi->user_id=auth('api')->user()->id;
        $favouritepoi->poi_id=$request->input('poi_id');
        $favouritepoi->save();

        return response(['status' => 'success', 'data' => 'Successfully added favourite poi!']);
      }      

    }


    public function get_favourite_poi(Request $request)
    {        
        $userID = auth('api')->user()->id;        
        $data= Favourite_poi::where('user_id', $userID)->get();
        return response(['status' => 'success', 'data' => $data]);
    }

    public function delete_favourite_poi(Request $request)
    {        
        $favouritepoi= Favourite_poi::where('user_id', auth('api')->user()->id)->where('poi_id', $request->input('poi_id'))->delete();
        
        return response(['status' => 'success', 'data' => 'Successfully deleted favourite poi!']);
        
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
