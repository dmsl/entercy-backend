<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poi;
use App\Site;
use App\Poichildren;
use App\Poimedia;
use App\Cy_district;
use App\Sitecategory;
use App\Siteworkinghour;
use App\Poitag;
use App\Tag;
use App\Chronological;
use App\Storytelling;
use App\Qr_room;
use App\Beacon;
use App\Outdoor_group;
use DB;

class PoitagsController extends Controller
{
    public function api_getpoitag(Request $request)
    {
        $data=Poitag::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_getpoitags_by_poiid(Request $request)
    {
        $data=Poitag::where('poi_id', $request->input('id'))->get();
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

    public function search(Request $request)
    {
        //return $request->input('poiid') . $request->input('keyword');
        $keyword=$request->input('keyword');
        $tags= Tag::where('tag_name', 'LIKE', "%$keyword%")->get();
        $poiid= $request->input('poiid');
        return view('poitags.create')->with('poiid', $poiid)->with('tags', $tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($poiid)
    {
        $tags= Tag::get();
        return view('poitags.create')->with('poiid', $poiid)->with('tags', $tags);
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
        'poiid' => 'required',
        'tagid' => 'required',
      ]);

      $existingPoiTag=Poitag::where('tag_id', $request->input('tagid'))->where('poi_id', $request->input('poiid'))->get();
      if ($existingPoiTag->isEmpty())
      {
        //if empty means TAG ID is NEW for this POItag so proceed *************

          $poitag = new Poitag;
          $poitag->poi_id=$request->input('poiid');
          $poitag->tag_id=$request->input('tagid');
          $poitag->save();
      }
      //even if exist try to add the other similar ones...

      //*************ADD SIMILAR TAGS BASED ON TAG_CODE **************
      $tags= Tag::get();
      $selectedTag = Tag::find($request->input('tagid'));
      foreach($tags as $tag)
      {

        $tagCode= str_replace('_', ' ', $tag->tag_code); //einai san i protasi aspm
        $separateWords = explode('_', $selectedTag->tag_code); //kai vlepi an ena apo ta array items iparxun mesa stn protasi

        foreach($separateWords as $separateWord)
        {
            if (strpos($tagCode, $separateWord) !== false)
            {
                $currentPoiTag=Poitag::where('tag_id', $tag->id)->where('poi_id', $request->input('poiid'))->get(); //vriski ta objects gia na dei an iparxi idi to TAG ID pou 8a prospa8isi na vali sto sigkekrimeno POI ID
                if (!$currentPoiTag->isEmpty())
                {
                    //if not empty means TAG ID already exist in the current POItag
                }
                else //else we add it
                {
                  $similarPoitag = new Poitag;
                  $similarPoitag->poi_id=$request->input('poiid');
                  $similarPoitag->tag_id=$tag->id;
                  $similarPoitag->save();
                }
            }
        }
      }


      //----RETURNING TO POIS SHOW PAGE with necessary variables -----
      $poi= Poi::find($request->input('poiid'));
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
      $poiartifact= Poi::where('artifact_id', $poi->id)->get();
      $poiartifact_foundon= Poi::where('id', $poi->artifact_id)->get();

      return view('pois.show')->with('poi',$poi)->with('poichildren',$poichildren)->with('poimedia',$poimedia)->with('poitag',$poitag)->with('chronological',$chronological)->with('sub_chronological',$sub_chronological)->with('storytelling',$storytelling)->with('qrrooms', $qrrooms)->with('beacons',$beacons)->with('outdoor_groups',$outdoor_groups)->with("poiartifact",$poiartifact)->with("poiartifact_foundon", $poiartifact_foundon);
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
      $poitag = Poitag::find($id);
      $poi= Poi::find($poitag->poi_id);
      $poitag->delete();
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
      $poiartifact= Poi::where('artifact_id', $poi->id)->get();
      $poiartifact_foundon= Poi::where('id', $poi->artifact_id)->get();

      return view('pois.show')->with('poi',$poi)->with('poichildren',$poichildren)->with('poimedia',$poimedia)->with('poitag',$poitag)->with('chronological',$chronological)->with('sub_chronological',$sub_chronological)->with('storytelling',$storytelling)->with('qrrooms', $qrrooms)->with('beacons',$beacons)->with('outdoor_groups',$outdoor_groups)->with("poiartifact",$poiartifact)->with("poiartifact_foundon", $poiartifact_foundon);
    }
}
