<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
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
use App\Qr_room_link;
use App\Beacon;
use App\Outdoor_group;
use App\Userjourney;
use DB;
use Exception;
use function GuzzleHttp\Promise\each;

class PoimediasController extends Controller
{
    /** REMEMBER TO ADD to prevent unauthorised users to create/view/edit data
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    public function api_getpoimedias()
    {

        return response(['status' => 'success', 'data' => Poimedia::get()]);
    }
    public function api_getpoimedia(Request $request)
    {
        $data = Poimedia::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_getpoimedia_by_poiID(Request $request)
    {
        $data = Poimedia::where('poi_id', $request->input('id'))->get();
        return response(['status' => 'success', 'data' => $data]);
    }

    //remember to set crontab for this function to run every 1 hour
    public function check_stitching_video_completion(Request $request)
    {
        $userjourneys = Userjourney::where('status', 'generating')->where('youtube_link', '!=', null)->get();

        foreach ($userjourneys as $userjourney) {
            $separete = explode(" ", $userjourney->updated_at);
            $time_video_created = $separete[1];
            $time_video_created_plus5 = date('H', strtotime($time_video_created) + 300 * 60); //adding 5 hours to updated time. link:https://stackoverflow.com/questions/8552106/adding-1-hour-to-time-variable
            $current_time = date('H');

            if ($time_video_created_plus5 == $current_time) {
                $uj = Userjourney::find($userjourney->id);
                $uj->status = 'completed';
                $uj->save();
            }
        }
        //return response(['status' => 'success', 'data' => 'Successfully status set to completed!']);
    }

    public function call_stitching_tool(Request $request)
    {
        $filename = 'storage/media/stitchingTool/VideoStitchingConsole';

        $output = shell_exec($filename . ' ' . $request->input('user_journey_id'));
        //$output ='youtube.com/test/';

        if (strpos($output, 'Error') !== false) // if contains the word Error goes in here
        {
            return 'Something went wrong with the stitching tool:' . $output;
        } else //if DOES NOT contain the word Error goes here
        {
            $separete = explode(" ", $output);
            $output = $separete[sizeof($separete) - 1]; //the size of the array -1

            $user_journey = Userjourney::find($request->input('user_journey_id'));
            $user_journey->youtube_link = $output;
            $user_journey->status = 'generating';
            $user_journey->save();

            return response(['status' => 'success', 'data' => 'Successfully created stitching video, youtube link, and status set to generating.']);
        }
    }

    public function api_stitching_procedure(Request $request)
    {
        $journey_id = $request->input('journey_id');
        //$userID = auth('api')->user()->id;
        $FOLDER_NAME = 'Stitching_Library/';
        $path = Storage::path('public/media/' . $FOLDER_NAME);
        $files = scandir($path); //reads all the files from the given path
        $myArray = [];
        $myArray2 = [];
        //$userjourneys = Userjourney::where('user_id', $userID)->where('id', $journey_id)->get();
        $userjourneys = Userjourney::where('id', $journey_id)->get();
        foreach ($userjourneys as $userjourney) {
            $site_ids = explode(",", $userjourney->journey_ids);
            foreach ($site_ids as $site_id) {
                foreach ($files as $filename) {
                    $site = Site::where('id', $site_id)->get();
                    $extension = substr($filename, strrpos($filename, '.') + 1);
                    $filename = substr($filename, 0, (strrpos($filename, ".")));
                    foreach ($site as $site) {
                        if ($filename != "." && $filename != ".." && $filename != "") {
                            //print_r($filename . " " . $site->name);
                            //echo "<br>";
                            try {
                                $newString =str_replace("/","", $site->name);
                                if (preg_match("/" .  $newString . "/i",  $filename)) {
                                    //print_r($filename);
                                    //echo "<br>";
                                    //array_push($myArray, $filename.'.'.$extension);
                                    $myArray[sizeof($myArray)] = array('name' => $filename . '.' . $extension);
                                }
                            } catch (Exception $e) {
                                echo 'Caught exception: ',  $e->getMessage(), "\n";
                                return $filename . " " . $site->name;
                            }
                        }
                    }
                }
            }
        }
        $results = array_unique($myArray, SORT_REGULAR);
        $count = 0;
        $res = $results;
        foreach ($results as $result) {
            $oldkey = array_search($result, $res);
            $res = $this->change_key($res, '0', 'filename' . $count);
            $count++;
        }
        $myArray2[sizeof($myArray2)] = array('count' => $count, 'filenames' => $res);
        return $myArray2;
        /*if (preg_match("/php/i", "A PHP is the web scripting language of choice.")) {
            echo "A match was found.";
        } else {
            echo "A match was not found.";
        }*/
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

    public function change_key($array, $old_key, $new_key)
    {
        if (!is_array($array)) {
            print 'You must enter a array as a haystack!';
            exit;
        }
        if (!array_key_exists($old_key, $array)) {
            return $array;
        }

        $key_pos = array_search($old_key, array_keys($array));
        $arr_before = array_slice($array, 0, $key_pos);
        $arr_after = array_slice($array, $key_pos + 1);
        $arr_renamed = array($new_key => $array[$old_key]);

        return $arr_before + $arr_renamed + $arr_after;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($poiid)
    {
        $pmtype = Poimediatype::get();
        $pois = Poi::get();
        return view('poimedia.create')->with('poiid', $poiid)->with('pmtype', $pmtype)->with('pois', $pois);
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
            'type' => 'required',
            'path_thumbnail' => 'required',
        ]);

        $pmtype = Poimediatype::find($request->input('type'));

        if (strpos($pmtype->name, 'Text') !== false) //checks if type contains the word TEXT
        {
            //create Poimedia (TEXT)
            $poimedia = new Poimedia;
            $poimedia->name = $request->input('name');
            $poimedia->type = $request->input('type');
            $poimedia->poi_id = $request->input('poiid');
            $poimedia->uri = $request->input('uri');
            $poimedia->artifact_poi_id = $request->input('artifact_poi_id');
            $poimedia->save();
        } else // type is FILE
        {
            $this->validate($request, [
                'uri' => 'required'
            ]);

            //create Poimedia (FILE)
            $poimedia = new Poimedia;
            $poimedia->name = $request->input('name');
            $poimedia->type = $request->input('type');
            $poimedia->poi_id = $request->input('poiid');
            $poimedia->artifact_poi_id = $request->input('artifact_poi_id');
            $poimedia->save();

            //handle file upload
            if ($request->hasFile('uri')) {
                //get filename with the extension
                $filenameWithExt = $request->file('uri')->getClientOriginalName();
                //get just $filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                //get just the extension
                $extension = $request->file('uri')->getClientOriginalExtension();
                //filename to store
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $fullPath = '';
                $tempPOI = $poimedia->poi_id;
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
                $path = $request->file('uri')->storeAs("public/media/{$fullPath}", $fileNameToStore);
                //save only the image on the right folder path
                $poimedia->uri = $fullPath . "/" . $fileNameToStore;
                $poimedia->save();

                //if($extension=="zip"){  IN CASE YOU WANT TO CONSIDER ALL ZIP FILES JUST REPLACE THIS LINE WITH THE BELOW
                if (($extension == "zip") && (($pmtype->name == "360 Tour") || ($pmtype->name == "360 Tour VR"))) {
                    $path = Storage::path('public/media/' . $poimedia->uri);
                    $zip = new \ZipArchive();
                    $res = $zip->open($path);
                    if ($res == TRUE) {
                        $directory_name = explode(".", $fileNameToStore)[0];
                        Storage::disk('public')->makeDirectory("media/" . $fullPath . "/" . $directory_name);
                        $unzip_success = $zip->extractTo('../storage/app/public/media/' . $fullPath . "/" . $directory_name);
                        $converted_res = $unzip_success ? 'true' : 'false';
                        @$zip->close();
                        Storage::delete('public/media/' . $poimedia->uri);
                        $poimedia->uri = $fullPath . "/" . $directory_name;
                        $poimedia->save();
                    }
                }
            }


            //handle file 2 upload
            if ($request->hasFile('uri2')) {
                //get filename with the extension
                $filenameWithExt = $request->file('uri2')->getClientOriginalName();
                //get just $filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                //get just the extension
                $extension = $request->file('uri2')->getClientOriginalExtension();
                //filename to store
                $fileNameToStore = $filename . '_uri2_' . time() . '.' . $extension;

                $fullPath = '';
                $tempPOI = $poimedia->poi_id;
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
                $path = $request->file('uri2')->storeAs("public/media/{$fullPath}", $fileNameToStore);
                //save only the image on the right folder path
                $poimedia->uri2 = $fullPath . "/" . $fileNameToStore;
                $poimedia->save();
            }
        } //the end of else statement

        //THUMBNAIL handle file upload
        if ($request->hasFile('path_thumbnail')) {
            //get filename with the extension
            $filenameWithExt = $request->file('path_thumbnail')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('path_thumbnail')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_thumbnail_' . time() . '.' . $extension;

            $fullPath = '';
            $tempPOI = $poimedia->poi_id;
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
            $path = $request->file('path_thumbnail')->storeAs("public/media/{$fullPath}", $fileNameToStore);
            //save only the image on the right folder path
            $poimedia->path_thumbnail = $fullPath . "/" . $fileNameToStore;
            $poimedia->save();
        }


        $poi = Poi::find($poimedia->poi_id);
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

        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacons)->with('outdoor_groups', $outdoor_groups)->with("poiartifact", $poiartifact)->with("poiartifact_foundon", $poiartifact_foundon);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $poimedia = Poimedia::find($id);
        $pmtype = Poimediatype::find($poimedia->type);
        $pois = Poi::get();
        return view('poimedia.show')->with('poimedia', $poimedia)->with('pmtype', $pmtype)->with('pois', $pois);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $poimedia = Poimedia::find($id);
        $pmtype = Poimediatype::get();
        $pmtype_current = Poimediatype::find($poimedia->type);
        $pois = Poi::get();
        return view('poimedia.edit')->with('poimedia', $poimedia)->with('pmtype', $pmtype)->with('pmtype_current', $pmtype_current)->with('pois', $pois);
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
            'type' => 'required'
        ]);

        $pmtype = Poimediatype::find($request->input('type'));

        if (strpos($pmtype->name, 'Text') !== false) //checks if type contains the word TEXT
        {
            //create Poimedia (TEXT)
            $poimedia = Poimedia::find($id);
            $TEMPtype = $poimedia->type;
            if (strpos($TEMPtype, 'text') !== false) //delete the previous FILE if exist
            {
            } else { //delete the previous FILE if exist
                Storage::delete('public/media/' . $poimedia->uri);
            }
            $poimedia->name = $request->input('name');
            $poimedia->type = $request->input('type');
            $poimedia->uri = $request->input('uri');
            $poimedia->artifact_poi_id = $request->input('artifact_poi_id');
            $poimedia->save();
        } else // type is FILE
        {
            //create Poimedia (FILE)
            $poimedia = Poimedia::find($id);
            $TEMPtype = $poimedia->type;
            $poimedia->name = $request->input('name');
            $poimedia->type = $request->input('type');
            $poimedia->artifact_poi_id = $request->input('artifact_poi_id');
            $poimedia->save();

            //handle file upload
            if ($request->hasFile('uri')) {
                if (strpos($TEMPtype, 'text') !== false) //delete the previous FILE if exist
                {
                } else { //delete the previous FILE if exist
                    Storage::delete('public/media/' . $poimedia->uri);

                    if (($pmtype->name == "360 Tour") || ($pmtype->name == "360 Tour VR")) {
                        Storage::deleteDirectory('public/media/' . $poimedia->uri);
                    }
                }

                //get filename with the extension
                $filenameWithExt = $request->file('uri')->getClientOriginalName();
                //get just $filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                //get just the extension
                $extension = $request->file('uri')->getClientOriginalExtension();
                //filename to store
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $fullPath = '';
                $tempPOI = $poimedia->poi_id;
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
                $path = $request->file('uri')->storeAs("public/media/{$fullPath}", $fileNameToStore);
                //save only the image on the right folder path
                $poimedia->uri = $fullPath . "/" . $fileNameToStore;
                $poimedia->save();

                //if($extension=="zip"){  IN CASE YOU WANT TO CONSIDER ALL ZIP FILES JUST REPLACE THIS LINE WITH THE BELOW
                if (($extension == "zip") && (($pmtype->name == "360 Tour") || ($pmtype->name == "360 Tour VR"))) {
                    $path = Storage::path('public/media/' . $poimedia->uri);
                    $zip = new \ZipArchive();
                    $res = $zip->open($path);
                    if ($res == TRUE) {
                        $directory_name = explode(".", $fileNameToStore)[0];
                        Storage::disk('public')->makeDirectory("media/" . $fullPath . "/" . $directory_name);
                        $unzip_success = $zip->extractTo('../storage/app/public/media/' . $fullPath . "/" . $directory_name);
                        $converted_res = $unzip_success ? 'true' : 'false';
                        @$zip->close();
                        Storage::delete('public/media/' . $poimedia->uri);
                        $poimedia->uri = $fullPath . "/" . $directory_name;
                        $poimedia->save();
                    }
                }
            }

            //handle file 2 upload
            if ($request->hasFile('uri2')) {
                if (strpos($TEMPtype, 'text') !== false) //delete the previous FILE if exist
                {
                } else { //delete the previous FILE if exist
                    Storage::delete('public/media/' . $poimedia->uri2);
                }

                //get filename with the extension
                $filenameWithExt = $request->file('uri2')->getClientOriginalName();
                //get just $filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                //get just the extension
                $extension = $request->file('uri2')->getClientOriginalExtension();
                //filename to store
                $fileNameToStore = $filename . '_uri2_' . time() . '.' . $extension;

                $fullPath = '';
                $tempPOI = $poimedia->poi_id;
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
                $path = $request->file('uri2')->storeAs("public/media/{$fullPath}", $fileNameToStore);
                //save only the image on the right folder path
                $poimedia->uri2 = $fullPath . "/" . $fileNameToStore;
                $poimedia->save();
            }
        }

        //THUMBNAIL handle file upload
        if ($request->hasFile('path_thumbnail')) {
            Storage::delete('public/media/' . $poimedia->path_thumbnail); //delete the previous thumbnail if exist

            //get filename with the extension
            $filenameWithExt = $request->file('path_thumbnail')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('path_thumbnail')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_thumbnail_' . time() . '.' . $extension;

            $fullPath = '';
            $tempPOI = $poimedia->poi_id;
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
            $path = $request->file('path_thumbnail')->storeAs("public/media/{$fullPath}", $fileNameToStore);
            //save only the image on the right folder path
            $poimedia->path_thumbnail = $fullPath . "/" . $fileNameToStore;
            $poimedia->save();
        }

        $poi = Poi::find($poimedia->poi_id);
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

        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacons)->with('outdoor_groups', $outdoor_groups)->with("poiartifact", $poiartifact)->with("poiartifact_foundon", $poiartifact_foundon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $poimedia = Poimedia::find($id);
        $pmtype = Poimediatype::find($poimedia->type);

        if (strpos($pmtype->name, 'Text') !== false) //delete the previous FILE if exist
        {
        } else { //delete the previous FILE if exist

            Storage::delete('public/media/' . $poimedia->uri);
            Storage::delete('public/media/' . $poimedia->uri2);

            if (($pmtype->name == "360 Tour") || ($pmtype->name == "360 Tour VR")) {
                Storage::deleteDirectory('public/media/' . $poimedia->uri);
            }
        }

        Storage::delete('public/media/' . $poimedia->path_thumbnail);
        Qr_room_link::where('poi_media_id', $poimedia->id)->delete();

        $poi = Poi::find($poimedia->poi_id);
        $poimedia->delete(); //SEARCH for POI first and then DELETE the POI Media
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

        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacons)->with('outdoor_groups', $outdoor_groups)->with("poiartifact", $poiartifact)->with("poiartifact_foundon", $poiartifact_foundon);
    }
}
