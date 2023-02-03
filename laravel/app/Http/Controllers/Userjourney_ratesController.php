<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Userjourney_rate;
use App\Visited_site_rate;
use App\Visited_sites_of_journey;

class Userjourney_ratesController extends Controller
{

    public function store_userjourney_and_sites_rates(Request $request)
    {
        $this->validate($request, [
        'all_rates' => 'required',       
        ]);

        $user_id = auth('api')->user()->id;
       

        $all_rates = $request->input('all_rates');
        $rates = explode('|', $all_rates);
        $userjourney_id=0;

        for ($i=0; $i<count($rates); $i++) {
            $rate= $rates[$i];

            $rating = explode(':', $rate);
            $id = $rating[0];
            $actual_rate = $rating[1];

            if ($i==0) //checks the first one because it's the USERJOURNEY RATING
            {
                $userjourney_id=$id;

                //DELETES PREVIOUS RATINGS FOR THE specific combination of userID,userjourney_id.
                Userjourney_rate::where('user_id', $user_id)->where('userjourney_id', $id)->delete();

                $userjourney_rate = new Userjourney_rate;
                $userjourney_rate->user_id =$user_id;
                $userjourney_rate->userjourney_id=$id;
                $userjourney_rate->rate =$actual_rate;
                $userjourney_rate->save();
            }
            else //checks ALL THE REST ones because they are SITES' RATINGS
            {
                if ($userjourney_id!=0)
                {
                    //DELETES PREVIOUS RATINGS FOR THE specific combination of userID,visited_site_id,userjourney_id.
                    Visited_site_rate::where('user_id', $user_id)->where('visited_site_id', $id)->where('userjourney_id', $userjourney_id)->delete();        

                    $visited_site_rate = new Visited_site_rate;
                    $visited_site_rate->user_id =$user_id;
                    $visited_site_rate->visited_site_id=$id;
                    $visited_site_rate->userjourney_id=$userjourney_id;
                    $visited_site_rate->rate =$actual_rate;
                    $visited_site_rate->save();

                    //DELETES PREVIOUS records FOR THE specific combination of userID, site_id,userjourney_id.
                    Visited_sites_of_journey::where('user_id', $user_id)->where('site_id', $id)->where('userjourney_id', $userjourney_id)->delete();        

                    $visited_sites_of_journey = new Visited_sites_of_journey;
                    $visited_sites_of_journey->user_id =$user_id;
                    $visited_sites_of_journey->site_id=$id;
                    $visited_sites_of_journey->userjourney_id=$userjourney_id;
                    $visited_sites_of_journey->save();
                }
            }
        }

         return response(['status' => 'success', 'data' => 'Successfully stored userjourney rate and sites rates!']);
    }

    public function store_userjourney_rate(Request $request)
    {
        $this->validate($request, [
        'userjourney_id' => 'required',
        'rate' => 'required',        
        ]);

        $user_id = auth('api')->user()->id;
        $userjourney_id = $request->input('userjourney_id');
        $rate = $request->input('rate');

        //DELETES PREVIOUS RATINGS FOR THE specific combination of userID,userjourney_id.
        Userjourney_rate::where('user_id', $user_id)->where('userjourney_id', $userjourney_id)->delete();

        $userjourney_rate = new Userjourney_rate;
        $userjourney_rate->user_id =$user_id;
        $userjourney_rate->userjourney_id=$userjourney_id;
        $userjourney_rate->rate =$rate;
        $userjourney_rate->save();

        return response(['status' => 'success', 'data' => 'Successfully stored userjourney rate!']);

    }

    public function get_userjourney_rate(Request $request)
    {
        $this->validate($request, [
        'userjourney_id' => 'required'
        ]);

        $user_id = auth('api')->user()->id;
        $userjourney_id = $request->input('userjourney_id');
        $data = Userjourney_rate::where('user_id', $user_id)->where('userjourney_id', $userjourney_id)->get();

        return response(['status' => 'success', 'data' => $data]);
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
