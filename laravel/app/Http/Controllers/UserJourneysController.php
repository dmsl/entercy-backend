<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Userjourney;
use App\Site;
use App\Poi;
use App\Poimedia;
use App\Poimediatype;
use App\Traveltype_user;
use App\Storytelling;
use App\Useranswer;
use App\Question;
use App\User;
use App\Service;

use Symfony\Component\Process\Process;


class UserJourneysController extends Controller
{
    //checks whether the departing date is past, and updates the status to rating
    public function check_departing_date()
    {
        $current_date = date('d-m-Y'); 
        $userjourneys = Userjourney::where('status', 'active')->get();

        foreach ($userjourneys as $userjourney)
        {
            // converting them both to timestamps first, and then compare two converted value
            $curdate=strtotime($current_date);
            $departingdate=strtotime($userjourney->departing_date);

            if ($departingdate < $curdate)
            {
                  $uj = Userjourney::find($userjourney->id);
                  $uj->status='rating';
                  $uj->save();
            }
        }
    }



    public function are_optional_questions_completed()
    {
        $response = false;
        $userID = auth('api')->user()->id;
        $user = User::find($userID);
        $useranswers = Useranswer::where('user_id', $user->id)->get();

        if (count($useranswers) > 0)
        {
            foreach ($useranswers as $useranswer) 
            {
                $question = Question::where('id', $useranswer->question_id)->where('compulsory', 'Optional')->get();

                 if (count($question) > 0)
                 {
                     $response = true;
                 }
            }
        }
        else
            $response = false;

        return response(['status' => 'success', 'data' => $response]);
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

    public function calendar_view(Request $request)
    {
        $this->validate($request, [
        'userjourney_id' => 'required'
        ]);

        $userjourney_id = $request->input('userjourney_id');
        $userID = auth('api')->user()->id;
        $userjourney = Userjourney::where('user_id', $userID)->where('id', $userjourney_id)->get();

        $allData = [];
        $lefkosia = [];
        $larnaka = [];
        $lemesos = [];
        $pafos = [];        
        $ammochostos = [];

        if (count($userjourney) > 0) {
            $userjourney_splits = explode(',', $userjourney[0]->journey_ids);
            
            foreach ($userjourney_splits as $userjourney_split) {
                $site = Site::where('main_poi', '!=', 0)->where("id", $userjourney_split)->get();
                
                if (count($site) > 0) {
                    if ($site[0]->district == 1 )
                    {
                        array_push($lefkosia, $site[0]);    
                    }
                    else if ($site[0]->district == 2 )
                    {
                        array_push($larnaka, $site[0]); 
                    }
                    else if ($site[0]->district == 3 )
                    {
                        array_push($lemesos, $site[0]); 
                    }
                    else if ($site[0]->district == 4 )
                    {
                        array_push($pafos, $site[0]); 
                    }
                    else if ($site[0]->district == 5 )
                    {
                        array_push($ammochostos, $site[0]); 
                    }                    
                }
            }
        }

        $allData[sizeof($allData)] = array('lefkosia' => $lefkosia ,'larnaka' => $larnaka ,'lemesos' => $lemesos ,'pafos' => $pafos ,'ammochostos' => $ammochostos);

        return response(['status' => 'success', 'data' => $allData[0]]);

    }

    public function get_userjourney_sites_with_imgs(Request $request)
    {
        $this->validate($request, [
        'userjourney_id' => 'required'
        ]);

        $userjourney_id = $request->input('userjourney_id');
        $userID = auth('api')->user()->id;
        $userjourney = Userjourney::where('user_id', $userID)->where('id', $userjourney_id)->get();

        $allData = [];
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

        $allData[sizeof($allData)] = array('userjourney' => $userjourney , 'sites' => $sitesWithMainPOI);

        return response(['status' => 'success', 'data' => $allData[0]]);

    }


    public function api_create_journey(Request $request)
    {
        $user_id = auth('api')->user()->id;
        $status = $request->input('status');
        #$journey_ids = $request->input('journey_ids');
        $districts = $request->input('districts');
        $arriving_date = $request->input('arriving_date');
        $departing_date = $request->input('departing_date');
        $travel_types = $request->input('travel_types');
        if (!empty($travel_types)) {
            Traveltype_user::where('user_id', $user_id)->delete(); //deletes old records
            //$travel_types = $request->input('travel_types');
            $travel_type = explode(',', $travel_types);
            foreach ($travel_type as $travel_typ) {
                $traveltype_user = new Traveltype_user;
                $traveltype_user->user_id = $user_id;
                $traveltype_user->traveltype_id = $travel_typ;
                $traveltype_user->save();
            }
        }
        $categories = $request->input('categories');
        $transportation = $request->input('transportation');
        $suggest_recommend = $request->input('suggest_recommend');
        #$rate = $request->input('rate');
        $token = $request->bearerToken();
        $userjourneys = Userjourney::where('user_id', $user_id)->get();
        foreach ($userjourneys as $user_journey) 
        {
            if ($user_journey->status == 'active') 
            {
                $user_journey->status = 'rating';
                $user_journey->save();
            }
        }
        $userjourney = new Userjourney;
        $userjourney->user_id = $user_id;
        $userjourney->status = $status;

        $userjourney->districts = $districts;
        $userjourney->arriving_date = $arriving_date;
        $userjourney->departing_date = $departing_date;
        $userjourney->travel_types = $travel_types;
        $userjourney->categories = $categories; //'-1';
        $userjourney->transportation = $transportation;
        $userjourney->suggest_recommend = $suggest_recommend;


        if ($suggest_recommend == 'recommend') {
            do {
                //$results = $this->api_journey_reccomendation($request); //old one
                $results = $this->api_journey_reccomendation_sites($request); //new one
                #$userjourney->journey_ids = $results;
                
                $splited = explode(",", $results);
                $sites_ids = [];
                foreach ($splited as $split) {
                    $poi = Poi::where('site_id', $split)->get();
                    if (isset($poi[0])) {
                        if ($poi[0]->coordinates != "0") {
                            $poimedias = Poimediatype::where('name', 'Image File')
                                ->orwhere('name', 'Video File')
                                ->orwhere('name', '360 single (Image)')
                                ->orwhere('name', '3D Model Mobile (GTFL)')->get();
                            foreach ($poimedias as $poimedia) {
                                $poimedia = Poimedia::where('poi_id', $poi[0]->id)->where('type', $poimedia->id)->get();
                                if (count($poimedia) > 0) {
                                    array_push($sites_ids, $split);
                                }
                            }
                        }
                    }
                }
                 
                $rand_position_SiteID=array_rand($splited, 2); //randomizes site id for image path thumbnail  
                $site = Site::where('id', $splited[$rand_position_SiteID[0]])->get();
                if (count($site) > 0) {
                    $userjourney->path_thumbnail = $site[0]->path;
                }

                $sites_ids = array_unique($sites_ids);
                $sites_ids = implode(",", $sites_ids);
            } while ($sites_ids == null);
            //return $results;
            $userjourney->journey_ids = $sites_ids;
        } else if ($suggest_recommend == 'suggest') {
            do {
                #$districts="1,2,5";
                $pieces = explode(",", $districts);
                $results = [];
                foreach ($pieces as $piece) {
                    $sites = Site::where('district', $piece)->get();
                    $arrLength = count($sites);
                    if ($arrLength > 0) {
                        for ($i = 1; $i <= 5; $i++) {
                            $rand = rand(0, $arrLength - 1);
                            $poi = Poi::where('site_id', $sites[$rand]->id)->get();
                            if (isset($poi[0])) {
                                if ($poi[0]->coordinates != "0") {
                                    $poimedias = Poimediatype::where('name', 'Image File')
                                        ->orwhere('name', 'Video File')
                                        ->orwhere('name', '360 single (Image)')
                                        ->orwhere('name', '3D Model Mobile (GTFL)')->get();
                                    foreach ($poimedias as $poimedia) {
                                        $poimedia = Poimedia::where('poi_id', $poi[0]->id)->where('type', $poimedia->id)->get();
                                        if (count($poimedia) > 0) {
                                            array_push($results, $poi[0]->site_id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $results = array_unique($results);
                $results = implode(",", $results);
                //return $results;
            } while ($results == null);
            $userjourney->journey_ids = $results;
            $splited = explode(",", $userjourney->journey_ids);
            $site = Site::where('id', $splited[0])->get();
            if (count($site) > 0) {
                $userjourney->path_thumbnail = $site[0]->path;
            }
        }
        $userjourney->save();
        return response(['status' => 'success', 'data' => 'true']);
    }


    public function api_check_userstatus(Request $request)
    {
        $user_id = auth('api')->user()->id;
        $userjourneys = Userjourney::where('user_id', $user_id)->get();
        foreach ($userjourneys as $user_journey) {
            if ($user_journey->status == 'active') {
                return response(['status' => 'success', 'data' => 'true']);
            }
        }
        return response(['status' => 'success', 'data' => 'false']);
    }

    public function api_getall_user_journey_by_user_id(Request $request)
    {
        $user_id = auth('api')->user()->id;
        $userjourney = Userjourney::where('user_id', $user_id)->get();
        return response(['status' => 'success', 'data' => $userjourney]);
    }

    public function api_change_userstatus(Request $request)
    {
        $user_journey_id = $request->input('user_journey_id');
        $user_journey = Userjourney::find($user_journey_id);
        if (isset($user_journey)) {
            if ($user_journey->status == 'active') {
                $user_journey->status = 'rating';
            } else if ($user_journey->status == 'rating') {
                $user_journey->status = 'generating';
                app(PoimediasController::class)->call_stitching_tool($request);
            }
            else if ($user_journey->status == 'generating') {
                $user_journey->status = 'completed';
            }

            $user_journey->save();
            return response(['status' => 'success', 'data' => 'true']);
        }
        return response(['status' => 'success', 'data' => 'unsuccessful change of status']);
    }

    public function api_refresh_journey(Request $request)
    {
        $user_journey_id = $request->input('user_journey_id');
        $user_journey = Userjourney::find($user_journey_id);
        if (isset($user_journey)) {

            if ($user_journey->suggest_recommend == 'recommend') {
                do {
                    $results = $this->api_journey_reccomendation_refresh($request, $user_journey);
                    $splited = explode(",", $results);
                    $sites_ids = [];
                    foreach ($splited as $split) {
                        $poi = Poi::where('site_id', $split)->get();
                        if (isset($poi[0])) {
                            if ($poi[0]->coordinates != "0") {
                                $poimedias = Poimediatype::where('name', 'Image File')
                                    ->orwhere('name', 'Video File')
                                    ->orwhere('name', '360 single (Image)')
                                    ->orwhere('name', '3D Model Mobile (GTFL)')->get();
                                foreach ($poimedias as $poimedia) {
                                    $poimedia = Poimedia::where('poi_id', $poi[0]->id)->where('type', $poimedia->id)->get();
                                    if (count($poimedia) > 0) {
                                        array_push($sites_ids, $split);
                                    }
                                }
                            }
                        }
                    }

                    $rand_position_SiteID=array_rand($splited, 2); //randomizes site id for image path thumbnail 
                    $site = Site::where('id', $splited[$rand_position_SiteID[0]])->get();
                    if (count($site) > 0) {
                        $user_journey->path_thumbnail = $site[0]->path;
                    }

                    $sites_ids = array_unique($sites_ids);
                    $sites_ids = implode(",", $sites_ids);
                } while ($sites_ids == null);
                //return $results;
                $user_journey->journey_ids = $sites_ids;
            } else if ($user_journey->suggest_recommend == 'suggest') {
                do {
                    #$districts="1,2,5";
                    $pieces = explode(",", $user_journey->districts);
                    $results = [];
                    foreach ($pieces as $piece) {
                        $sites = Site::where('district', $piece)->get();
                        $arrLength = count($sites);
                        if ($arrLength > 0) {
                            for ($i = 1; $i <= 5; $i++) {
                                $rand = rand(0, $arrLength - 1);
                                $poi = Poi::where('site_id', $sites[$rand]->id)->get();
                                if (isset($poi[0])) {
                                    if ($poi[0]->coordinates != "0") {
                                        $poimedias = Poimediatype::where('name', 'Image File')
                                            ->orwhere('name', 'Video File')
                                            ->orwhere('name', '360 single (Image)')
                                            ->orwhere('name', '3D Model Mobile (GTFL)')->get();
                                        foreach ($poimedias as $poimedia) {
                                            $poimedia = Poimedia::where('poi_id', $poi[0]->id)->where('type', $poimedia->id)->get();
                                            if (count($poimedia) > 0) {
                                                array_push($results, $poi[0]->site_id);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $results = array_unique($results);
                    $results = implode(",", $results);
                    //return $results;
                } while ($results == null);
                $user_journey->journey_ids = $results;
                $splited = explode(",", $user_journey->journey_ids);
                $site = Site::where('id', $splited[0])->get();
                if (count($site) > 0) {
                    $user_journey->path_thumbnail = $site[0]->path;
                }
            }

            $user_journey->save();
            return response(['status' => 'success', 'data' => 'true']);
        }
        return response(['status' => 'success', 'data' => '']);
    }


    public function api_journey_reccomendation(Request $request)
    {
        $userID = auth('api')->user()->id;
        $filename = 'storage/media/journey_recommendation.py'; //testing file
        $filename2 = 'storage/media/recommendation.py';
        $filename3 = 'storage/media/test.py';  //testing file
        if (file_exists($filename2)) {
            #echo "The file $filename2 exists";
        } else {
            #echo "The file $filename2 does not exist";
        }
        

        #sudo -H python3.8 -m pip install scipy
        #python3 -m pip install pandas


        $output = shell_exec($filename2 . ' ' . 'SITES' . ' ' . $request->bearerToken() . ' ' . $request->input('districts') . ' ' . $request->input('arriving_date') . ' ' . $request->input('departing_date') . ' ' . $request->input('travel_types') . ' ' . $request->input('categories') . ' ' . $request->input('transportation'));
        
        return trim($output, "\n");
    }


    public function api_journey_reccomendation_refresh(Request $request, $userjourney)
    {
        $userID = auth('api')->user()->id;
        $filename = 'storage/media/journey_recommendation.py'; //testing file
        $filename2 = 'storage/media/recommendation.py';  //old one
        //$filename2 = 'storage/media/main_sites.py';
        if (file_exists($filename2)) {
            #echo "The file $filename2 exists";
        } else {
            #echo "The file $filename2 does not exist";
        }

        $seperated_by_comma_travelTypes='';
        $current_user_travelTypes= Traveltype_user::where('user_id', $userID)->get();
        foreach($current_user_travelTypes as $current_user_travelType){
            $seperated_by_comma_travelTypes=$seperated_by_comma_travelTypes . $current_user_travelType->traveltype_id.',';
        }
        $seperated_by_comma_travelTypes = rtrim($seperated_by_comma_travelTypes, ',');

        //OLD APPROACH 7/7/2022
        //$output = shell_exec($filename2 . ' ' . 'SITES' . ' ' . $request->bearerToken() . ' ' . $userjourney->districts . ' ' . $userjourney->arriving_date . ' ' . $userjourney->departing_date . ' ' . $seperated_by_comma_travelTypes . ' ' . $userjourney->categories . ' ' . $userjourney->transportation);

        
        $d1=date_create($userjourney->arriving_date);
        $d2=date_create($userjourney->departing_date);
        $uj_arriving_date=date_format($d1, 'd/m/y');
        $uj_departing_date=date_format($d2, 'd/m/y');
       
        $cmd='python3.8 storage/media/main_sites.py' . ' ' . $request->bearerToken() . ' ' . $userjourney->districts . ' ' . $uj_arriving_date . ' ' . $uj_departing_date . ' ' . $seperated_by_comma_travelTypes . ' ' . $userjourney->categories . ' ' . $userjourney->transportation;

        $process = Process::fromShellCommandline($cmd);
        $output = '';
        $captureOutput = function ($type, $line) use (&$output) {
            $output .= $line;
        };
        $process->setTimeout(null)
            ->run($captureOutput); 

       
        return trim($output, "\n");
    }


    public function api_journey_reccomendation_sites(Request $request)
    {
        #sudo -H python3.8 -m pip install scipy
        #python3 -m pip install pandas

        $userID = auth('api')->user()->id;
        //$filename = 'storage/media/journey_recommendation.py'; //testing file
        $filename2 = 'storage/media/main_sites.py';
        //$filename3 = 'storage/media/test.py';  //testing file
      
        $d1=date_create($request->input('arriving_date'));
        $d2=date_create($request->input('departing_date'));
        $uj_arriving_date=date_format($d1, 'd/m/y');
        $uj_departing_date=date_format($d2, 'd/m/y');
       
       //OLD APPROACH 7/7/2022
        //$output = shell_exec($filename2 . ' ' . $request->bearerToken() . ' ' . $request->input('districts') . ' ' . $uj_arriving_date . ' ' . $uj_departing_date . ' ' . $request->input('travel_types') . ' ' . $request->input('categories') . ' ' . $request->input('transportation'));

        $cmd='python3.8 storage/media/main_sites.py' . ' ' . $request->bearerToken() . ' ' . $request->input('districts') . ' ' . $uj_arriving_date . ' ' . $uj_departing_date . ' ' . $request->input('travel_types') . ' ' . $request->input('categories') . ' ' . $request->input('transportation');

        $process = Process::fromShellCommandline($cmd);
        $output = '';
        $captureOutput = function ($type, $line) use (&$output) {
            $output .= $line;
        };
        $process->setTimeout(null)
            ->run($captureOutput); 

        return trim($output, "\n");

        # python .\main_sites.py eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiMDhjMjc5NWZiZDUxYjhkMDgxNWJmMjU5YTdhMGY2NmU5Njg0MjJhZmY5NmNhNDUxN2M3NTczYWU5YTAyZmMzMjcxNzI0OGE4YzRiODhiODIiLCJpYXQiOjE2NDY0NzE3NDMsIm5iZiI6MTY0NjQ3MTc0MywiZXhwIjoxNjYzNzUxNzQzLCJzdWIiOiIyNzQiLCJzY29wZXMiOlsiKiJdfQ.Ty-Jk8D7rgN1oQbQFiow46TqGqBuZGoa61jdQFdSCP15J5V0lRKFWASFtBZ3WaZyjrVrjbnB_WAzeEWpBS-q8SEnd6g5w1yfRu2ueNJ-wzpA4NTSxcrdgdV2MUy2H6vK6QocLLUgb2ZeUDq_Dt6kuyq0iOhVp6KDg5HdD3X63vX5i4mXJvmvz4vwNvSqb0IegicWEXNnDGRV4nkqrECpdm2LzXUXVFEv0qIehdRonc7_r7u8wRNvPf8kmr_3uvUIsySYd9bp6siqBj-GQut_2NxePwRr00hkWhwI0-zNY4kfZCIjOLHqhrDTayfMiSEqEswuOYeOjhm2Kvkz10oBNax8UIhjg5GNVsU827IyXqh_ZBORIcr_AWYoPnqMjycechDxobfkWxE_dxioXjQprkeYGJ2cXJSRQc0BYg-QVXpGkkPzigrX7SumpSOvdA2fTBPv1NTQaZFnT-vb3IxLdFGNskOYqEkqDGQR7FaGtaieW9RS2sdQ9u8pqzUKJeP8FaX7z6Esd8mgSzmRjHDJOV_ytnJf7k3XmO0t-egkfR0eHKOTES_WUoxrYbShyqWr4Q_5sFf_Hd_IzvmJ2A6DioeAUTQtYP1IZ2n59n6a7KJ01bVI4oHEKJFdHpDJ0WQ3IX3RpCmWrbkdIJuiKCEN2nG4lczkdTBw8hhKlGyGTNs 1,2,3 22 24 1,2 1,2,3 1

    }

    public function api_journey_reccomendation_services(Request $request)
    {
        #sudo -H python3.8 -m pip install scipy
        #python3 -m pip install pandas

        $userID = auth('api')->user()->id;
        $filename2 = 'storage/media/main_services.py';

        $userjourney = Userjourney::where('user_id', $userID)->where('status', 'active')->get();

        if ($userjourney==null || count($userjourney)==0)
        {
            return response(['status' => 'success', 'data' => null]);
        }

        $seperated_by_comma_travelTypes='';
        $current_user_travelTypes= Traveltype_user::where('user_id', $userID)->get();
        foreach($current_user_travelTypes as $current_user_travelType){
            $seperated_by_comma_travelTypes=$seperated_by_comma_travelTypes . $current_user_travelType->traveltype_id.',';
        }
        $seperated_by_comma_travelTypes = rtrim($seperated_by_comma_travelTypes, ',');

        $d1=date_create($userjourney[0]->arriving_date);
        $d2=date_create($userjourney[0]->departing_date);
        $uj_arriving_date=date_format($d1, 'd/m/y');
        $uj_departing_date=date_format($d2, 'd/m/y');
  
        //OLD APPROACH 31/6/2022
        //$output = shell_exec('storage/media/test1.py'); 
        //$output = shell_exec($filename2 . ' ' . $request->bearerToken() . ' ' . $userjourney[0]->districts . ' ' . $userjourney[0]->arriving_date . ' ' . $userjourney[0]->departing_date . ' ' . $seperated_by_comma_travelTypes . ' ' . $userjourney[0]->transportation . ' ' . $request->input('lat') . ',' . $request->input('lng') . ' ' . $request->input('distance') . ' ' . $request->input('time'));
        
        //OLD APPROACH 7/7/2022
        //$output = shell_exec($filename2 . ' ' . $request->bearerToken() . ' ' . $userjourney[0]->districts . ' ' . $uj_arriving_date . ' ' . $uj_departing_date . ' ' . $seperated_by_comma_travelTypes . ' ' . $userjourney[0]->transportation . ' ' . $request->input('lat') . ',' . $request->input('lng') . ' ' . $request->input('distance') . ' ' . $request->input('time'));
        
        //TEST APPROACH 7/7/2022
        //$cmd="python3.8 storage/media/main_services.py eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiNzViMzIwZmI1OGM0MGUzNWE0OWE5NWRiMmUwZjE1ZjBlYTlhZThhMGUzOGU3NDg5M2ZjMGVkZWZjYmIxZTA0ZWM2MmU2MDdjYzE4Nzg2MjAiLCJpYXQiOjE2NTcyMDAzMTcsIm5iZiI6MTY1NzIwMDMxNywiZXhwIjoxNjc0NDgwMzE3LCJzdWIiOiIxOTUiLCJzY29wZXMiOlsiKiJdfQ.CRGTSaPIxU9GPUG6PzdiShgUQb6t6Ibl2CJy0DN0lPMjrx2W_7RCjPaFrOqaZpIgZCof0S3QSNetOo-clieF-mPCpf68LwCLpKknjbzE4nAlmUtSK5nefsFkLnvHSVgrpCxE2CCjo-po48FaVa47tXh7NwbMw07px5fgSyc5WV0ggRIAROtiqR-Y_GrRH-i3BCLEytQP92yH-FNhH-jN0C4N0dLmF8bb5LSYtikGkQFHqkZTsPLQd-X0JisdQ4-JTP09i63e3aU3QUfZCR_hx7WnwDhh4-qafwd4XdB5tp6gT9y1lZQRFtIjsFzTan_I2Rv01q47VJ1Hqd222iBbj3KiP61rUFCp_-dYrUv1hiEz19qlT0VJzqyxUW9cwkJmVi9EJjqL-49GXL1d4RrFq1KbXTtqY4BifTRBuz2wFc6t81RvMbruZYDE5-7MvvDM082Fuora5d5sq2K_zoFOkB_0B83vxcPLwYnckpNKlV3l5NkCzJ8YZuFOc6J2Ln-cVbyNTWGSoIkcbvi3GIz6FawE5etWoD00l8GFh98zL-b2djw80L13jNVpJJ2uapz-BOfwDxoKSQyqD7-xSmaotYTQXspHaWyeQ3IkYyxTGXfvMepi9XlU8qFonvwKNgxTyzMNb5bVuJylvjoDL9YF2NWOS00gQiySbXP_iBFDb0U 3 07/07/22 30/07/22 6,1,2,3,4,5 3 35.012588,34.05876 1000000 15:31";

        $cmd='python3.8 storage/media/main_services.py' . ' ' . $request->bearerToken() . ' ' . $userjourney[0]->districts . ' ' . $uj_arriving_date . ' ' . $uj_departing_date . ' ' . $seperated_by_comma_travelTypes . ' ' . $userjourney[0]->transportation . ' ' . $request->input('lat') . ',' . $request->input('lng') . ' ' . $request->input('distance') . ' ' . $request->input('time');

        //$cmd='python3.8 -V';
        //return $cmd;
        $process = Process::fromShellCommandline($cmd);
        $output = '';
        $captureOutput = function ($type, $line) use (&$output) {
            $output .= $line;
        };
        $process->setTimeout(null)
            ->run($captureOutput);   

       

        //$output=null; // ONLY for testing if py script returns nothing
        $output= trim($output, "\n"); 
        
        //return $output;
        //this is for testing ease in testing env..remember to comment out all above when using this
         //$output='23|S,5|S,39|S,6|S,24|S,17|A,96|A,79|A,21|A,68|A,16|A,29|S,28|S,19|S,67|A,78|A,84|A,85|A,86|A,87|A,88|A,89|A,95|A,97|A';
        
        $final=[];

        if ($output==null || $output=='')
        {
            $services_with_cords= Service::where('coord_lat','!=', null)->where('coord_long', '!=', null)->where('registeredservice_id', '!=', 0)->get();
           
            $random_service = $services_with_cords[rand(0, count($services_with_cords) - 1)];
            
            $service = \DB::table('services')
                    ->join('registeredservices', 'services.registeredservice_id', '=', 'registeredservices.id')
                    ->select( 'registeredservices.name as service_name', 'services.*')            
                    ->where('services.id',$random_service->id)
                    ->get();
                    
                    if (count($service)>0)
                    {
                            $final[sizeof($final)]=$service[0];
                    }
        }
        else
        {

            $separated_answers = explode(',', $output);

            foreach ($separated_answers as  $answer) 
            {
                $combination = explode('|', $answer);
                //IGNORE A--ONLY RETURN S
                if ($combination[1]=='S')
                {
                    //print_r($combination);
                     $service = \DB::table('services')
                    ->join('registeredservices', 'services.registeredservice_id', '=', 'registeredservices.id')
                    ->select( 'registeredservices.name as service_name', 'services.*')            
                    ->where('services.id',$combination[0])
                    ->get();
                    
                    if (count($service)>0)
                    {
                            $final[sizeof($final)]=$service[0];
                    }
                }
            }

        }

        if (count($final)>0)
        {
                return response(['status' => 'success', 'data' => $final]);
        }
        else
        {
            return response(['status' => 'success', 'data' => null]);
        }
       

        //OLD TESTING APPROACH 31/6/2022
        //to pio kato ktipa den epistrefi kati logo tou matlplotlib
        //$output = shell_exec('python3.8 main_services.py eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiMWY3YTRkYWUzNzMwZDFmYWRkNzA2YzNkMzlkMTQ3YjBmYTg3ZTNkZGM3MjdhNjIyOWNlZGRhYzY4M2Y4NThiZmZlZWU4OGFlNjJmMGQyZDYiLCJpYXQiOjE2NDczNDA2NTAsIm5iZiI6MTY0NzM0MDY1MCwiZXhwIjoxNjY0NjIwNjUwLCJzdWIiOiI0MyIsInNjb3BlcyI6WyIqIl19.a8hAof_oWDHEGtrrHFFQgcmcIGjrWVYoPb7xiTol4sp3fAOWIdPbrEmvQzOvEbuYbDM6z-0uHBS75-C04BX1869_7x8vcZ4aiPl543N1b2VLExstomoIc5WKrgt4CDPYZXW7ARqvQQJM5vgAQv2nnPeF_jUgMKmoeXrsy9T0CC0GCU-sw12gf4zn2cz_uxiyfem-Jk7fI3k_vcw8uEOj1i3zQdn-UL5Hv7tmdsBn362X6OJ5i1Wc45qZmmFlLEjF8vkAfbaoYIhVohh177nZNi8LEtQtMcbifimEOpvYHc8l7g7zXbS-ZYRef6QtTa4jXVlWOTULPwD962pBnO-LJPerWYx6i_el7MzCn8AnetlkP4dp2at9mdbG0CVcrp5JqKRMX-ciR1K4RHFadgMDyZ6y1Joa2lzFdH68GKEAhPmxRk_MvPHpAyzW1BSpUFKTW3yNXfbmDedZ-hxF45O_J0Lc3AUxA4QSGAlf29dKqE9H56jQZ57we4K8KnCBqvp5u6FJ5rwS65phiPaVSI_fLNtZm58PEAN-cvAUrsKa9WQNlSfgP1XYZ3OQzms93SrZLAFCv-19ZE3xcQzq4bw4Zs64a54yiXHyPl_NdzOM8znYXI4kRLVBkNWBhrpTZ9zptEX8POknxInjKnGl4F3QlQ6tsgYMTuhv4g9X61DQ-Gk 1,3,5,4,2 15-03-2022 31-03-2022 6,5,4,1,2,3 3,2 33.766,35.4433 1000000 15:30');
        
        //OLD TESTING APPROACH 31/6/2022
        # python main_services.py eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiMWY3YTRkYWUzNzMwZDFmYWRkNzA2YzNkMzlkMTQ3YjBmYTg3ZTNkZGM3MjdhNjIyOWNlZGRhYzY4M2Y4NThiZmZlZWU4OGFlNjJmMGQyZDYiLCJpYXQiOjE2NDczNDA2NTAsIm5iZiI6MTY0NzM0MDY1MCwiZXhwIjoxNjY0NjIwNjUwLCJzdWIiOiI0MyIsInNjb3BlcyI6WyIqIl19.a8hAof_oWDHEGtrrHFFQgcmcIGjrWVYoPb7xiTol4sp3fAOWIdPbrEmvQzOvEbuYbDM6z-0uHBS75-C04BX1869_7x8vcZ4aiPl543N1b2VLExstomoIc5WKrgt4CDPYZXW7ARqvQQJM5vgAQv2nnPeF_jUgMKmoeXrsy9T0CC0GCU-sw12gf4zn2cz_uxiyfem-Jk7fI3k_vcw8uEOj1i3zQdn-UL5Hv7tmdsBn362X6OJ5i1Wc45qZmmFlLEjF8vkAfbaoYIhVohh177nZNi8LEtQtMcbifimEOpvYHc8l7g7zXbS-ZYRef6QtTa4jXVlWOTULPwD962pBnO-LJPerWYx6i_el7MzCn8AnetlkP4dp2at9mdbG0CVcrp5JqKRMX-ciR1K4RHFadgMDyZ6y1Joa2lzFdH68GKEAhPmxRk_MvPHpAyzW1BSpUFKTW3yNXfbmDedZ-hxF45O_J0Lc3AUxA4QSGAlf29dKqE9H56jQZ57we4K8KnCBqvp5u6FJ5rwS65phiPaVSI_fLNtZm58PEAN-cvAUrsKa9WQNlSfgP1XYZ3OQzms93SrZLAFCv-19ZE3xcQzq4bw4Zs64a54yiXHyPl_NdzOM8znYXI4kRLVBkNWBhrpTZ9zptEX8POknxInjKnGl4F3QlQ6tsgYMTuhv4g9X61DQ-Gk 1,3,5,4,2 15-03-2022 31-03-2022 6,5,4,1,2,3 3,2 33.766,35.4433 1000000 15:30

        ## DEFAULT => with current location and DEFAULT distance from current location and current time
        ## CUSTOM => with current location, distance from current location, time selection
        ## Default Distance = 10

        # 40|S,5|S,39|S,6|S,81|S,17|A,96|A,79|A,21|A,68|A,16|A,29|S,28|S,19|S,67|A,78|A,84|A,85|A,86|A,87|A,88|A,89|A,95|A,97|A
        ## id | S = search for services /  A = search in sites (activity)
        //IGNORE A--ONLY RETURN S
    }


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
