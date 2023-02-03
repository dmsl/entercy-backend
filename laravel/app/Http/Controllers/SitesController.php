<?php

namespace App\Http\Controllers;

use App\Beacon;
use App\Chronological;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Site;
use App\Poi;
use App\Poichildren;
use App\Poimedia;
use App\Poimediatype;
use App\User;
use App\Cy_district;
use App\Sitecategory;
use App\Siteaccessibility;
use App\Siteworkinghour;
use App\Poitag;
use App\Storytelling;
use App\Poirate;
use App\Thematicroutesite;
use App\Qr_room;
use App\Qr_room_link;
use App\Qr_room_group;
use App\Outdoor_group;
use App\Outdoor_link;
use App\Userjourney;
use DB;


class SitesController extends Controller
{
    /** REMEMBER TO ADD to prevent unauthorised users to create/view/edit data
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {

      //if from web:
        $this->middleware('auth');
        //else if from api
        // auth:api
    }*/
    public function api_getsites_by_AR(Request $request)
    {
        $outdoor_indoor_type = $request->input('outdoor_indoor');
        if ($outdoor_indoor_type == 'Indoor') {
            $pois = Poi::where('outdoor_indoor', $outdoor_indoor_type)->where('indoor_type', $request->input('type'))->get();
        } else if ($outdoor_indoor_type == 'Outdoor') {
            $pois = Poi::where('outdoor_indoor', $outdoor_indoor_type)->get();
        }

        $sites = [];
        foreach ($pois as $poi) {
            $site = Site::where('main_poi', $poi->id)->get();
            array_push($sites, $site[0]);
        }
        if (empty($sites)) {
            return response(['status' => 'success', 'data' => '']);
        }
        return response(['status' => 'success', 'data' => $sites]);
    }


    public function api_getsites_by_outdoor_groups(Request $request)
    {
        $outdoor_group_id = $request->input('outdoor_group_id');
        $outdoor_group = Outdoor_group::find($outdoor_group_id);

        $sites = [];
        if (isset($outdoor_group)) {
            $poi = Poi::find($outdoor_group->poi_id);
            $site = Site::where('main_poi', $poi->id)->get();
            array_push($sites, $site[0]);
        }
        if (empty($sites)) {
            return response(['status' => 'success', 'data' => '']);
        }
        return response(['status' => 'success', 'data' => $sites]);
    }

    public function api_getsites_by_outdoor_links(Request $request)
    {
        $outdoor_link_id = $request->input('outdoor_link_id');
        $outdoor_link = Outdoor_link::find($outdoor_link_id);

        $sites = [];
        if (isset($outdoor_link)) {
            $outdoor_group = Outdoor_group::find($outdoor_link->outdoor_group_id);
            $poi = Poi::find($outdoor_group->poi_id);
            $site = Site::where('main_poi', $poi->id)->get();
            array_push($sites, $site[0]);
        }
        if (empty($sites)) {
            return response(['status' => 'success', 'data' => '']);
        }
        return response(['status' => 'success', 'data' => $sites]);
    }

    public function api_getsites_by_rooms(Request $request)
    {
        $site_id = $request->input('site_id');
        $site = Site::find($site_id);

        if (empty($site)) {
            return response(['status' => 'success', 'qrroom' => '']);
        }

        $qrroom = Qr_room::where('poi_id', $site->main_poi)->get();

        if (empty($qrroom)) {
            return response(['status' => 'success', 'qrroom' => '']);
        }

        return response(['status' => 'success', 'qrroom' => $qrroom]);
    }


    public function api_getsites_by_groups(Request $request)
    {
        //sto trito api san input na einai sited id kai roomid kai output mono ta groupss autou tou room an estw kai ena exei x position adeio
        $site_id = $request->input('site_id');
        $room_id = $request->input('room_id');

        $qrroom = Qr_room::find($room_id);
        if (empty($qrroom)) {
            return response(['status' => 'success', 'Qr_room_group' => '']);
        }
        $qr_room_groups = Qr_room_group::where('qr_room_id', $qrroom->id)->get();
        $room_groups = [];
        foreach ($qr_room_groups as $qr_room_group) {
            array_push($room_groups, $qr_room_group);
        }

        return response(['status' => 'success', 'Qr_room_group' => $room_groups]);
    }

    public function api_get_groups_by_outdoor_sites(Request $request)
    {
        $poi_id = $request->input('poi_id');
        $outdoor_groups = Outdoor_group::where('poi_id', $poi_id)->get();
        $result_groups = [];
        foreach ($outdoor_groups as $outdoor_group) {
            array_push($result_groups, $outdoor_group);
        }

        return response(['status' => 'success', 'Outdoor_groups' => $result_groups]);
    }


    public function api_savesitegroup_by_groupID(Request $request)
    {
        //api savesitegroup site_id type group_id input xyz kai ta 9 xyz

       // $site_id = $request->input('site_id');
        $outdoor_indoor_type = $request->input('outdoor_indoor');
        $indoor_type = $request->input('type');
        $group_id = $request->input('group_id');

        //elegxos outdoor_indoor kai type
        if ($outdoor_indoor_type == 'Indoor') {
            if ($indoor_type == 'QR codes') {
                $qr_room_group = Qr_room_group::find($group_id);

                if (isset($qr_room_group)) {
                    $qr_room_group->x_position = $request->input('x_position');
                    $qr_room_group->y_position = $request->input('y_position');
                    $qr_room_group->z_position = $request->input('z_position');

                    $qr_room_group->x_scale = $request->input('x_scale');
                    $qr_room_group->y_scale = $request->input('y_scale');
                    $qr_room_group->z_scale = $request->input('z_scale');

                    $qr_room_group->x_rotation = $request->input('x_rotation');
                    $qr_room_group->y_rotation = $request->input('y_rotation');
                    $qr_room_group->z_rotation = $request->input('z_rotation');


                    $qr_room_group->save();
                }
            }
            //elegxos outdoor_indoor na einai outdoor sto elseif
        } else if ($outdoor_indoor_type == 'Outdoor') {


            $outdoor_group = Outdoor_group::find($group_id);

            if (isset($outdoor_group)) {
                $outdoor_group->latitude = $request->input('latitude');
                $outdoor_group->longitude = $request->input('longitude');
                $outdoor_group->altitude = $request->input('altitude');

                $outdoor_group->save();
            }
        }

        return response(['status' => 'success', 'value' => 'true']);
    }

    public function api_savesitelink_by_linkID(Request $request)
    {
       // $site_id = $request->input('site_id');
        $outdoor_indoor_type = $request->input('outdoor_indoor');
        $indoor_type = $request->input('type');
        $link_id = $request->input('link_id');
        if ($outdoor_indoor_type == 'Indoor') {
            if ($indoor_type == 'QR codes') {
                $qrroom_linked = Qr_room_link::find($link_id);

                if (isset($qrroom_linked)) {
                    $qrroom_linked->x_position = $request->input('x_position');
                    $qrroom_linked->y_position = $request->input('y_position');
                    $qrroom_linked->z_position = $request->input('z_position');

                    $qrroom_linked->x_scale = $request->input('x_scale');
                    $qrroom_linked->y_scale = $request->input('y_scale');
                    $qrroom_linked->z_scale = $request->input('z_scale');

                    $qrroom_linked->x_rotation = $request->input('x_rotation');
                    $qrroom_linked->y_rotation = $request->input('y_rotation');
                    $qrroom_linked->z_rotation = $request->input('z_rotation');

                    $qrroom_linked->save();
                }
            }
        } else if ($outdoor_indoor_type == 'Outdoor') {
            $outdoor_link = Outdoor_link::find($link_id);
            if (isset($outdoor_link)) {
                $outdoor_link->latitude = $request->input('latitude');
                $outdoor_link->longitude = $request->input('longitude');

                $outdoor_link->x_scale = $request->input('x_scale');
                $outdoor_link->y_scale = $request->input('y_scale');
                $outdoor_link->z_scale = $request->input('z_scale');

                $outdoor_link->x_rotation = $request->input('x_rotation');
                $outdoor_link->y_rotation = $request->input('y_rotation');
                $outdoor_link->z_rotation = $request->input('z_rotation');

                $outdoor_link->save();
            }
        }
        return response(['status' => 'success', 'value' => 'true']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Site::orderBy('created_at', 'desc')->paginate(10);
        return view('sites.index')->with('sites', $sites);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sitecategory = Sitecategory::get();
        $cydistrict = Cy_district::get();
        return view('sites.create')->with('sitecategory', $sitecategory)->with('cydistrict', $cydistrict);
    }

    public function api_getsites()
    {
        $sites = Site::get();
        foreach ($sites as $site) {
            if (($site->fee == '0' || $site->fee == 0) || is_null($site->fee)) {
                $site->fee = "Free entrance";
            }
            $poi = Poi::where('site_id', $site->id)->get();
            $district = Cy_district::where('id', $site->district)->get();
            $category = Sitecategory::where('id', $site->category)->get();
            $site->district_name = $district[0]->name;
            $site->category_name = $category[0]->name;
            if (count($poi) > 0) {
                $pieces = explode(",", $poi[0]->coordinates);
                if (isset($pieces[0]) && isset($pieces[1])) {
                    $site->latitude = $pieces[0];
                    $site->longitude = $pieces[1];
                } else {
                    $site->latitude = $poi[0]->coordinates;
                    $site->longitude = "0";
                }
                $chronological = Chronological::where('id', $poi[0]->chronological_id)->get();
                $site->chronological_id = $poi[0]->chronological_id;
                $site->outdoor_indoor = $poi[0]->outdoor_indoor;
                $site->indoor_type = $poi[0]->indoor_type;
                if (count($chronological) > 0) {
                    $site->chronological_name = $chronological[0]->name;
                }
            }
        }
        return response(['status' => 'success', 'data' => $sites]);
    }

    /*public function api_getsite($id)
    {
        return response()->json(Site::find($id), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
        //return Site::find($id);
    }*/

    public function api_getsite(Request $request)
    {
        $site = Site::find($request->input('id'));
        if (($site->fee == '0' || $site->fee == 0) || is_null($site->fee)) {
            $site->fee = "Free entrance";
        }
        $poi = Poi::where('site_id', $site->id)->get();
        $district = Cy_district::where('id', $site->district)->get();
        $category = Sitecategory::where('id', $site->category)->get();
        $site->district_name = $district[0]->name;
        $site->category_name = $category[0]->name;
        if (count($poi) > 0) {
            $chronological = Chronological::where('id', $poi[0]->chronological_id)->get();
            $site->chronological_id = $poi[0]->chronological_id;
            $site->outdoor_indoor = $poi[0]->outdoor_indoor;
            $site->indoor_type = $poi[0]->indoor_type;
            if (count($chronological) > 0) {
                $site->chronological_name = $chronological[0]->name;
            }
        }
        return response(['status' => 'success', 'data' => $site]);
        //return response()->json(Site::find($id), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
    }

    public function api_getsites_by_district(Request $request)
    {
        /*$data=Site::where('district', $request->input('id'))->get();
        return response(['status' => 'success', 'data' => $data]);*/

        $sitesWithMainPOI = Site::where('main_poi', '!=', 0)->where('district', $request->input('id'))->get();
        $myArray = [];

        foreach ($sitesWithMainPOI as $siteWithMainPOI) {
            $MainPoi = Poi::find($siteWithMainPOI->main_poi);
            $MainPoi->makeHidden(['kb_static']);

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
            $CounterPoiMedia3DModelMobile = 0;

            $poi = Poi::find($siteWithMainPOI->main_poi);
            $poi->makeHidden(['kb_static']);
            $tempTable = [$siteWithMainPOI->main_poi];
            $isZero = false;
            while ($isZero == false) {
                $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
                $poichildren->makeHidden(['kb_static']);
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

                $mediaType3DModelMobileID = Poimediatype::where('name', '3D Model Mobile (GTFL)')->get();
                $poiMedia3DModelMobile = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType3DModelMobileID[0]->id)->get();
                $CounterPoiMedia3DModelMobile = $CounterPoiMedia3DModelMobile + count($poiMedia3DModelMobile);

                $poiMediaStoryTelling = Storytelling::where('poi_id', $tempTable[0])->get();
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
            if ($CounterPoiMediaZip != 0)
                $array3[sizeof($array3)] = array('id' => $mediaTypeZipID[0]->id, 'name' => $mediaTypeZipID[0]->name, 'amount' => $CounterPoiMediaZip);
            if ($CounterPoiMedia360Tour != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360TourID[0]->id, 'name' => $mediaType360TourID[0]->name, 'amount' => $CounterPoiMedia360Tour);
            if ($CounterPoiMedia360TourVR != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360TourVRID[0]->id, 'name' => $mediaType360TourVRID[0]->name, 'amount' => $CounterPoiMedia360TourVR);
            if ($CounterPoiMedia360Single != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360SingleID[0]->id, 'name' => $mediaType360SingleID[0]->name, 'amount' => $CounterPoiMedia360Single);
            if ($CounterPoiMedia3DModelMobile != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType3DModelMobileID[0]->id, 'name' => $mediaType3DModelMobileID[0]->name, 'amount' => $CounterPoiMedia3DModelMobile);
            if ($CounterPoiMediaStoryTelling != 0)
                $array3[sizeof($array3)] = array('id' => '999', 'name' => 'Story Telling', 'amount' => $CounterPoiMediaStoryTelling);

            $array3 = array('media' => $array3);
            $merged_array = $array1 + $array2 + $array3;
            array_push($myArray,  $merged_array);
        }

        return response(['status' => 'success', 'data' => $myArray]);
    }

    public function api_getsites_by_category(Request $request)
    {
        /*$data=Site::where('category', $request->input('id'))->get();
        return response(['status' => 'success', 'data' => $data]);*/

        $sitesWithMainPOI = Site::where('main_poi', '!=', 0)->where('category', $request->input('id'))->get();
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
            $CounterPoiMedia3DModelMobile = 0;

            $poi = Poi::find($siteWithMainPOI->main_poi);
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

                $mediaType3DModelMobileID = Poimediatype::where('name', '3D Model Mobile (GTFL)')->get();
                $poiMedia3DModelMobile = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType3DModelMobileID[0]->id)->get();
                $CounterPoiMedia3DModelMobile = $CounterPoiMedia3DModelMobile + count($poiMedia3DModelMobile);

                $poiMediaStoryTelling = Storytelling::where('poi_id', $tempTable[0])->get();
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
            if ($CounterPoiMediaZip != 0)
                $array3[sizeof($array3)] = array('id' => $mediaTypeZipID[0]->id, 'name' => $mediaTypeZipID[0]->name, 'amount' => $CounterPoiMediaZip);
            if ($CounterPoiMedia360Tour != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360TourID[0]->id, 'name' => $mediaType360TourID[0]->name, 'amount' => $CounterPoiMedia360Tour);
            if ($CounterPoiMedia360TourVR != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360TourVRID[0]->id, 'name' => $mediaType360TourVRID[0]->name, 'amount' => $CounterPoiMedia360TourVR);
            if ($CounterPoiMedia360Single != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360SingleID[0]->id, 'name' => $mediaType360SingleID[0]->name, 'amount' => $CounterPoiMedia360Single);
            if ($CounterPoiMedia3DModelMobile != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType3DModelMobileID[0]->id, 'name' => $mediaType3DModelMobileID[0]->name, 'amount' => $CounterPoiMedia3DModelMobile);
            if ($CounterPoiMediaStoryTelling != 0)
                $array3[sizeof($array3)] = array('id' => '999', 'name' => 'Story Telling', 'amount' => $CounterPoiMediaStoryTelling);

            $array3 = array('media' => $array3);
            $merged_array = $array1 + $array2 + $array3;
            array_push($myArray,  $merged_array);
        }

        return response(['status' => 'success', 'data' => $myArray]);
    }

    public function api_getsites_by_chronological(Request $request)
    {
        $sitesWithMainPOI = Site::where('main_poi', '!=', 0)->get();
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
            $CounterPoiMedia3DModelMobile = 0;

            $poi = Poi::find($siteWithMainPOI->main_poi);
            $tempTable = [$siteWithMainPOI->main_poi];
            $isZero = false;
            $chronPOIfoundEvenAfterMainPOI = false;
            while ($isZero == false) {
                $poichildren = Poi::where('parent_poi', $tempTable[0])->get();

                //checks if THIS POI has the require chronological ID
                $doesThisPOIhasThisChronolID = Poi::where('id', $tempTable[0])->where('chronological_id', $request->input('id'))->get();
                if (count($doesThisPOIhasThisChronolID) > 0) //checks if THIS POI has the require chronological ID
                {
                    //means that it has the require chronological ID
                    $chronPOIfoundEvenAfterMainPOI = true;

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

                    $mediaType3DModelMobileID = Poimediatype::where('name', '3D Model Mobile (GTFL)')->get();
                    $poiMedia3DModelMobile = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType3DModelMobileID[0]->id)->get();
                    $CounterPoiMedia3DModelMobile = $CounterPoiMedia3DModelMobile + count($poiMedia3DModelMobile);

                    $poiMediaStoryTelling = Storytelling::where('poi_id', $tempTable[0])->get();
                    $CounterPoiMediaStoryTelling = $CounterPoiMediaStoryTelling + count($poiMediaStoryTelling);
                }


                \array_splice($tempTable, 0, 1); //removes 1st record from array
                $loopIter = count($poichildren);
                for ($x = 0; $x < $loopIter; $x++) {
                    array_push($tempTable, $poichildren[$x]->id);
                }
                if (($loopIter == 0) && (count($tempTable) == 0)) {
                    $isZero = true;
                }
            }

            if ($chronPOIfoundEvenAfterMainPOI == true) {
                if ($CounterPoiMediaImage != 0)
                    $array3[sizeof($array3)] = array('id' => $mediaTypeImageID[0]->id, 'name' => $mediaTypeImageID[0]->name, 'amount' => $CounterPoiMediaImage);
                if ($CounterPoiMediaVideo != 0)
                    $array3[sizeof($array3)] = array('id' => $mediaTypeVideoID[0]->id, 'name' => $mediaTypeVideoID[0]->name, 'amount' => $CounterPoiMediaVideo);
                if ($CounterPoiMediaZip != 0)
                    $array3[sizeof($array3)] = array('id' => $mediaTypeZipID[0]->id, 'name' => $mediaTypeZipID[0]->name, 'amount' => $CounterPoiMediaZip);
                if ($CounterPoiMedia360Tour != 0)
                    $array3[sizeof($array3)] = array('id' => $mediaType360TourID[0]->id, 'name' => $mediaType360TourID[0]->name, 'amount' => $CounterPoiMedia360Tour);
                if ($CounterPoiMedia360TourVR != 0)
                    $array3[sizeof($array3)] = array('id' => $mediaType360TourVRID[0]->id, 'name' => $mediaType360TourVRID[0]->name, 'amount' => $CounterPoiMedia360TourVR);
                if ($CounterPoiMedia360Single != 0)
                    $array3[sizeof($array3)] = array('id' => $mediaType360SingleID[0]->id, 'name' => $mediaType360SingleID[0]->name, 'amount' => $CounterPoiMedia360Single);
                if ($CounterPoiMedia3DModelMobile != 0)
                    $array3[sizeof($array3)] = array('id' => $mediaType3DModelMobileID[0]->id, 'name' => $mediaType3DModelMobileID[0]->name, 'amount' => $CounterPoiMedia3DModelMobile);
                if ($CounterPoiMediaStoryTelling != 0)
                    $array3[sizeof($array3)] = array('id' => '999', 'name' => 'Story Telling', 'amount' => $CounterPoiMediaStoryTelling);

                $array3 = array('media' => $array3);
                $merged_array = $array1 + $array2 + $array3;
                array_push($myArray,  $merged_array);
            }
        }

        return response(['status' => 'success', 'data' => $myArray]);
    }


    public function api_get_all_sites(Request $request)
    {
        /*$data=Site::where('district', $request->input('id'))->get();
        return response(['status' => 'success', 'data' => $data]);*/

        $sitesWithMainPOI = Site::where('main_poi', '!=', 0)->get();
        $myArray = [];

        foreach ($sitesWithMainPOI as $siteWithMainPOI) {
            $MainPoi = Poi::find($siteWithMainPOI->main_poi);
            $MainPoi->makeHidden(['kb_static']);

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
            $CounterPoiMedia3DModelMobile = 0;

            $poi = Poi::find($siteWithMainPOI->main_poi);
            $poi->makeHidden(['kb_static']);
            $tempTable = [$siteWithMainPOI->main_poi];
            $isZero = false;
            while ($isZero == false) {
                $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
                $poichildren->makeHidden(['kb_static']);
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

                $mediaType3DModelMobileID = Poimediatype::where('name', '3D Model Mobile (GTFL)')->get();
                $poiMedia3DModelMobile = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType3DModelMobileID[0]->id)->get();
                $CounterPoiMedia3DModelMobile = $CounterPoiMedia3DModelMobile + count($poiMedia3DModelMobile);

                $poiMediaStoryTelling = Storytelling::where('poi_id', $tempTable[0])->get();
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
            if ($CounterPoiMediaZip != 0)
                $array3[sizeof($array3)] = array('id' => $mediaTypeZipID[0]->id, 'name' => $mediaTypeZipID[0]->name, 'amount' => $CounterPoiMediaZip);
            if ($CounterPoiMedia360Tour != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360TourID[0]->id, 'name' => $mediaType360TourID[0]->name, 'amount' => $CounterPoiMedia360Tour);
            if ($CounterPoiMedia360TourVR != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360TourVRID[0]->id, 'name' => $mediaType360TourVRID[0]->name, 'amount' => $CounterPoiMedia360TourVR);
            if ($CounterPoiMedia360Single != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360SingleID[0]->id, 'name' => $mediaType360SingleID[0]->name, 'amount' => $CounterPoiMedia360Single);
            if ($CounterPoiMedia3DModelMobile != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType3DModelMobileID[0]->id, 'name' => $mediaType3DModelMobileID[0]->name, 'amount' => $CounterPoiMedia3DModelMobile);
            if ($CounterPoiMediaStoryTelling != 0)
                $array3[sizeof($array3)] = array('id' => '999', 'name' => 'Story Telling', 'amount' => $CounterPoiMediaStoryTelling);

            $array3 = array('media' => $array3);
            $merged_array = $array1 + $array2 + $array3;
            array_push($myArray,  $merged_array);
        }

        return response(['status' => 'success', 'data' => $myArray]);
    }


    public function api_get_specific_sites(Request $request)
    {        
        $comma_separeted_sites=explode(',', $request->input('ids'));
        $sitesWithMainPOI=[];
        foreach ($comma_separeted_sites as $separeted_site) 
        {
            $newAddition = Site::where('main_poi', '!=', 0)->where('id', $separeted_site)->get();
            if (count($newAddition)>0)
            {
                array_push($sitesWithMainPOI,  $newAddition[0]);    
            }            
        }

        //$sitesWithMainPOI = Site::where('main_poi', '!=', 0)->where('id', $request->input('id'))->get();        
        $myArray = []; 

        foreach ($sitesWithMainPOI as $siteWithMainPOI) {
            $MainPoi = Poi::find($siteWithMainPOI->main_poi);
            $MainPoi->makeHidden(['kb_static']);

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
            $CounterPoiMedia3DModelMobile = 0;

            $poi = Poi::find($siteWithMainPOI->main_poi);
            $poi->makeHidden(['kb_static']);
            $tempTable = [$siteWithMainPOI->main_poi];
            $isZero = false;
            while ($isZero == false) {
                $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
                $poichildren->makeHidden(['kb_static']);
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

                $mediaType3DModelMobileID = Poimediatype::where('name', '3D Model Mobile (GTFL)')->get();
                $poiMedia3DModelMobile = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType3DModelMobileID[0]->id)->get();
                $CounterPoiMedia3DModelMobile = $CounterPoiMedia3DModelMobile + count($poiMedia3DModelMobile);

                $poiMediaStoryTelling = Storytelling::where('poi_id', $tempTable[0])->get();
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
            if ($CounterPoiMediaZip != 0)
                $array3[sizeof($array3)] = array('id' => $mediaTypeZipID[0]->id, 'name' => $mediaTypeZipID[0]->name, 'amount' => $CounterPoiMediaZip);
            if ($CounterPoiMedia360Tour != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360TourID[0]->id, 'name' => $mediaType360TourID[0]->name, 'amount' => $CounterPoiMedia360Tour);
            if ($CounterPoiMedia360TourVR != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360TourVRID[0]->id, 'name' => $mediaType360TourVRID[0]->name, 'amount' => $CounterPoiMedia360TourVR);
            if ($CounterPoiMedia360Single != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360SingleID[0]->id, 'name' => $mediaType360SingleID[0]->name, 'amount' => $CounterPoiMedia360Single);
            if ($CounterPoiMedia3DModelMobile != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType3DModelMobileID[0]->id, 'name' => $mediaType3DModelMobileID[0]->name, 'amount' => $CounterPoiMedia3DModelMobile);
            if ($CounterPoiMediaStoryTelling != 0)
                $array3[sizeof($array3)] = array('id' => '999', 'name' => 'Story Telling', 'amount' => $CounterPoiMediaStoryTelling);

            $array3 = array('media' => $array3);
            $merged_array = $array1 + $array2 + $array3;
            array_push($myArray,  $merged_array);
        }

        return response(['status' => 'success', 'data' => $myArray]);
    }




    /* //api_MapJourneyPois version 1
    public function api_MapJourneyPois(Request $request)
    {
      $sitesWithMainPOI= Site::where('main_poi', '!=', 0)->get();
      $myArray = [];

      foreach($sitesWithMainPOI as $siteWithMainPOI)
      {
          $MainPoi = Poi::find($siteWithMainPOI->main_poi);

          $array1 = array('site' => $siteWithMainPOI);
          $array2 = array('mainPOI' => $MainPoi);
          $array3 =[];
          $CounterPoiMediaImage=0;
          $CounterPoiMediaVideo=0;

          $poi=Poi::find($siteWithMainPOI->main_poi);
          $tempTable = [$siteWithMainPOI->main_poi];
          $isZero = false;
          while($isZero == false)
          {
            $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
            //find media...
            $mediaTypeImageID= Poimediatype::where('name', 'Image File')->get();
            $poiMediaImage = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaTypeImageID[0]->id )->get();
            $CounterPoiMediaImage = $CounterPoiMediaImage + count($poiMediaImage);

            $mediaTypeVideoID= Poimediatype::where('name', 'Video File')->get();
            $poiMediaVideo = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaTypeVideoID[0]->id)->get();
            $CounterPoiMediaVideo = $CounterPoiMediaVideo + count($poiMediaVideo);

            \array_splice($tempTable, 0, 1); //removes 1st record from array
            $loopIter = count($poichildren);
            for ($x = 0; $x < $loopIter; $x++)
              {
                  array_push($tempTable,$poichildren[$x]->id);
              }
            if (($loopIter==0) && (count($tempTable) == 0))
              { $isZero=true; }
          }

           if ($CounterPoiMediaImage!=0)
              $array3[sizeof($array3)] = array('id'=> $mediaTypeImageID[0]->id,'name' => $mediaTypeImageID[0]->name, 'amount' => $CounterPoiMediaImage);
           if ($CounterPoiMediaVideo!=0)
              $array3[sizeof($array3)] = array('id'=> $mediaTypeVideoID[0]->id,'name' => $mediaTypeVideoID[0]->name, 'amount' => $CounterPoiMediaVideo);

          $array3 = array('media' => $array3);
          $merged_array = $array1 + $array2 + $array3;
          array_push($myArray,  $merged_array );
      }

      return response(['status' => 'success', 'data' => $myArray]);
    }*/

    public function api_MapJourneyPois(Request $request)
    {
        $userID = auth('api')->user()->id;
        $userjourney = Userjourney::where('user_id', $userID)->where('status', 'active')->get();

        $sitesWithMainPOI = [];
        if (count($userjourney) > 0) {
            $userjourney_splits = explode(',', $userjourney[0]->journey_ids);
            
            foreach ($userjourney_splits as $userjourney_split) {
                $site = Site::where('main_poi', '!=', 0)->where("id", $userjourney_split)->get();
                if (count($site) > 0) {
                    array_push($sitesWithMainPOI, $site[0]);
                }
            }
        }

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

        return response(['status' => 'success', 'data' => $myArray]);
        
    }

    public function api_MapJourneyPois_by_userjourneyid(Request $request)
    {
        $userID = auth('api')->user()->id;
        $userjourney = Userjourney::where('user_id', $userID)->where('id', '=', $request->input('userjourney_id'))->get();

        $sitesWithMainPOI = [];
        if (count($userjourney) > 0) {
            $userjourney_splits = explode(',', $userjourney[0]->journey_ids);
            
            foreach ($userjourney_splits as $userjourney_split) {
                $site = Site::where('main_poi', '!=', 0)->where("id", $userjourney_split)->get();
                if (count($site) > 0) {
                    array_push($sitesWithMainPOI, $site[0]);
                }
            }
        }
        else
        {
            return response(['status' => 'success', 'data' => null]); 
        }

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

        return response(['status' => 'success', 'data' => $myArray]);

        return response(['status' => 'success', 'data' => '']);
    }

    public function api_POIContentViewByMediaTypeID(Request $request)
    {
        $sitesWithMainPOI = Site::where('id', '=', $request->input('site_id'))->get();
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
            $CounterPoiMediamobileGTFL = 0;
            $mediaTypeResultArray = [];

            $poi = Poi::find($siteWithMainPOI->main_poi);
            $tempTable = [$siteWithMainPOI->main_poi];
            $isZero = false;
            while ($isZero == false) {
                $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
                //find media...
                $mediaTypeImageID = Poimediatype::where('name', 'Image File')->get();
                $poiMediaImage = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaTypeImageID[0]->id)->get();
                $CounterPoiMediaImage = $CounterPoiMediaImage + count($poiMediaImage);
                if (($request->input('mediatype_id') == $mediaTypeImageID[0]->id) && ($CounterPoiMediaImage != 0)) {
                    $currentPOI = Poi::find($tempTable[0]);
                    foreach ($poiMediaImage as $poiMediaImage) {
                        $mediaTypeResultArray[sizeof($mediaTypeResultArray)] = array(
                            'id' => $poiMediaImage->id,
                            'imageThumbnail' => $poiMediaImage->path_thumbnail,
                            'image' => $poiMediaImage->uri,
                            //'name' => $siteWithMainPOI->name,
                            'name' => $currentPOI->name,
                            'shortDescription' => $poiMediaImage->name,
                            'description' => $poiMediaImage->name,
                            'audio' => null,
                            'model' => null,
                            'video' => null,
                            '360Tour' => null,
                            '360TourVR' => null,
                            '360Single' => null,
                            'mobileGTFL' => null,
                            'mobileIOS' => null
                        );
                    }
                }

                $mediaTypeVideoID = Poimediatype::where('name', 'Video File')->get();
                $poiMediaVideo = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaTypeVideoID[0]->id)->get();
                $CounterPoiMediaVideo = $CounterPoiMediaVideo + count($poiMediaVideo);
                if (($request->input('mediatype_id') == $mediaTypeVideoID[0]->id) && ($CounterPoiMediaVideo != 0)) {
                    $currentPOI = Poi::find($tempTable[0]);
                    foreach ($poiMediaVideo as $poiMediaVideo) {
                        $mediaTypeResultArray[sizeof($mediaTypeResultArray)] = array(
                            'id' => $poiMediaVideo->id,
                            'imageThumbnail' => $poiMediaVideo->path_thumbnail,
                            'image' => null,
                            //'name' => $siteWithMainPOI->name,
                            'name' => $currentPOI->name,
                            'shortDescription' => $poiMediaVideo->name,
                            'description' => $poiMediaVideo->name,
                            'audio' => null,
                            'model' => null,
                            'video' => $poiMediaVideo->uri,
                            '360Tour' => null,
                            '360TourVR' => null,
                            '360Single' => null,
                            'mobileGTFL' => null,
                            'mobileIOS' => null
                        );
                    }
                }

                $mediaTypeZipID = Poimediatype::where('name', 'LIKE', '%Zip%')->get();
                $poiMediaZip = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaTypeZipID[0]->id)->get();
                $CounterPoiMediaZip = $CounterPoiMediaZip + count($poiMediaZip);
                if (($request->input('mediatype_id') == $mediaTypeZipID[0]->id) && ($CounterPoiMediaZip != 0)) {
                    $currentPOI = Poi::find($tempTable[0]);
                    foreach ($poiMediaZip as $poiMediaZip) {
                        $mediaTypeResultArray[sizeof($mediaTypeResultArray)] = array(
                            'id' => $poiMediaZip->id,
                            'imageThumbnail' => $poiMediaZip->path_thumbnail,
                            'image' => null,
                            //'name' => $siteWithMainPOI->name,
                            'name' => $currentPOI->name,
                            'shortDescription' => $poiMediaZip->name,
                            'description' => $poiMediaZip->name,
                            'audio' => null,
                            'model' => $poiMediaZip->uri,
                            'video' => null,
                            '360Tour' => null,
                            '360TourVR' => null,
                            '360Single' => null,
                            'mobileGTFL' => null,
                            'mobileIOS' => null
                        );
                    }
                }

                $mediaType360TourID = Poimediatype::where('name', '360 Tour')->get();
                $poiMedia360Tour = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType360TourID[0]->id)->get();
                $CounterPoiMedia360Tour = $CounterPoiMedia360Tour + count($poiMedia360Tour);
                if (($request->input('mediatype_id') == $mediaType360TourID[0]->id) && ($CounterPoiMedia360Tour != 0)) {
                    $currentPOI = Poi::find($tempTable[0]);
                    foreach ($poiMedia360Tour as $poiMedia360Tour) {
                        $mediaTypeResultArray[sizeof($mediaTypeResultArray)] = array(
                            'id' => $poiMedia360Tour->id,
                            'imageThumbnail' => $poiMedia360Tour->path_thumbnail,
                            'image' => null,
                            //'name' => $siteWithMainPOI->name,
                            'name' => $currentPOI->name,
                            'shortDescription' => $poiMedia360Tour->name,
                            'description' => $poiMedia360Tour->name,
                            'audio' => null,
                            'model' => null,
                            'video' => null,
                            '360Tour' => $poiMedia360Tour->uri,
                            '360TourVR' => null,
                            '360Single' => null,
                            'mobileGTFL' => null,
                            'mobileIOS' => null
                        );
                    }
                }

                $mediaType360TourVRID = Poimediatype::where('name', '360 Tour VR')->get();
                $poiMedia360TourVR = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType360TourVRID[0]->id)->get();
                $CounterPoiMedia360TourVR = $CounterPoiMedia360TourVR + count($poiMedia360TourVR);
                if (($request->input('mediatype_id') == $mediaType360TourVRID[0]->id) && ($CounterPoiMedia360TourVR != 0)) {
                    $currentPOI = Poi::find($tempTable[0]);
                    foreach ($poiMedia360TourVR as $poiMedia360TourVR) {
                        $mediaTypeResultArray[sizeof($mediaTypeResultArray)] = array(
                            'id' => $poiMedia360TourVR->id,
                            'imageThumbnail' => $poiMedia360TourVR->path_thumbnail,
                            'image' => null,
                            //'name' => $siteWithMainPOI->name,
                            'name' => $currentPOI->name,
                            'shortDescription' => $poiMedia360TourVR->name,
                            'description' => $poiMedia360TourVR->name,
                            'audio' => null,
                            'model' => null,
                            'video' => null,
                            '360Tour' => null,
                            '360TourVR' => $poiMedia360TourVR->uri,
                            '360Single' => null,
                            'mobileGTFL' => null,
                            'mobileIOS' => null
                        );
                    }
                }

                $mediaType360SingleID = Poimediatype::where('name', '360 single (Image)')->get();
                $poiMedia360Single = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType360SingleID[0]->id)->get();
                $CounterPoiMedia360Single = $CounterPoiMedia360Single + count($poiMedia360Single);
                if (($request->input('mediatype_id') == $mediaType360SingleID[0]->id) && ($CounterPoiMedia360Single != 0)) {
                    $currentPOI = Poi::find($tempTable[0]);
                    foreach ($poiMedia360Single as $poiMedia360Single) {
                        $mediaTypeResultArray[sizeof($mediaTypeResultArray)] = array(
                            'id' => $poiMedia360Single->id,
                            'imageThumbnail' => $poiMedia360Single->path_thumbnail,
                            'image' => null,
                            //'name' => $siteWithMainPOI->name,
                            'name' => $currentPOI->name,
                            'shortDescription' => $poiMedia360Single->name,
                            'description' => $poiMedia360Single->name,
                            'audio' => null,
                            'model' => null,
                            'video' => null,
                            '360Tour' => null,
                            '360TourVR' => null,
                            '360Single' => $poiMedia360Single->uri,
                            'mobileGTFL' => null,
                            'mobileIOS' => null
                        );
                    }
                }

                $mediaTypemobileGTFLID = Poimediatype::where('name', '3D Model Mobile (GTFL)')->get();
                $poiMediamobileGTFL = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaTypemobileGTFLID[0]->id)->get();
                $CounterPoiMediamobileGTFL = $CounterPoiMediamobileGTFL + count($poiMediamobileGTFL);
                if (($request->input('mediatype_id') == $mediaTypemobileGTFLID[0]->id) && ($CounterPoiMediamobileGTFL != 0)) {
                    $currentPOI = Poi::find($tempTable[0]);
                    foreach ($poiMediamobileGTFL as $poiMediamobileGTFL) {
                        $mediaTypeResultArray[sizeof($mediaTypeResultArray)] = array(
                            'id' => $poiMediamobileGTFL->id,
                            'imageThumbnail' => $poiMediamobileGTFL->path_thumbnail,
                            'image' => null,
                            //'name' => $siteWithMainPOI->name,
                            'name' => $currentPOI->name,
                            'shortDescription' => $poiMediamobileGTFL->name,
                            'description' => $poiMediamobileGTFL->name,
                            'audio' => null,
                            'model' => null,
                            'video' => null,
                            '360Tour' => null,
                            '360TourVR' => null,
                            '360Single' => null,
                            'mobileGTFL' => $poiMediamobileGTFL->uri,
                            'mobileIOS' => $poiMediamobileGTFL->uri2
                        );
                    }
                }

                $poiMediaStoryTelling = Storytelling::where('poi_id', $tempTable[0])->Where('language', $request->input('language'))->get();
                $CounterPoiMediaStoryTelling = $CounterPoiMediaStoryTelling + count($poiMediaStoryTelling);
                if (($request->input('mediatype_id') == 999) && ($CounterPoiMediaStoryTelling != 0)) {
                    $currentPOI = Poi::find($tempTable[0]);
                    foreach ($poiMediaStoryTelling as $poiMediaStoryTelling) {
                        $mediaTypeResultArray[sizeof($mediaTypeResultArray)] = array(
                            'id' => $poiMediaStoryTelling->id,
                            'imageThumbnail' => $poiMediaStoryTelling->path_thumbnail,
                            'image' => null,
                            //'name' => $siteWithMainPOI->name,
                            'name' => $currentPOI->name,
                            'shortDescription' => $poiMediaStoryTelling->description,
                            'description' => $poiMediaStoryTelling->description,
                            'audio' => $poiMediaStoryTelling->path,
                            'model' => null,
                            'video' => null,
                            '360Tour' => null,
                            '360TourVR' => null,
                            '360Single' => null,
                            'mobileGTFL' => null,
                            'mobileIOS' => null
                        );
                    }
                }


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
            if ($CounterPoiMediaZip != 0)
                $array3[sizeof($array3)] = array('id' => $mediaTypeZipID[0]->id, 'name' => $mediaTypeZipID[0]->name, 'amount' => $CounterPoiMediaZip);
            if ($CounterPoiMedia360Tour != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360TourID[0]->id, 'name' => $mediaType360TourID[0]->name, 'amount' => $CounterPoiMedia360Tour);
            if ($CounterPoiMedia360TourVR != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360TourVRID[0]->id, 'name' => $mediaType360TourVRID[0]->name, 'amount' => $CounterPoiMedia360TourVR);
            if ($CounterPoiMedia360Single != 0)
                $array3[sizeof($array3)] = array('id' => $mediaType360SingleID[0]->id, 'name' => $mediaType360SingleID[0]->name, 'amount' => $CounterPoiMedia360Single);
            if ($CounterPoiMediamobileGTFL != 0)
                $array3[sizeof($array3)] = array('id' => $mediaTypemobileGTFLID[0]->id, 'name' => $mediaTypemobileGTFLID[0]->name, 'amount' => $CounterPoiMediamobileGTFL);
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

        return response(['status' => 'success', 'data' => $mediaTypeResultArray]);
    }





    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $sites = Site::where('name', 'LIKE', "%$keyword%")->paginate(10);
        return view('sites.index')->with('sites', $sites);
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
            'path' => 'image|nullable|max:100000',
            'district' => 'required',
            'category' => 'required',
        ]);


        //handle file upload.
        $fileNameToStore = 'noimage.jpg';

        //create Site
        $site = new Site;
        $site->name = $request->input('name');
        $site->district = $request->input('district');
        $site->town = $request->input('town');
        $site->category = $request->input('category');
        $site->fee = $request->input('fee');
        $site->contact_tel = $request->input('contact_tel');
        $site->url = $request->input('url');
        $site->main_poi = 0;
        $site->path = $fileNameToStore;
        $site->name_gr = $request->input('name_gr');
        $site->name_ru = $request->input('name_ru');
        $site->name_it = $request->input('name_it');
        $site->name_fr = $request->input('name_fr');
        $site->name_ge = $request->input('name_ge');
        $site->town_gr = $request->input('town_gr');
        $site->town_ru = $request->input('town_ru');
        $site->town_it = $request->input('town_it');
        $site->town_fr = $request->input('town_fr');
        $site->town_ge = $request->input('town_ge');
        $site->save();

        //handle file upload
        if ($request->hasFile('path')) {
            //return $site->id;
            //get filename with the extension
            $filenameWithExt = $request->file('path')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('path')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('path')->storeAs("public/media/{$site->id}", $fileNameToStore);
            //save only the image on the right folder path
            $site->path = $site->id . "/" . $fileNameToStore;
            $site->save();
        }


        return redirect('/sites')->with('success', 'Site created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $site = Site::find($id);
        $poi = Poi::find($site->main_poi);
        $sitecategory = Sitecategory::find($site->category);
        $cydistrict = Cy_district::find($site->district);
        //$siteaccessibility=Siteaccessibility::where('site_id', $site->id)->get();
        $siteaccessibility = \DB::table('siteaccessibilities')
            ->join('accessibilities', 'accessibilities.id', '=', 'siteaccessibilities.accessibility_id')
            ->select('accessibilities.name as name', 'siteaccessibilities.id')
            ->where('siteaccessibilities.site_id', $site->id)
            ->get();
        $siteworkinghour = Siteworkinghour::where('site_id', $site->id)->get();

        return view('sites.show')->with('site', $site)->with('poi', $poi)->with('sitecategory', $sitecategory)->with('cydistrict', $cydistrict)->with('siteaccessibility', $siteaccessibility)->with('siteworkinghour', $siteworkinghour);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $site = Site::find($id);
        $sitecategory = Sitecategory::get();
        $cydistrict = Cy_district::get();
        $sitecategory_current = Sitecategory::find($site->category);
        $cydistrict_current = Cy_district::find($site->district);

        return view('sites.edit')->with('site', $site)->with('sitecategory_current', $sitecategory_current)->with('cydistrict_current', $cydistrict_current)->with('sitecategory', $sitecategory)->with('cydistrict', $cydistrict);
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
            'path' => 'image|nullable|max:100000',
            'district' => 'required',
            'category' => 'required',
        ]);

        //update SIte
        $site = Site::find($id);

        //handle file upload
        if ($request->hasFile('path')) {
            //get filename with the extension
            $filenameWithExt = $request->file('path')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('path')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('path')->storeAs("public/media/{$site->id}", $fileNameToStore);

            if ($site->path != "noimage.jpg") {
                Storage::delete('public/media/' . $site->path);
            }
        }

        $site->name = $request->input('name');
        $site->district = $request->input('district');
        $site->town = $request->input('town');
        $site->category = $request->input('category');
        $site->fee = $request->input('fee');
        $site->contact_tel = $request->input('contact_tel');
        $site->url = $request->input('url');
        $site->name_gr = $request->input('name_gr');
        $site->name_ru = $request->input('name_ru');
        $site->name_it = $request->input('name_it');
        $site->name_fr = $request->input('name_fr');
        $site->name_ge = $request->input('name_ge');
        $site->town_gr = $request->input('town_gr');
        $site->town_ru = $request->input('town_ru');
        $site->town_it = $request->input('town_it');
        $site->town_fr = $request->input('town_fr');
        $site->town_ge = $request->input('town_ge');
        if ($request->hasFile('path')) {
            $site->path = $site->id . "/" . $fileNameToStore;
        }
        $site->save();

        return redirect('/sites')->with('success', 'Site updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $site = Site::find($id);

        if ($site->main_poi != 0) {
            $poi = Poi::find($site->main_poi);
            $tempTable = [$poi->id];
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
        }

        //if ($site->path != 'noimage.jpg')
        //{
        //Storage::delete('public/media/'.$site->path); //delete the image as well
        Storage::deleteDirectory("public/media/{$site->id}");
        //}

        Siteaccessibility::where('site_id', $site->id)->delete();
        Siteworkinghour::where('site_id', $site->id)->delete();
        Thematicroutesite::where('site_id', $site->id)->delete();

        $site->delete();
        return redirect('/sites')->with('success', 'Site Removed');
    }
}
