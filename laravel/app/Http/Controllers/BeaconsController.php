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
use App\Beacon;
use App\Qr_room_group;
use App\Outdoor_group;
use DB;

class BeaconsController extends Controller
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
    public function create($poiid)
    {
        return view('beacons.create')->with('poiid', $poiid);
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
            'beacon_id' => 'required',
        ]);

        $beacon = new Beacon;
        $beacon->name = $request->input('name');
        $beacon->beacon_id = $request->input('beacon_id');
        $beacon->poi_id = $request->input('poiid');
        $beacon->save();

        if ($request->hasFile('path')) {
            //get filename with the extension
            $filenameWithExt = $request->file('path')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('path')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $fullPath = '';
            $tempPOI = $beacon->poi_id;
            $continue = true;
            while ($continue == true) {
                $poi = Poi::find($tempPOI);
                $fullPath = $poi->id . "/" . $fullPath;
                if ($poi->parent_poi != 0) {
                    $tempPOI = $poi->parent_poi;
                } else {
                    $fullPath = $poi->site_id . "/" . $fullPath;
                    $continue = false;
                }
            }

            $fullPath = substr($fullPath, 0, -1); //removes the last character which is an extra /
            //upload image
            $path = $request->file('path')->storeAs("public/media/{$fullPath}", $fileNameToStore);
            //save only the image on the right folder path
            $beacon->img_path = $fullPath . "/" . $fileNameToStore;
            $beacon->save();
        }

        $poi = Poi::find($beacon->poi_id);
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
    public function show($id)
    {
        $beacon= Beacon::find($id);
        return view('beacons.show')->with('beacon',$beacon);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $beacon= Beacon::find($id);
        return view('beacons.edit')->with('beacon',$beacon);
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
            'beacon_id' => 'required',
        ]);

        $beacon = Beacon::find($id);
        $beacon->name = $request->input('name');
        $beacon->beacon_id = $request->input('beacon_id');
        $beacon->poi_id = $request->input('poiid');
        $beacon->save();

        if ($request->hasFile('path')) {
            //get filename with the extension
            $filenameWithExt = $request->file('path')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('path')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $fullPath = '';
            $tempPOI = $beacon->poi_id;
            $continue = true;
            while ($continue == true) {
                $poi = Poi::find($tempPOI);
                $fullPath = $poi->id . "/" . $fullPath;
                if ($poi->parent_poi != 0) {
                    $tempPOI = $poi->parent_poi;
                } else {
                    $fullPath = $poi->site_id . "/" . $fullPath;
                    $continue = false;
                }
            }

            $fullPath = substr($fullPath, 0, -1); //removes the last character which is an extra /
            //upload image
            $path = $request->file('path')->storeAs("public/media/{$fullPath}", $fileNameToStore);
            //save only the image on the right folder path
            $beacon->img_path = $fullPath . "/" . $fileNameToStore;
            $beacon->save();
        }

        $poi = Poi::find($beacon->poi_id);
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
    public function destroy($id)
    {
        $beacon = Beacon::find($id);

        $poi= Poi::find($beacon->poi_id);
        $beacon->delete(); //SEARCH for POI first and then DELETE the qrroom record
        $poichildren = Poi::where('parent_poi', $poi->id)->get();
        $poimedia = Poimedia::where('poi_id', $poi->id)->get();
        $poitag = \DB::table('poitags')
            ->join('tags', 'tags.id', '=', 'poitags.tag_id')
            ->select('tags.tag_name as name', 'poitags.id')
            ->where('poitags.poi_id', $poi->id)
            ->get();

        $chronological= Chronological::find($poi->chronological_id);
        $sub_chronological= Chronological::find($poi->sub_chronological_id);

        $storytelling=Storytelling::where('poi_id', $poi->id)->get();
        $qrrooms = Qr_room::where('poi_id', $poi->id)->get();
        $beacons = Beacon::where('poi_id', $poi->id)->get();
        $outdoor_groups=Outdoor_group::where('poi_id', $poi->id)->get();
        $poiartifact = Poi::where('artifact_id', $poi->id)->get();
        $poiartifact_foundon = Poi::where('id', $poi->artifact_id)->get();

        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacons)->with('outdoor_groups', $outdoor_groups)->with('poiartifact', $poiartifact)->with('poiartifact_foundon', $poiartifact_foundon);
    }
}
