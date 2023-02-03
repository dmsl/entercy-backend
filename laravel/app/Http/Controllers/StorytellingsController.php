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
use App\Outdoor_group;
use DB;


class StorytellingsController extends Controller
{

     public function api_getstorytelling(Request $request)
    {
        $data=Storytelling::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_getstorytellings_by_poiid(Request $request)
    {
        $data=Storytelling::where('poi_id', $request->input('id'))->get();
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
    public function create($poiid)
    {
        $storytelling= Storytelling::get();
        return view('storytellings.create')->with('poiid', $poiid);
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
          'language' => 'required',
          'duration' => 'required',
          'path' => 'required',
          'path_thumbnail' => 'required',
        ]);

      $storytelling = new Storytelling;
      $storytelling->name=$request->input('name');
      $storytelling->language=$request->input('language');
      $storytelling->duration=$request->input('duration');
      $storytelling->description=$request->input('description');
      $storytelling->poi_id=$request->input('poiid');
      $storytelling->save();

      //handle file upload
        if($request->hasFile('path'))
        {
          //get filename with the extension
          $filenameWithExt=$request->file('path')->getClientOriginalName();
          //get just $filename
          $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
          //get just the extension
          $extension= $request->file('path')->getClientOriginalExtension();
          //filename to store
          $fileNameToStore=$filename.'_'.time().'.'.$extension;

          $fullPath= '';
          $tempPOI= $storytelling->poi_id;
          $continue=true;
          while($continue == true)
          {
              $poi= Poi::find($tempPOI);
              $fullPath =$poi->id . "/" . $fullPath;
              if ($poi->parent_poi != 0)
              {
                $tempPOI=$poi->parent_poi;
              }
              else
              {
                $fullPath =$poi->site_id . "/" . $fullPath;
                $continue=false;
              }
          }

          $fullPath = substr($fullPath, 0, -1); //removes the last character which is an extra /
          //upload image
          $path= $request->file('path')->storeAs("public/media/{$fullPath}", $fileNameToStore);
          //save only the image on the right folder path
          $storytelling->path= $fullPath . "/" . $fileNameToStore;
          $storytelling->save();
        }

        //THUMBNAIL handle file upload
        if($request->hasFile('path_thumbnail'))
        {
          //get filename with the extension
          $filenameWithExt=$request->file('path_thumbnail')->getClientOriginalName();
          //get just $filename
          $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
          //get just the extension
          $extension= $request->file('path_thumbnail')->getClientOriginalExtension();
          //filename to store
          $fileNameToStore=$filename.'_thumbnail_'.time().'.'.$extension;

          $fullPath= '';
          $tempPOI= $storytelling->poi_id;
          $continue=true;
          while($continue == true)
          {
              $poi= Poi::find($tempPOI);
              $fullPath =$poi->id . "/" . $fullPath;
              if ($poi->parent_poi != 0)
              {
                $tempPOI=$poi->parent_poi;
              }
              else
              {
                $fullPath =$poi->site_id . "/" . $fullPath;
                $continue=false;
              }
          }

          $fullPath = substr($fullPath, 0, -1); //removes the last character which is an extra /
          //upload image
          $path= $request->file('path_thumbnail')->storeAs("public/media/{$fullPath}", $fileNameToStore);
          //save only the image on the right folder path
          $storytelling->path_thumbnail= $fullPath . "/" . $fileNameToStore;
          $storytelling->save();
        }

      $poi= Poi::find($storytelling->poi_id);
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
        $storytelling= Storytelling::find($id);
        return view('storytellings.show')->with('storytelling',$storytelling);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $storytelling= Storytelling::find($id);
      return view('storytellings.edit')->with('storytelling',$storytelling);
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
          'language' => 'required',
          'duration' => 'required',
        ]);

      $storytelling = Storytelling::find($id);
      $storytelling->name=$request->input('name');
      $storytelling->language=$request->input('language');
      $storytelling->duration=$request->input('duration');
      $storytelling->description=$request->input('description');
      $storytelling->save();

      //handle file upload
        if($request->hasFile('path'))
        {
          Storage::delete('public/media/'.$storytelling->path);//delete the previous FILE if exist

          //get filename with the extension
          $filenameWithExt=$request->file('path')->getClientOriginalName();
          //get just $filename
          $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
          //get just the extension
          $extension= $request->file('path')->getClientOriginalExtension();
          //filename to store
          $fileNameToStore=$filename.'_'.time().'.'.$extension;

          $fullPath= '';
          $tempPOI= $storytelling->poi_id;
          $continue=true;
          while($continue == true)
          {
              $poi= Poi::find($tempPOI);
              $fullPath =$poi->id . "/" . $fullPath;
              if ($poi->parent_poi != 0)
              {
                $tempPOI=$poi->parent_poi;
              }
              else
              {
                $fullPath =$poi->site_id . "/" . $fullPath;
                $continue=false;
              }
          }

          $fullPath = substr($fullPath, 0, -1); //removes the last character which is an extra /
          //upload image
          $path= $request->file('path')->storeAs("public/media/{$fullPath}", $fileNameToStore);
          //save only the image on the right folder path
          $storytelling->path= $fullPath . "/" . $fileNameToStore;
          $storytelling->save();
        }

        //THUMBNAIL handle file upload
        if($request->hasFile('path_thumbnail'))
        {
           Storage::delete('public/media/'.$storytelling->path_thumbnail);//delete the previous thumbnail if exist

          //get filename with the extension
          $filenameWithExt=$request->file('path_thumbnail')->getClientOriginalName();
          //get just $filename
          $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
          //get just the extension
          $extension= $request->file('path_thumbnail')->getClientOriginalExtension();
          //filename to store
          $fileNameToStore=$filename.'_thumbnail_'.time().'.'.$extension;

          $fullPath= '';
          $tempPOI= $storytelling->poi_id;
          $continue=true;
          while($continue == true)
          {
              $poi= Poi::find($tempPOI);
              $fullPath =$poi->id . "/" . $fullPath;
              if ($poi->parent_poi != 0)
              {
                $tempPOI=$poi->parent_poi;
              }
              else
              {
                $fullPath =$poi->site_id . "/" . $fullPath;
                $continue=false;
              }
          }

          $fullPath = substr($fullPath, 0, -1); //removes the last character which is an extra /
          //upload image
          $path= $request->file('path_thumbnail')->storeAs("public/media/{$fullPath}", $fileNameToStore);
          //save only the image on the right folder path
          $storytelling->path_thumbnail= $fullPath . "/" . $fileNameToStore;
          $storytelling->save();
        }

      $poi= Poi::find($storytelling->poi_id);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $storytelling = Storytelling::find($id);

        Storage::delete('public/media/'.$storytelling->path);//delete the previous FILE if exist
        Storage::delete('public/media/'.$storytelling->path_thumbnail);//delete the previous thumbnail if exist

        $poi= Poi::find($storytelling->poi_id);
        $storytelling->delete(); //SEARCH for POI first and then DELETE the POI story telling record
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
