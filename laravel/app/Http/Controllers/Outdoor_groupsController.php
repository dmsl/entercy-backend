<?php

namespace App\Http\Controllers;

use App\Outdoor_group;
use Illuminate\Http\Request;
use App\Poi;
use App\Site;
use App\Poichildren;
use App\Poimedia;
use App\Cy_district;
use App\Sitecategory;
use App\Siteworkinghour;
use App\Poitag;
use App\Chronological;
use App\Storytelling;
use App\Storytellingrate; //not used yet
use App\Qr_room_link;
use App\Poirate;
use App\Qr_room;
use App\Beacon;
use App\Qr_room_group;
use App\Outdoor_link;
use DB;

class Outdoor_groupsController extends Controller
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


    public function api_get_sites_by_outdoor_group_id(Request $request)
    {
        $outdoor_group_id = $request->input('outdoor_group_id');
        $outdoor_group = Outdoor_group::find($outdoor_group_id);

        if (isset($outdoor_group)) {
            return response(['status' => 'success', 'data' => $outdoor_group]);
        }
        return response(['status' => 'success', 'data' => '']);
    }
    public function api_get_outdoor_links_by_outdoor_group(Request $request)
    {
        $outdoor_group_id = $request->input('outdoor_group_id');
        $outdoor_group = Outdoor_group::find($outdoor_group_id);

        if (isset($outdoor_group)) {
            $outdoor_links = Outdoor_link::where('outdoor_group_id', $outdoor_group->id)->get();
            $results = [];
            foreach ($outdoor_links as $outdoor_link) {
                array_push($results, $outdoor_link);
            }
        }
        if (empty($results)) {
            return response(['status' => 'success', 'data' => '']);
        }
        return response(['status' => 'success', 'data' => $results]);
    }

    public function api_OutdoorAugmented(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $max_bound = $request->input('max_bound');

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
        $outdoor_groups = Outdoor_group::where('poi_id', $id)->get();
        //array_push($results, $outdoor_groups);
        foreach ($outdoor_groups as $outdoor_group) {
            $outdoor_links = Outdoor_link::where('outdoor_group_id', $outdoor_group->id)->get();
            $result2 = [];
            foreach ($outdoor_links as $outdoor_link) {
                if (is_null($outdoor_link->storytelling_id)) // CHECKS IF POI MEDIA
                {
                     $link = DB::table('outdoor_links')
                    ->join('poimedia', 'outdoor_links.poi_media_id', '=', 'poimedia.id')
                    ->join('poimediatypes', 'poimedia.type', '=', 'poimediatypes.id')
                    ->select('outdoor_links.*','poimediatypes.id as type_id','poimediatypes.name as type_name')
                    ->where('outdoor_links.poi_media_id', $outdoor_link->poi_media_id)
                    ->get();

                    array_push($result2, $link[0]);
                }
                else // CHECKS IF STORY TELLING
                {
                     $link = DB::table('outdoor_links')
                    ->join('storytellings', 'outdoor_links.storytelling_id', '=', 'storytellings.id')
                    ->select('outdoor_links.*','storytellings.*')
                    ->where('outdoor_links.storytelling_id', $outdoor_link->storytelling_id)
                    ->get();

                    array_push($result2, $link[0]);
                }
            }

            //array_push($results, $outdoor_links);
            $results[sizeof($results)] = array('poi' => $poi, 'outdoor_group' => $outdoor_group, 'outdoor_link' => $result2);
        }

        if (empty($results)) {
            return response(['status' => 'success', 'data' => null]);
        }
        return response(['status' => 'success', 'data' => $results]);
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
    public function create($poiid)
    {
        return view('outdoor_groups.create')->with('poiid', $poiid);
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
            'name' => 'required',
        ]);

        $outdoor_group = new Outdoor_group;

        $outdoor_group->poi_id = $request->input('poiid');
        $outdoor_group->name = $request->input('name');
        $outdoor_group->latitude = $request->input('latitude');
        $outdoor_group->longitude = $request->input('longitude');
        $outdoor_group->altitude = $request->input('altitude');

        $outdoor_group->save();

        $poi = Poi::find($outdoor_group->poi_id);
        //$poichildren= Poichildren::select('id','poi_id','child_poi_id')->where('poi_id', $poi->id)->get();
        //return   $poi . $poichildren;
        $poichildren = Poi::where('parent_poi', $poi->id)->get();
        $poimedia = Poimedia::where('poi_id', $poi->id)->get();

        $poitag = \DB::table('poitags')
            ->join('tags', 'tags.id', '=', 'poitags.tag_id')
            ->select('tags.tag_name as name', 'poitags.id')
            ->where('poitags.poi_id', $poi->id)
            ->get();

        $chronological = Chronological::find($poi->chronological_id);
        $sub_chronological = Chronological::find($poi->sub_chronological_id);

        $storytelling = Storytelling::where('poi_id', $poi->id)->get();
        $qrrooms = Qr_room::where('poi_id', $poi->id)->get();
        $beacons = Beacon::where('poi_id', $poi->id)->get();
        $outdoor_groups = Outdoor_group::where('poi_id', $poi->id)->get();
        $poiartifact = Poi::where('artifact_id', $poi->id)->get();
        $poiartifact_foundon = Poi::where('id', $poi->artifact_id)->get();

        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacons)->with('outdoor_groups', $outdoor_groups)->with('poiartifact', $poiartifact)->with('poiartifact_foundon', $poiartifact_foundon);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($outdoor_group_id)
    {
        $outdoor_groups = Outdoor_group::find($outdoor_group_id);
        $outdoor_links = Outdoor_link::where('outdoor_group_id', $outdoor_groups->id)->get();
        return view('outdoor_groups.show')->with('outdoor_groups', $outdoor_groups)->with('outdoor_links', $outdoor_links);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($outdoor_group_id)
    {
        $outdoor_groups = Outdoor_group::find($outdoor_group_id);
        return view('outdoor_groups.edit')->with('outdoor_groups', $outdoor_groups);
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
            'name' => 'required',
        ]);

        $outdoor_group = Outdoor_group::find($id);

        #$outdoor_group->poi_id=$request->input('poiid');
        $outdoor_group->name = $request->input('name');
        $outdoor_group->latitude = $request->input('latitude');
        $outdoor_group->longitude = $request->input('longitude');
        $outdoor_group->altitude = $request->input('altitude');

        $outdoor_group->save();

        $poi = Poi::find($outdoor_group->poi_id);
        //$poichildren= Poichildren::select('id','poi_id','child_poi_id')->where('poi_id', $poi->id)->get();
        //return   $poi . $poichildren;
        $poichildren = Poi::where('parent_poi', $poi->id)->get();
        $poimedia = Poimedia::where('poi_id', $poi->id)->get();

        $poitag = \DB::table('poitags')
            ->join('tags', 'tags.id', '=', 'poitags.tag_id')
            ->select('tags.tag_name as name', 'poitags.id')
            ->where('poitags.poi_id', $poi->id)
            ->get();

        $chronological = Chronological::find($poi->chronological_id);
        $sub_chronological = Chronological::find($poi->sub_chronological_id);

        $storytelling = Storytelling::where('poi_id', $poi->id)->get();
        $qrrooms = Qr_room::where('poi_id', $poi->id)->get();
        $beacons = Beacon::where('poi_id', $poi->id)->get();
        $outdoor_groups = Outdoor_group::where('poi_id', $poi->id)->get();

        $poiartifact = Poi::where('artifact_id', $poi->id)->get();
        $poiartifact_foundon = Poi::where('id', $poi->artifact_id)->get();

        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacons)->with('outdoor_groups', $outdoor_groups)->with('poiartifact', $poiartifact)->with('poiartifact_foundon', $poiartifact_foundon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($outdoor_group_id)
    {
        $outdoor_group = Outdoor_group::find($outdoor_group_id);
        $outdoor_links = Outdoor_link::where('outdoor_group_id', $outdoor_group->id)->delete();
        $poi = Poi::find($outdoor_group->poi_id);
        $outdoor_group->delete();

        $poichildren = Poi::where('parent_poi', $poi->id)->get();
        $poimedia = Poimedia::where('poi_id', $poi->id)->get();

        $poitag = \DB::table('poitags')
            ->join('tags', 'tags.id', '=', 'poitags.tag_id')
            ->select('tags.tag_name as name', 'poitags.id')
            ->where('poitags.poi_id', $poi->id)
            ->get();

        $chronological = Chronological::find($poi->chronological_id);
        $sub_chronological = Chronological::find($poi->sub_chronological_id);

        $storytelling = Storytelling::where('poi_id', $poi->id)->get();
        $qrrooms = Qr_room::where('poi_id', $poi->id)->get();
        $beacons = Beacon::where('poi_id', $poi->id)->get();
        $outdoor_groups = Outdoor_group::where('poi_id', $poi->id)->get();

        $poiartifact = Poi::where('artifact_id', $poi->id)->get();
        $poiartifact_foundon = Poi::where('id', $poi->artifact_id)->get();

        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacons)->with('outdoor_groups', $outdoor_groups)->with('poiartifact', $poiartifact)->with('poiartifact_foundon', $poiartifact_foundon);
    }
}
