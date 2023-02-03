<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Visited_site_rate;
use App\Visited_sites_of_journey;

class Visited_site_ratesController extends Controller
{

    public function store_visited_site_rate(Request $request)
    {
        $this->validate($request, [
        'visited_site_id' => 'required',
        'userjourney_id' => 'required',
        'rate' => 'required',        
        ]);

        $user_id = auth('api')->user()->id; 
        $visited_site_id = $request->input('visited_site_id');
        $userjourney_id = $request->input('userjourney_id');
        $rate = $request->input('rate');

        //DELETES PREVIOUS RATINGS FOR THE specific combination of userID,visited_site_id,userjourney_id.
        Visited_site_rate::where('user_id', $user_id)->where('visited_site_id', $visited_site_id)->where('userjourney_id', $userjourney_id)->delete();        

        $visited_site_rate = new Visited_site_rate;
        $visited_site_rate->user_id =$user_id;
        $visited_site_rate->visited_site_id=$visited_site_id;
        $visited_site_rate->userjourney_id=$userjourney_id;
        $visited_site_rate->rate =$rate;
        $visited_site_rate->save();

        //----------updates a different table: Visited_sites_of_journey -----
        //DELETES PREVIOUS records FOR THE specific combination of userID, site_id,userjourney_id.
        Visited_sites_of_journey::where('user_id', $user_id)->where('site_id', $visited_site_id)->where('userjourney_id', $userjourney_id)->delete();        

        $visited_sites_of_journey = new Visited_sites_of_journey;
        $visited_sites_of_journey->user_id =$user_id;
        $visited_sites_of_journey->site_id=$visited_site_id;
        $visited_sites_of_journey->userjourney_id=$userjourney_id;
        $visited_sites_of_journey->save();

        return response(['status' => 'success', 'data' => "Successfully stored 'visited site rate' and 'visited site of journey' tables!"]);

    }

    public function get_visited_site_rate(Request $request)
    {
        $this->validate($request, [
        'visited_site_id' => 'required',
        'userjourney_id' => 'required'
        ]);

        $user_id = auth('api')->user()->id;
        $userjourney_id = $request->input('userjourney_id');
        $visited_site_id = $request->input('visited_site_id');
        $data = Visited_site_rate::where('user_id', $user_id)->where('visited_site_id', $visited_site_id)->where('userjourney_id', $userjourney_id)->get();

        return response(['status' => 'success', 'data' => $data]);
    }

    public function get_overall_site_rate(Request $request)
    {
        $this->validate($request, [
        'visited_site_id' => 'required',
        ]);

        $visited_site_id = $request->input('visited_site_id');
        $all_ratings = Visited_site_rate::where('visited_site_id', $visited_site_id)->get();
        $counter=0;
        $total=0;

        foreach ($all_ratings as $rating) {
             $counter= $counter+1;
             $total=$total+ $rating->rate;
        }

        if ($total==0 || $counter==0)
        {
            return response(['status' => 'success', 'data' => 'Either counter or total is zero so average rating cannot be provided.']);
        }
        else
        {
            $data=$total/$counter;
            return response(['status' => 'success', 'data' => $data]);
        }
        
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
