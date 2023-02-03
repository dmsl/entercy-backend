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
use App\Qr_room;
use App\Qr_room_group;
use App\Beacon;
use App\Qr_room_link;
use App\Outdoor_group;
use App\Userjourney;
use App\Visited_sites_of_journey;
use Illuminate\Support\Facades\DB;


class Qr_roomsController extends Controller
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

    public function api_get_qr_room_by_qr_code(Request $request)
    {
        $user_id = auth('api')->user()->id;
        $qr_code = $request->input('qr_code');
        if ($qr_code==null || $qr_code=='')
        {
            return response(['status' => 'success', 'data' => 'Please enter a valid QR code']); 
        }

        $qr_room = Qr_room::where('qr_code', $qr_code)->get();

        $result = [];
        if (count($qr_room) > 0) {
            $poi = Poi::find($qr_room[0]->poi_id);

            //in case the poi is sub-children we loop all the way to the top to find the parent poi and finally the SITE ID
            while ($poi->site_id==0)
            {
                $poi = Poi::find($poi->parent_poi);
            }

            $result[sizeof($result)]=array('site_id'=>$poi->site_id,'room_id'=>$qr_room[0]->id);
             
            //----------updates a different table: Visited_sites_of_journey -----
            //automaticallly finds the latest active user's journey
            $userjourney= Userjourney::where('user_id', $user_id)->where('status', 'active')->get();

            if (count($userjourney)==0)
            {
                echo 'There is no active journey for this user in order to add info on Visited_sites_of_journey table!';
            }
            else
            {
                //DELETES PREVIOUS records FOR THE specific combination of userID, site_id,userjourney_id.
                Visited_sites_of_journey::where('user_id', $user_id)->where('site_id', $poi->site_id)->where('userjourney_id', $userjourney[0]->id)->delete();        

                $visited_sites_of_journey = new Visited_sites_of_journey;
                $visited_sites_of_journey->user_id =$user_id;
                $visited_sites_of_journey->site_id=$poi->site_id;
                $visited_sites_of_journey->userjourney_id=$userjourney[0]->id;
                $visited_sites_of_journey->save();
            }
            
            return response(['status' => 'success', 'data' => $result]);
        }

        //in case of no qr code found return null
        return response(['status' => 'success', 'data' => null]);
    }

    public function api_get_qr_groupsmedia_by_qr_room(Request $request)
    {
        $room_id = $request->input('room_id');
        $site_id = $request->input('site_id');
        $qr_room_groups = Qr_room_group::where('qr_room_id', $room_id)->get();
        $result = [];
        foreach ($qr_room_groups as $qr_room_group) {
            $qr_room_links = Qr_room_link::where('group_id', $qr_room_group->id)->get();

            $result2 = [];
            foreach ($qr_room_links as $qr_room_link) {
                if (is_null($qr_room_link->storytelling_id)) // CHECKS IF POI MEDIA
                {
                     $link = DB::table('qr_room_links')
                    ->join('poimedia', 'qr_room_links.poi_media_id', '=', 'poimedia.id')
                    ->join('poimediatypes', 'poimedia.type', '=', 'poimediatypes.id')
                    ->select('qr_room_links.*','poimediatypes.id as type_id','poimediatypes.name as type_name')
                    ->where('qr_room_links.poi_media_id', $qr_room_link->poi_media_id)
                    ->get();

                    array_push($result2, $link[0]);
                }
                else // CHECKS IF STORY TELLING
                {
                     $link = DB::table('qr_room_links')
                    ->join('storytellings', 'qr_room_links.storytelling_id', '=', 'storytellings.id')
                    ->select('qr_room_links.*','storytellings.*')
                    ->where('qr_room_links.storytelling_id', $qr_room_link->storytelling_id)
                    ->get();

                    array_push($result2, $link[0]);
                }
            }

            $result[sizeof($result)]=array('room_group'=>$qr_room_group,'room_links'=>$result2);
        }

        return response(['status' => 'success', 'data' => $result]);
    }


    public function create($poiid)
    {
        return view('qrrooms.create')->with('poiid', $poiid);
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
            'name' => 'required'
            //'qr_code' => 'required',
        ]);

        $qrroom = new Qr_room;
        $qrroom->name = $request->input('name');
        $qrroom->qr_code = $request->input('qr_code');
        $qrroom->poi_id = $request->input('poiid');
        $qrroom->save();

        $poi = Poi::find($qrroom->poi_id);
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
        $outdoor_groups=Outdoor_group::where('poi_id', $poi->id)->get();
        $poiartifact= Poi::where('artifact_id', $poi->id)->get();
        $poiartifact_foundon= Poi::where('id', $poi->artifact_id)->get();


        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacons)->with('outdoor_groups',$outdoor_groups)->with('poiartifact',$poiartifact)->with('poiartifact_foundon',$poiartifact_foundon);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $qr_room = Qr_room::find($id);
        $qr_room_grouped = Qr_room_group::where('qr_room_id', $qr_room->id)->get();
        return view('qrrooms.show')->with('qr_room', $qr_room)->with('qr_room_grouped', $qr_room_grouped);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $qr_room = Qr_room::find($id);
        return view('qrrooms.edit')->with('qr_room', $qr_room);
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
            'name' => 'required'
            //'qr_code' => 'required',
        ]);

        $qrroom = Qr_room::find($id);
        $qrroom->name = $request->input('name');
        $qrroom->qr_code = $request->input('qr_code');
        #$qrroom->poi_id=$request->input('poiid');
        $qrroom->save();

        $poi = Poi::find($qrroom->poi_id);
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
        $outdoor_groups=Outdoor_group::where('poi_id', $poi->id)->get();
        $poiartifact= Poi::where('artifact_id', $poi->id)->get();
        $poiartifact_foundon= Poi::where('id', $poi->artifact_id)->get();


        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacons)->with('outdoor_groups',$outdoor_groups)->with('poiartifact',$poiartifact)->with('poiartifact_foundon',$poiartifact_foundon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $qr_room = Qr_room::find($id);

        $poi = Poi::find($qr_room->poi_id);
        Qr_room_link::where('qr_room_id', $qr_room->id)->delete();
        Qr_room_group::where('qr_room_id', $qr_room->id)->delete();
        $qr_room->delete(); //SEARCH for POI first and then DELETE the qrroom record
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
        $outdoor_groups=Outdoor_group::where('poi_id', $poi->id)->get();
        $poiartifact= Poi::where('artifact_id', $poi->id)->get();
        $poiartifact_foundon= Poi::where('id', $poi->artifact_id)->get();


        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacons)->with('outdoor_groups',$outdoor_groups)->with('poiartifact',$poiartifact)->with('poiartifact_foundon',$poiartifact_foundon);
    }
}
