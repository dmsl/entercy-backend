<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Visited_sites_of_journey;
use App\Poi;
use App\Userjourney;

class Visited_sites_of_journeysController extends Controller
{
    public function store_visited_site_of_journey(Request $request)
    {
        $this->validate($request, [
        'site_id' => 'required',
        'userjourney_id' => 'required',
        ]);

        $user_id = auth('api')->user()->id; 
        $site_id = $request->input('site_id');
        $userjourney_id = $request->input('userjourney_id');

        //DELETES PREVIOUS records FOR THE specific combination of userID, site_id,userjourney_id.
        Visited_sites_of_journey::where('user_id', $user_id)->where('site_id', $site_id)->where('userjourney_id', $userjourney_id)->delete();        

        $visited_sites_of_journey = new Visited_sites_of_journey;
        $visited_sites_of_journey->user_id =$user_id;
        $visited_sites_of_journey->site_id=$site_id;
        $visited_sites_of_journey->userjourney_id=$userjourney_id;
        $visited_sites_of_journey->save();

        return response(['status' => 'success', 'data' => 'Successfully stored visited site of journey!']);

    }

    public function get_visited_sites_of_journey(Request $request)
    {
        $this->validate($request, [
        'userjourney_id' => 'required'
        ]);

        $user_id = auth('api')->user()->id;
        $userjourney_id = $request->input('userjourney_id');        
        $data = Visited_sites_of_journey::where('user_id', $user_id)->where('userjourney_id', $userjourney_id)->get();

        return response(['status' => 'success', 'data' => $data]);
    }

    public function store_visited_site_of_journey_GPS(Request $request)
    {
        $user_id = auth('api')->user()->id; 
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $max_bound = 1; //$request->input('max_bound');

        $pois = Poi::all();
        $results = [];
        $min = 9999;
        $id='';
        foreach ($pois as $poi) {
            $pieces = explode(",", $poi->coordinates);
            if (!isset($pieces[1])) {
                $pieces[1] = '0';
            }
            //print_r($this->distance($latitude,$longitude,$pieces[0],$pieces[1],''));
            //echo"<br>";
            $distance = floatval($this->distance($latitude, $longitude, $pieces[0], $pieces[1], 'K'));
            if ($distance < floatval($max_bound)) {
                //echo 'Number ' . $pieces[0] . ' is in range '.$latitude.'-' . intval($max_bound+$latitude).'<br>';
                //echo 'Number ' . $pieces[1] . ' is in range '.$longitude.'-' . intval($max_bound+$longitude).'<br>';
                if ($distance < $min) {
                    $min = $distance;
                    $id=$poi->id;
                }
            }
        }
        
        if ($id==null || $id==0 || $id=='')
        {
            return response(['status' => 'success', 'data' => 'No site id found!']);
        }

        $poi = Poi::find($id);
        //in case the poi is sub-children we loop all the way to the top to find the parent poi and finally the SITE ID
        while ($poi->site_id==0)
        {
            $poi = Poi::find($poi->parent_poi);
        }

        //automaticallly finds the latest active user's journey
        $userjourney= Userjourney::where('user_id', $user_id)->where('status', 'active')->get();

        if (count($userjourney)==0)
        {
            return response(['status' => 'success', 'data' => 'There is no active journey for this user!']);
        }

        //DELETES PREVIOUS records FOR THE specific combination of userID, site_id,userjourney_id.
        Visited_sites_of_journey::where('user_id', $user_id)->where('site_id', $poi->site_id)->where('userjourney_id', $userjourney[0]->id)->delete();        

        $visited_sites_of_journey = new Visited_sites_of_journey;
        $visited_sites_of_journey->user_id =$user_id;
        $visited_sites_of_journey->site_id=$poi->site_id;
        $visited_sites_of_journey->userjourney_id=$userjourney[0]->id;
        $visited_sites_of_journey->save();

        return response(['status' => 'success', 'data' => 'Successfully stored visited site of journey!']);
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
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
