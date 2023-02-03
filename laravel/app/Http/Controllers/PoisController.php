<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Poi;
use App\Site;
use App\Poichildren;
use App\Poimedia;
use App\Poimediatype;
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
use App\Outdoor_group;
use App\Outdoor_link;
use DB;
use Tymon\JWTAuth\Payload;

class PoisController extends Controller
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

    public function api_get_kG_data(Request $request)
    {
        #echo $request->textarea;
        /*  $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json'
        ])->post('http://10.16.20.19:8080/factextraction/analyze', [
            'docId' => 'doc1',
            'text' => $request->input('text'),
            'extractConcepts' => 'true',
            'language' => 'en',
        ]);*/
        $pois = Poi::get();
        foreach ($pois as $poi) {
            //$url = 'http://10.16.20.19:8080/factextraction/analyze';
            $url = 'http://10.16.30.155:8080/factextraction/analyze';
            //The data you want to send via POST
            $Headers = [
                'accept: application/json',
                'content-type: application/json'
            ];
           /* $fields = [
                "docId" => 'doc1',
                "language" => 'en',
                "text" => $request->input('text'),
                "extractConcepts:" => 'true'
            ];*/

            //$payload = json_encode($fields);
            $ch = curl_init();
            // return $payload;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, '{"docId": "doc1","language": "en", "text": "Jack founded Alibaba with investments from SoftBank and Goldman.", "extractConcepts": "true" }');
            $poi->name=str_replace('"','\'',$poi->name);
            $poi->name=str_replace('(','',$poi->name);
            $poi->description=str_replace('"','\'',$poi->description);
            $poi->description=str_replace(')','',$poi->description);
            $encoded='{"docId": "doc1","language": "en", "text": "' .$poi->name .$poi->description. '", "extractConcepts": "true" }';
            $encoded=str_replace("\n","",$encoded);
            $encoded=str_replace("\r","",$encoded);

            curl_setopt($ch, CURLOPT_POSTFIELDS,$encoded);
            //curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //execute post
            $result = curl_exec($ch);
            echo $result;
            $poi->kb_static = $result;
            $poi->save();
        return response(['status' => 'success']);

        //return response(['status' => 'success', 'response' => $response]);
        }
    }


    public function get_kb_by_poiid(Request $request)
    {
        $data = Poi::find($request->input('id'));

        if (strpos($data->kb_static, 'docId') !== false)
        {
            $manage = json_decode($data->kb_static);
            //echo json_encode($manage);
            $best_matches=array();

            //OLD APPROCH- MATCHES the entities and matches with the id (id link)
            /*foreach ($manage->matches as $record) 
            {
                if (isset($record->entity->id))
                {
                    $best_matches[sizeof($best_matches)] = array('confidence' => $record->entity->confidence,'id' => $record->entity->id);                    
                }
           
                echo '\n ------';  
            }
            rsort($best_matches);
            //print_r($best_matches);
            $final_resultset=[];
            $counter=1;

            foreach ($best_matches as $match) 
            {              
                print_r($match['id']);
                foreach ($manage->entities as $record) 
                {
                    if ($record->id==$match['id'])
                    {
                        //$final_resultset[sizeof($final_resultset)] = array('confidence' => $match['confidence'] ,'id' => $match['id'], $record->name, $record->url);
                        $final_resultset[sizeof($final_resultset)] = array($record->name, $record->url);
                    }
                }

                if ($counter==3)
                {
                    break;
                }
                $counter=$counter+1;
            }

            print_r($final_resultset);*/

            //NEW APPROCH- returns best salience results
            $final_resultset=[];
            foreach ($manage->entities as $record) 
            {
                    $final_resultset[sizeof($final_resultset)] = array($record->salience, $record->name, $record->url);
            }
            rsort($final_resultset);
            $final_resultset = array_slice($final_resultset, 0, 3);
            //print_r($final_resultset);

            return response(['status' => 'success', 'data' => $final_resultset]);
        }
        else
        {

            return response(['status' => 'success', 'data' => NULL]);
        }


    }

    public function api_getpois()
    {
        return response(['status' => 'success', 'data' => Poi::all()]);
        //return response()->json(Poi::all(), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
    }

    public function api_getpoi(Request $request)
    {
        //This commented out section is a prior version. It has been changed to include the counters as well
        /*$data = Poi::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);*/
        //return response()->json(Poi::find($id), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);

        $data = Poi::find($request->input('id'));

        $sitesWithMainPOI= Site::where('main_poi', '=', $request->input('id'))->get();
        $myArray = [];
        foreach ($sitesWithMainPOI as $siteWithMainPOI) {
            $MainPoi = Poi::find($siteWithMainPOI->main_poi);

            $array1 = array('site' => $siteWithMainPOI);
            $array2 = array('mainPOI' => $MainPoi);
            $array3 = [];
            $CounterPoiMediaImage = 0;
            $CounterPoiMediaVideo = 0;
            $CounterPoiMediaZip = 0;
            $CounterPoiMediaStoryTelling = 0;
            $CounterPoiMedia360Tour = 0;
            $CounterPoiMedia360TourVR = 0;
            $CounterPoiMedia360Single = 0;
            $CounterPoiMedia3DMobile = 0;

            //$poi = Poi::find($siteWithMainPOI->main_poi);
            $tempTable = [$siteWithMainPOI->main_poi];

            $isZero = false;
            while ($isZero == false) {
                $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
                //find media...
                $mediaTypeImageID = Poimediatype::where('name', 'Image File')->get();
                $poiMediaImage = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaTypeImageID[0]->id)->get();
                $CounterPoiMediaImage = $CounterPoiMediaImage + count($poiMediaImage);

                $mediaTypeVideoID = Poimediatype::where('name', 'Video File')->get();
                $poiMediaVideo = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaTypeVideoID[0]->id)->get();
                $CounterPoiMediaVideo = $CounterPoiMediaVideo + count($poiMediaVideo);

                $mediaTypeZipID = Poimediatype::where('name', 'LIKE', '%Zip%')->get();
                $poiMediaZip = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaTypeZipID[0]->id)->get();
                $CounterPoiMediaZip = $CounterPoiMediaZip + count($poiMediaZip);

                $mediaType360TourID = Poimediatype::where('name', '360 Tour')->get();
                $poiMedia360Tour = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType360TourID[0]->id)->get();
                $CounterPoiMedia360Tour = $CounterPoiMedia360Tour + count($poiMedia360Tour);

                $mediaType360TourVRID = Poimediatype::where('name', '360 Tour VR')->get();
                $poiMedia360TourVR = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType360TourVRID[0]->id)->get();
                $CounterPoiMedia360TourVR = $CounterPoiMedia360TourVR + count($poiMedia360TourVR);

                $mediaType360SingleID = Poimediatype::where('name', '360 single (Image)')->get();
                $poiMedia360Single = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType360SingleID[0]->id)->get();
                $CounterPoiMedia360Single = $CounterPoiMedia360Single + count($poiMedia360Single);

                $mediaType3DMobileID = Poimediatype::where('name', '3D Model Mobile (GTFL)')->get();
                if (count($mediaType3DMobileID) > 0) {
                    $poiMedia3DMobile = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType3DMobileID[0]->id)->get();
                    $CounterPoiMedia3DMobile = $CounterPoiMedia3DMobile + count($poiMedia3DMobile);
                }

                $poiMediaStoryTelling = Storytelling::where('poi_id', $tempTable[0])->Where('language', $request->input('language'))->get();
                $CounterPoiMediaStoryTelling = $CounterPoiMediaStoryTelling + count($poiMediaStoryTelling);


                \array_splice($tempTable, 0, 1); //removes 1st record from array
                $loopIter = count($poichildren);
                for ($x = 0; $x < $loopIter; $x++) {
                    array_push($tempTable, $poichildren[$x]->id);
                }
                if (($loopIter == 0) && (count($tempTable) == 0)) {
                    $isZero = true;
                }
            }

            if ($CounterPoiMediaImage != 0)
                $array3[sizeof($array3)] = array('id' => $mediaTypeImageID[0]->id, 'name' => $mediaTypeImageID[0]->name, 'amount' => $CounterPoiMediaImage);
            if ($CounterPoiMediaVideo != 0)
                $array3[sizeof($array3)] = array('id' => $mediaTypeVideoID[0]->id, 'name' => $mediaTypeVideoID[0]->name, 'amount' => $CounterPoiMediaVideo);
            #if ($CounterPoiMediaZip != 0)
            #    $array3[sizeof($array3)] = array('id' => $mediaTypeZipID[0]->id, 'name' => $mediaTypeZipID[0]->name, 'amount' => $CounterPoiMediaZip);
            # if ($CounterPoiMedia360Tour != 0)
            #    $array3[sizeof($array3)] = array('id' => $mediaType360TourID[0]->id, 'name' => $mediaType360TourID[0]->name, 'amount' => $CounterPoiMedia360Tour);
            #if ($CounterPoiMedia360TourVR != 0)
            #    $array3[sizeof($array3)] = array('id' => $mediaType360TourVRID[0]->id, 'name' => $mediaType360TourVRID[0]->name, 'amount' => $CounterPoiMedia360TourVR);
            if ($CounterPoiMedia360Single != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360SingleID[0]->id, 'name' => $mediaType360SingleID[0]->name, 'amount' => $CounterPoiMedia360Single);
            if ($CounterPoiMedia3DMobile != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType3DMobileID[0]->id, 'name' => $mediaType3DMobileID[0]->name, 'amount' => $CounterPoiMedia3DMobile);
            if ($CounterPoiMediaStoryTelling != 0)
                $array3[sizeof($array3)] = array('id' => '999', 'name' => 'Story Telling', 'amount' => $CounterPoiMediaStoryTelling);


            //$array3 = array('media' => $array3);
            //$merged_array = $array1 + $array2 + $array3;
            //array_push($myArray,  $merged_array );

            $langName = '';
            $langDescr = '';
            if (($request->input('language') == 'English')) {
                if (!is_null($siteWithMainPOI->name))
                    $langName = $siteWithMainPOI->name;
                else
                    $langName = $siteWithMainPOI->name;

                if (!is_null($MainPoi->description))
                    $langDescr = $MainPoi->description;
                else
                    $langDescr = $MainPoi->description;
            } else if ($request->input('language') == 'Greek') {
                if (!is_null($siteWithMainPOI->name_gr))
                    $langName = $siteWithMainPOI->name_gr;
                else
                    $langName = $siteWithMainPOI->name;

                if (!is_null($MainPoi->description_gr))
                    $langDescr = $MainPoi->description_gr;
                else
                    $langDescr = $MainPoi->description;
            } else if ($request->input('language') == 'Russian') {
                if (!is_null($siteWithMainPOI->name_ru))
                    $langName = $siteWithMainPOI->name_ru;
                else
                    $langName = $siteWithMainPOI->name;

                if (!is_null($MainPoi->description_ru))
                    $langDescr = $MainPoi->description_ru;
                else
                    $langDescr = $MainPoi->description;
            } else if ($request->input('language') == 'Italian') {
                if (!is_null($siteWithMainPOI->name_it))
                    $langName = $siteWithMainPOI->name_it;
                else
                    $langName = $siteWithMainPOI->name;

                if (!is_null($MainPoi->description_it))
                    $langDescr = $MainPoi->description_it;
                else
                    $langDescr = $MainPoi->description;
            } else if ($request->input('language') == 'French') {
                if (!is_null($siteWithMainPOI->name_fr))
                    $langName = $siteWithMainPOI->name_fr;
                else
                    $langName = $siteWithMainPOI->name;

                if (!is_null($MainPoi->description_fr))
                    $langDescr = $MainPoi->description_fr;
                else
                    $langDescr = $MainPoi->description;
            } else if ($request->input('language') == 'German') {
                if (!is_null($siteWithMainPOI->name_ge))
                    $langName = $siteWithMainPOI->name_ge;
                else
                    $langName = $siteWithMainPOI->name;

                if (!is_null($MainPoi->description_ge))
                    $langDescr = $MainPoi->description_ge;
                else
                    $langDescr = $MainPoi->description;
            } else {
                if (!is_null($siteWithMainPOI->name))
                    $langName = $siteWithMainPOI->name;
                else
                    $langName = $siteWithMainPOI->name;

                if (!is_null($MainPoi->description))
                    $langDescr = $MainPoi->description;
                else
                    $langDescr = $MainPoi->description;
            }

            $coords = explode(',', $MainPoi->coordinates);
            if ((!empty($coords[1])) || (!empty($coords[1]))) {
                $myArray[sizeof($myArray)] = array('id' => $siteWithMainPOI->id, 'name' => $langName, 'description' => $langDescr, 'lat' => $coords[0], 'lon' => $coords[1], 'media' => $array3);
            } else {
                $myArray[sizeof($myArray)] = array('id' => $siteWithMainPOI->id, 'name' => $siteWithMainPOI->name, 'description' => $MainPoi->description, 'lat' => 0, 'lon' => 0, 'media' => $array3);
            }
        }

        return response(['status' => 'success', 'data' => $data, 'site_info' => $myArray ]);


    }

    public function api_getpois_by_parentpoi(Request $request)
    {
        $data = Poi::where('parent_poi', $request->input('id'))->get();
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_getpoi_with_cover_image(Request $request)
    {
        $data = \DB::table('pois')
            ->join('poimedia', 'poimedia.poi_id', '=', 'pois.id')
            ->join('poimediatypes', 'poimediatypes.id', '=', 'poimedia.type')
            ->select('pois.id',  'pois.name as name', 'pois.description', 'pois.year', 'pois.coordinates', 'pois.toponym', 'pois.site_id', 'pois.parent_poi', 'poimedia.name as cover_img_description', 'poimedia.uri as path')
            ->where('pois.id', $request->input('id'))
            ->where('poimediatypes.name', '=', 'POI Cover Image')
            ->get();

        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_getpois_with_cover_image(Request $request)
    {
        $data = \DB::table('pois')
            ->join('poimedia', 'poimedia.poi_id', '=', 'pois.id')
            ->join('poimediatypes', 'poimediatypes.id', '=', 'poimedia.type')
            ->select('pois.id',  'pois.name as name', 'pois.description', 'pois.year', 'pois.coordinates', 'pois.toponym', 'pois.site_id', 'pois.parent_poi', 'poimedia.name as cover_img_description', 'poimedia.uri as path')
            ->where('poimediatypes.name', '=', 'POI Cover Image')
            ->get();

        return response(['status' => 'success', 'data' => $data]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('pois.index');
        $pois = Poi::orderBy('created_at', 'desc')->paginate(10);
        return view('pois.index')->with('pois', $pois);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($siteid)
    {
        $chronologicals = Chronological::get();
        return view('pois.create')->with('siteid', $siteid)->with('chronologicals', $chronologicals);
    }

    public function createsub($parentpoiid)
    {
        $chronologicals = Chronological::get();
        return view('pois.createsub')->with('parentpoiid', $parentpoiid)->with('chronologicals', $chronologicals);
    }

    public function createartifact($poiid)
    {
        $pois = Poi::get();
        return view('pois.addartifact')->with('poiid', $poiid)->with('pois', $pois);
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
            'description' => 'required',
            'coordinates' => 'required',
            'publicly_available' => 'required',
        ]);
        //create POI
        $poi = new Poi;
        $poi->name = $request->input('name');
        $poi->description = $request->input('description');
        $poi->description_gr = $request->input('description_gr');
        $poi->description_ru = $request->input('description_ru');
        $poi->description_it = $request->input('description_it');
        $poi->description_fr = $request->input('description_fr');
        $poi->description_ge = $request->input('description_ge');
        $poi->year = $request->input('year');
        $poi->coordinates = $request->input('coordinates');
        $poi->toponym = $request->input('toponym');
        $poi->site_id = $request->input('siteid');
        $poi->parent_poi = 0;
        $poi->chronological_id = $request->input('chronological');
        $poi->outdoor_indoor = $request->input('indoor_outdoor');
        if ($request->input('indoor_outdoor') == 'Outdoor') {
            $poi->indoor_type = "";
        } else {
            $poi->indoor_type = $request->input('indoor_type');
        }
        $poi->sub_chronological_id = $request->input('sub_chronological');
        $poi->name_gr = $request->input('name_gr');
        $poi->name_ru = $request->input('name_ru');
        $poi->name_it = $request->input('name_it');
        $poi->name_fr = $request->input('name_fr');
        $poi->name_ge = $request->input('name_ge');
        $poi->toponym_gr = $request->input('toponym_gr');
        $poi->toponym_ru = $request->input('toponym_ru');
        $poi->toponym_it = $request->input('toponym_it');
        $poi->toponym_fr = $request->input('toponym_fr');
        $poi->toponym_ge = $request->input('toponym_ge');
        $poi->publicly_available = $request->input('publicly_available');
        $poi->save();

        //return redirect('/sites')->with('success', 'POI Created');
        $site = Site::find($request->input('siteid'));
        $site->main_poi = $poi->id;
        $site->save();

        $cydistrict = Cy_district::find($site->district);
        $sitecategory = Sitecategory::find($site->category);

        //$pois= Poi::orderBy('created_at','desc')->paginate(10);
        $siteaccessibility = \DB::table('siteaccessibilities')
            ->join('accessibilities', 'accessibilities.id', '=', 'siteaccessibilities.accessibility_id')
            ->select('accessibilities.name as name', 'siteaccessibilities.id')
            ->where('siteaccessibilities.site_id', $site->id)
            ->get();

        $siteworkinghour = Siteworkinghour::where('site_id', $site->id)->get();

        return view('sites.show')->with('site', $site)->with('poi', $poi)->with('sitecategory', $sitecategory)->with('cydistrict', $cydistrict)->with('siteaccessibility', $siteaccessibility)->with('siteworkinghour', $siteworkinghour);
    }

    public function storesub(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'coordinates' => 'required',
            'publicly_available' => 'required',
        ]);

        //create POI
        $poi = new Poi;
        $poi->name = $request->input('name');
        $poi->description = $request->input('description');
        $poi->description_gr = $request->input('description_gr');
        $poi->description_ru = $request->input('description_ru');
        $poi->description_it = $request->input('description_it');
        $poi->description_fr = $request->input('description_fr');
        $poi->description_ge = $request->input('description_ge');
        $poi->year = $request->input('year');
        $poi->coordinates = $request->input('coordinates');
        $poi->toponym = $request->input('toponym');
        $poi->site_id = 0;
        $poi->parent_poi = $request->input('parentpoi');
        $poi->chronological_id = $request->input('chronological');
        $poi->sub_chronological_id = $request->input('sub_chronological');
        $poi->name_gr = $request->input('name_gr');
        $poi->name_ru = $request->input('name_ru');
        $poi->name_it = $request->input('name_it');
        $poi->name_fr = $request->input('name_fr');
        $poi->name_ge = $request->input('name_ge');
        $poi->toponym_gr = $request->input('toponym_gr');
        $poi->toponym_ru = $request->input('toponym_ru');
        $poi->toponym_it = $request->input('toponym_it');
        $poi->toponym_fr = $request->input('toponym_fr');
        $poi->toponym_ge = $request->input('toponym_ge');
        $poi->publicly_available = $request->input('publicly_available');
        $poi->save();

        $poichild = new Poichildren;
        $poichild->poi_id = $request->input('parentpoi');
        $poichild->child_poi_id = $poi->id;
        $poichild->save();

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


    public function storeartifacts(Request $request)
    {
        $this->validate($request, [
            'poiid' => 'required',
            'poi_to_be_added' => 'required'
        ]);

        $temp_poi = Poi::find($request->input('poi_to_be_added'));
        $temp_poi->artifact_id = $request->input('poiid');
        $temp_poi->save();


        $poi = Poi::find($request->input('poiid'));
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
    public function searchartifacts(Request $request)
    {
        //return $request->input('poiid') . $request->input('keyword');
        $keyword = $request->input('keyword');
        $pois = Poi::where('name', 'LIKE', "%$keyword%")->get();
        $poiid = $request->input('poiid');
        return view('pois.addartifact')->with('poiid', $poiid)->with('pois', $pois);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $poi = Poi::find($id);
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
        $beacon = Beacon::where('poi_id', $poi->id)->get();
        $outdoor_groups = Outdoor_group::where('poi_id', $poi->id)->get();
        $poiartifact = Poi::where('artifact_id', $poi->id)->get();
        $poiartifact_foundon = Poi::where('id', $poi->artifact_id)->get();


        return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacon)->with('outdoor_groups', $outdoor_groups)->with('poiartifact', $poiartifact)->with('poiartifact_foundon', $poiartifact_foundon);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $poi = Poi::find($id);
        $chronologicals = Chronological::get();
        return view('pois.edit')->with('poi', $poi)->with('chronologicals', $chronologicals);
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
            'description' => 'required',
            'coordinates' => 'required',
            'publicly_available' => 'required',
        ]);
        //update POI and Site
        $poi = Poi::find($id);

        if ($poi->outdoor_indoor == 'Indoor') {
            if (($poi->indoor_type == 'QR codes' || $poi->indoor_type == 'Finger Print') && $request->input('indoor_type') == 'Beacons') {
                $qrooms = Qr_room::where('poi_id', $poi->id)->get();
                foreach ($qrooms as $qroom) {
                    Qr_room_link::where('qr_room_id', $qroom->id)->delete();
                    Qr_room_group::where('qr_room_id', $qroom->id)->delete();
                }

                Qr_room::where('poi_id', $poi->id)->delete();
            }
            if (($poi->indoor_type == 'Beacons' || $poi->indoor_type == 'Finger Print') && $request->input('indoor_type') == 'QR codes') {
                Beacon::where('poi_id', $poi->id)->delete();
            }
            if (($poi->indoor_type == 'QR codes' || $poi->indoor_type == 'Beacons') && $request->input('indoor_type') == 'Finger Print') {
                $qrooms = Qr_room::where('poi_id', $poi->id)->get();
                foreach ($qrooms as $qroom) {
                    Qr_room_link::where('qr_room_id', $qroom->id)->delete();
                    Qr_room_group::where('qr_room_id', $qroom->id)->delete();
                }
                Qr_room::where('poi_id', $poi->id)->delete();
                Beacon::where('poi_id', $poi->id)->delete();
            }
            if (($poi->indoor_type == 'QR codes' || $poi->indoor_type == 'Finger Print' || $poi->indoor_type == 'Beacons') && $request->input('indoor_outdoor') == 'Outdoor') {
                $qrooms = Qr_room::where('poi_id', $poi->id)->get();
                foreach ($qrooms as $qroom) {
                    Qr_room_link::where('qr_room_id', $qroom->id)->delete();
                    Qr_room_group::where('qr_room_id', $qroom->id)->delete();
                }
                Qr_room::where('poi_id', $poi->id)->delete();
                Beacon::where('poi_id', $poi->id)->delete();
            }
        } else if ($poi->outdoor_indoor == 'Outdoor' && $request->input('indoor_outdoor') != 'Outdoor') {
            $outdoor_groups = Outdoor_group::where('poi_id', $poi->id)->get();
            foreach ($outdoor_groups as $outdoor_group) {
                Outdoor_link::where('outdoor_group_id', $outdoor_group->id)->delete();
            }
            Outdoor_group::where('poi_id', $poi->id)->delete();
        }

        $poi->name = $request->input('name');
        $poi->description = $request->input('description');
        $poi->description_gr = $request->input('description_gr');
        $poi->description_ru = $request->input('description_ru');
        $poi->description_it = $request->input('description_it');
        $poi->description_fr = $request->input('description_fr');
        $poi->description_ge = $request->input('description_ge');
        $poi->year = $request->input('year');
        $poi->coordinates = $request->input('coordinates');
        $poi->toponym = $request->input('toponym');
        $poi->chronological_id = $request->input('chronological');
        $poi->outdoor_indoor = $request->input('indoor_outdoor');
        if ($request->input('indoor_outdoor') == 'Outdoor') {
            $poi->indoor_type = "";
        } else {
            $poi->indoor_type = $request->input('indoor_type');
        }
        $poi->sub_chronological_id = $request->input('sub_chronological');
        $poi->name_gr = $request->input('name_gr');
        $poi->name_ru = $request->input('name_ru');
        $poi->name_it = $request->input('name_it');
        $poi->name_fr = $request->input('name_fr');
        $poi->name_ge = $request->input('name_ge');
        $poi->toponym_gr = $request->input('toponym_gr');
        $poi->toponym_ru = $request->input('toponym_ru');
        $poi->toponym_it = $request->input('toponym_it');
        $poi->toponym_fr = $request->input('toponym_fr');
        $poi->toponym_ge = $request->input('toponym_ge');
        $poi->publicly_available = $request->input('publicly_available');
        $poi->save();

        $site = Site::find($poi->site_id);
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
        $beacon = Beacon::where('poi_id', $poi->id)->get();
        $outdoor_groups = Outdoor_group::where('poi_id', $poi->id)->get();
        $poiartifact = Poi::where('artifact_id', $poi->id)->get();
        $poiartifact_foundon = Poi::where('id', $poi->artifact_id)->get();

        if ($poi->site_id != 0) {
            $cydistrict = Cy_district::find($site->district);
            $sitecategory = Sitecategory::find($site->category);

            $siteaccessibility = \DB::table('siteaccessibilities')
                ->join('accessibilities', 'accessibilities.id', '=', 'siteaccessibilities.accessibility_id')
                ->select('accessibilities.name as name', 'siteaccessibilities.id')
                ->where('siteaccessibilities.site_id', $site->id)
                ->get();

            $siteworkinghour = Siteworkinghour::where('site_id', $site->id)->get();

            return view('sites.show')->with('site', $site)->with('poi', $poi)->with('sitecategory', $sitecategory)->with('cydistrict', $cydistrict)->with('siteaccessibility', $siteaccessibility)->with('siteworkinghour', $siteworkinghour);
        } else {
            return view('pois.show')->with('site', $site)->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacon)->with('outdoor_groups', $outdoor_groups)->with('poiartifact', $poiartifact)->with('poiartifact_foundon', $poiartifact_foundon);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $poiToDelete = Poi::find($id);

        //FINDS AND DELETES THE PATH OF THE POI
        $fullPath = '';
        $tempPOI = $poiToDelete->id;
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
        //FINDS AND DELETES THE PATH OF THE POI
        Storage::deleteDirectory("public/media/{$fullPath}");
        //------------------------------------------------------------

        if ($poiToDelete->site_id != 0) {
            $site = Site::find($poiToDelete->site_id);
            $site->main_poi = 0;
            $site->save();

            $tempTable = [$poiToDelete->id];
            $isZero = false;
            while ($isZero == false) {
                //print_r($tempTable);
                $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
                Poi::where('id', $tempTable[0])->delete();
                Poichildren::where('poi_id', $tempTable[0])->delete();
                Poichildren::where('child_poi_id', $tempTable[0])->delete();
                Poimedia::where('poi_id', $tempTable[0])->delete();
                Storytelling::where('poi_id', $tempTable[0])->delete();
                $qrooms = Qr_room::where('poi_id', $poi->id)->get();
                foreach ($qrooms as $qroom) {
                    Qr_room_link::where('qr_room_id', $qroom->id)->delete();
                    Qr_room_group::where('qr_room_id', $qroom->id)->delete();
                }
                Qr_room::where('poi_id', $tempTable[0])->delete();
                Beacon::where('poi_id', $tempTable[0])->delete();
                $outdoor_groups = Outdoor_group::where('poi_id', $tempTable[0])->get();
                foreach ($outdoor_groups as $outdoor_group) {
                    Outdoor_link::where('outdoor_group_id', $outdoor_group->id)->delete();
                }
                Outdoor_group::where('poi_id', $tempTable[0])->delete();
                Poirate::where('poi_id', $tempTable[0])->delete();
                Poitag::where('poi_id', $tempTable[0])->delete();
                \array_splice($tempTable, 0, 1); //removes 1st record from array
                $loopIter = count($poichildren);
                for ($x = 0; $x < $loopIter; $x++) {
                    array_push($tempTable, $poichildren[$x]->id);
                }
                if (($loopIter == 0) && (count($tempTable) == 0)) {
                    $isZero = true;
                }
            }

            $poi = Poi::find(0);
            $cydistrict = Cy_district::find($site->district);
            $sitecategory = Sitecategory::find($site->category);
            //return redirect('/sites/{{$poi->site_id}}')->with('success', 'POI Removed');
            $siteaccessibility = \DB::table('siteaccessibilities')
                ->join('accessibilities', 'accessibilities.id', '=', 'siteaccessibilities.accessibility_id')
                ->select('accessibilities.name as name', 'siteaccessibilities.id')
                ->where('siteaccessibilities.site_id', $site->id)
                ->get();

            $siteworkinghour = Siteworkinghour::where('site_id', $site->id)->get();

            return view('sites.show')->with('site', $site)->with('poi', $poi)->with('sitecategory', $sitecategory)->with('cydistrict', $cydistrict)->with('siteaccessibility', $siteaccessibility)->with('siteworkinghour', $siteworkinghour);
        } else {
            $poi = Poi::find($poiToDelete->parent_poi);

            $tempTable = [$poiToDelete->id];
            $isZero = false;
            while ($isZero == false) {
                //print_r($tempTable);
                $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
                Poi::where('id', $tempTable[0])->delete();
                Poichildren::where('poi_id', $tempTable[0])->delete();
                Poichildren::where('child_poi_id', $tempTable[0])->delete();
                Poimedia::where('poi_id', $tempTable[0])->delete();
                Storytelling::where('poi_id', $tempTable[0])->delete();
                $qrooms = Qr_room::where('poi_id', $poi->id)->get();
                foreach ($qrooms as $qroom) {
                    Qr_room_link::where('qr_room_id', $qroom->id)->delete();
                    Qr_room_group::where('qr_room_id', $qroom->id)->delete();
                }
                Qr_room::where('poi_id', $tempTable[0])->delete();
                Beacon::where('poi_id', $tempTable[0])->delete();
                $outdoor_groups = Outdoor_group::where('poi_id', $tempTable[0])->get();
                foreach ($outdoor_groups as $outdoor_group) {
                    Outdoor_link::where('outdoor_group_id', $outdoor_group->id)->delete();
                }
                Outdoor_group::where('poi_id', $tempTable[0])->delete();
                Poirate::where('poi_id', $tempTable[0])->delete();
                Poitag::where('poi_id', $tempTable[0])->delete();
                \array_splice($tempTable, 0, 1); //removes 1st record from array
                $loopIter = count($poichildren);
                for ($x = 0; $x < $loopIter; $x++) {
                    array_push($tempTable, $poichildren[$x]->id);
                }
                if (($loopIter == 0) && (count($tempTable) == 0)) {
                    $isZero = true;
                }
            }

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
            $beacon = Beacon::where('poi_id', $poi->id)->get();
            $outdoor_groups = Outdoor_group::where('poi_id', $poi->id)->get();
            $poiartifact = Poi::where('artifact_id', $poi->id)->get();
            $poiartifact_foundon = Poi::where('id', $poi->artifact_id)->get();

            return view('pois.show')->with('poi', $poi)->with('poichildren', $poichildren)->with('poimedia', $poimedia)->with('poitag', $poitag)->with('chronological', $chronological)->with('sub_chronological', $sub_chronological)->with('storytelling', $storytelling)->with('qrrooms', $qrrooms)->with('beacons', $beacon)->with('outdoor_groups', $outdoor_groups)->with("poiartifact", $poiartifact)->with("poiartifact_foundon", $poiartifact_foundon);
        }
    }


    public function removeartifact($id)
    {
        $poiartifact_to_be_removed = Poi::find($id);
        $poi = Poi::find($poiartifact_to_be_removed->artifact_id);
        $poiartifact_to_be_removed->artifact_id = NULL;
        $poiartifact_to_be_removed->save();

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
