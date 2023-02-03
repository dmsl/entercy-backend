<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Thematicroute;
use App\Thematicroutesite;
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
use DB;

class ThematicroutesController extends Controller
{

    public function api_getthematicroutes()
    {
        $thematicroutes= Thematicroute::get();
        $myArray = [];
        foreach ($thematicroutes as $thematicroute) {
            //$thematicrouteSites = Thematicroutesite::where('thematicroute_id', $thematicroute->id)->get();
          $thematicrouteSites = DB::table('thematicroutesites')
                    ->join('sites', 'thematicroutesites.site_id', '=', 'sites.id')
                    ->select('thematicroutesites.*','sites.path')
                    ->where('thematicroutesites.thematicroute_id', $thematicroute->id)
                    ->get();

            $myArray[sizeof($myArray)] = array('ThematicRoute'=> $thematicroute,'Sites' => $thematicrouteSites);
        }
        
        return response(['status' => 'success', 'data' => $myArray]);  
    }

     public function api_getthematicroute(Request $request)
    {
        $thematicroute= Thematicroute::find($request->input('id'));
        if ($thematicroute!='')
        {
          $myArray = [];
          //$thematicrouteSites = Thematicroutesite::where('thematicroute_id', $thematicroute->id)->get();
          $thematicrouteSites = DB::table('thematicroutesites')
                    ->join('sites', 'thematicroutesites.site_id', '=', 'sites.id')
                    ->select('thematicroutesites.*','sites.path')
                    ->where('thematicroutesites.thematicroute_id', $thematicroute->id)
                    ->get();
                    
          $myArray[sizeof($myArray)] = array('ThematicRoute'=> $thematicroute,'Sites' => $thematicrouteSites);
          return response(['status' => 'success', 'data' => $myArray]);  
        }
        else
        {
         return response(['status' => 'success', 'data' => '']);   
        }
    }


    public function api_getthematicroute_mediacounter(Request $request)
    {
      $site_ids=$request->input('site_ids');
      $site_ids = explode(',', $site_ids);
      
      $myArray = [];
      $Total_CounterPoiMediaImage=0; 
      $Total_CounterPoiMediaVideo=0;  
      $Total_CounterPoiMediaZip=0;
      $Total_CounterPoiMediaStoryTelling=0;  
      $Total_CounterPoiMedia360Tour=0;
      $Total_CounterPoiMedia360TourVR=0;
      $Total_CounterPoiMedia360Single=0;

      foreach ($site_ids as $site_id) 
      {
        $sitesWithMainPOI= Site::where('id', '=', $site_id)->get();
        //$myArray = [];
        
        foreach($sitesWithMainPOI as $siteWithMainPOI)
        {        
            $MainPoi = Poi::find($siteWithMainPOI->main_poi);       
                     
            $array1 = array('site' => $siteWithMainPOI);
            $array2 = array('mainPOI' => $MainPoi);    
            $array3 =[];          
            $CounterPoiMediaImage=0; 
            $CounterPoiMediaVideo=0;  
            $CounterPoiMediaZip=0;
            $CounterPoiMediaStoryTelling=0; 
            $CounterPoiMedia360Tour=0;
            $CounterPoiMedia360TourVR=0;
            $CounterPoiMedia360Single=0;       

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

              $mediaTypeZipID= Poimediatype::where('name', 'LIKE','%Zip%')->get(); 
              $poiMediaZip = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaTypeZipID[0]->id)->get();
              $CounterPoiMediaZip = $CounterPoiMediaZip + count($poiMediaZip);

              $mediaType360TourID= Poimediatype::where('name','360 Tour')->get(); 
              $poiMedia360Tour = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType360TourID[0]->id)->get();
              $CounterPoiMedia360Tour = $CounterPoiMedia360Tour + count($poiMedia360Tour);

              $mediaType360TourVRID= Poimediatype::where('name','360 Tour VR')->get(); 
              $poiMedia360TourVR = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType360TourVRID[0]->id)->get();
              $CounterPoiMedia360TourVR = $CounterPoiMedia360TourVR + count($poiMedia360TourVR); 

              $mediaType360SingleID= Poimediatype::where('name','360 single (Image)')->get(); 
              $poiMedia360Single = Poimedia::where('poi_id', $tempTable[0])->Where('type', $mediaType360SingleID[0]->id)->get();
              $CounterPoiMedia360Single = $CounterPoiMedia360Single + count($poiMedia360Single);                   

              $poiMediaStoryTelling = Storytelling::where('poi_id', $tempTable[0])->Where('language',$request->input('language'))->get();
              //$poiMediaStoryTelling = Storytelling::where('poi_id', $tempTable[0])->get();
              $CounterPoiMediaStoryTelling = $CounterPoiMediaStoryTelling + count($poiMediaStoryTelling); 

                                  
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
             if ($CounterPoiMediaZip!=0)
                $array3[sizeof($array3)] = array('id'=> $mediaTypeZipID[0]->id,'name' => $mediaTypeZipID[0]->name, 'amount' => $CounterPoiMediaZip); 
             if ($CounterPoiMedia360Tour!=0)
                $array3[sizeof($array3)] = array('id'=> $mediaType360TourID[0]->id,'name' => $mediaType360TourID[0]->name, 'amount' => $CounterPoiMedia360Tour); 
             if ($CounterPoiMedia360TourVR!=0)
                $array3[sizeof($array3)] = array('id'=> $mediaType360TourVRID[0]->id,'name' => $mediaType360TourVRID[0]->name, 'amount' => $CounterPoiMedia360TourVR);
             if ($CounterPoiMedia360Single!=0)
                $array3[sizeof($array3)] = array('id'=> $mediaType360SingleID[0]->id,'name' => $mediaType360SingleID[0]->name, 'amount' => $CounterPoiMedia360Single); 
             if ($CounterPoiMediaStoryTelling!=0)
                $array3[sizeof($array3)] = array('id'=> '999','name' => 'Story Telling', 'amount' => $CounterPoiMediaStoryTelling); 

              $Total_CounterPoiMediaImage=$Total_CounterPoiMediaImage+$CounterPoiMediaImage; 
              $Total_CounterPoiMediaVideo=$Total_CounterPoiMediaVideo+$CounterPoiMediaVideo; 
              $Total_CounterPoiMediaZip=$Total_CounterPoiMediaZip+$CounterPoiMediaZip;
              $Total_CounterPoiMedia360Tour=$Total_CounterPoiMedia360Tour+$CounterPoiMedia360Tour;
              $Total_CounterPoiMedia360TourVR=$Total_CounterPoiMedia360TourVR+$CounterPoiMedia360TourVR;
              $Total_CounterPoiMedia360Single=$Total_CounterPoiMedia360Single+$CounterPoiMedia360Single;
              $Total_CounterPoiMediaStoryTelling=$Total_CounterPoiMediaStoryTelling+$CounterPoiMediaStoryTelling;

            
            //$array3 = array('media' => $array3);
            //$merged_array = $array1 + $array2 + $array3;
            //array_push($myArray,  $merged_array );

            $langName='';
            $langDescr='';
            if (($request->input('language') == 'English'))
            {
              if (!is_null($siteWithMainPOI->name))
                $langName=$siteWithMainPOI->name;
              else
                $langName=$siteWithMainPOI->name;

              if (!is_null($MainPoi->description))
                $langDescr=$MainPoi->description;
              else
                $langDescr=$MainPoi->description;
            }
            else if ($request->input('language') == 'Greek')
            {
              if (!is_null($siteWithMainPOI->name_gr))
                $langName=$siteWithMainPOI->name_gr;
              else
                $langName=$siteWithMainPOI->name;

              if (!is_null($MainPoi->description_gr))
                $langDescr=$MainPoi->description_gr;
              else
                $langDescr=$MainPoi->description;
            }
            else if ($request->input('language') == 'Russian')
            {
              if (!is_null($siteWithMainPOI->name_ru))
                $langName=$siteWithMainPOI->name_ru;
              else
                $langName=$siteWithMainPOI->name;

              if (!is_null($MainPoi->description_ru))
                $langDescr=$MainPoi->description_ru;
              else
                $langDescr=$MainPoi->description;
            }
            else if ($request->input('language') == 'Italian')
            {
              if (!is_null($siteWithMainPOI->name_it))
                $langName=$siteWithMainPOI->name_it;
              else
                $langName=$siteWithMainPOI->name;

              if (!is_null($MainPoi->description_it))
                $langDescr=$MainPoi->description_it;
              else
                $langDescr=$MainPoi->description;
            }
            else if ($request->input('language') == 'French')
            {
              if (!is_null($siteWithMainPOI->name_fr))
                $langName=$siteWithMainPOI->name_fr;
              else
                $langName=$siteWithMainPOI->name;

              if (!is_null($MainPoi->description_fr))
                $langDescr=$MainPoi->description_fr;
              else
                $langDescr=$MainPoi->description;
            }
            else if ($request->input('language') == 'German')
            {
              if (!is_null($siteWithMainPOI->name_ge))
                $langName=$siteWithMainPOI->name_ge;
              else
                $langName=$siteWithMainPOI->name;

              if (!is_null($MainPoi->description_ge))
                $langDescr=$MainPoi->description_ge;
              else
                $langDescr=$MainPoi->description;
            }
            else
            {
              if (!is_null($siteWithMainPOI->name))
                $langName=$siteWithMainPOI->name;
              else
                $langName=$siteWithMainPOI->name;

              if (!is_null($MainPoi->description))
                $langDescr=$MainPoi->description;
              else
                $langDescr=$MainPoi->description;
            }

            $coords = explode(',', $MainPoi->coordinates);  
            if ((!empty($coords[1])) || (!empty($coords[1])))  
              { 
                $myArray[sizeof($myArray)] = array('id'=> $siteWithMainPOI->id,'name' => $langName, 'description' => $langDescr, 'lat'=> $coords[0], 'lon'=> $coords[1], 'media' => $array3);   
              } 
            else{
                $myArray[sizeof($myArray)] = array('id'=> $siteWithMainPOI->id,'name' => $siteWithMainPOI->name, 'description' => $MainPoi->description, 'lat'=> 0, 'lon'=> 0, 'media' => $array3);    
            }        
            
        }
        
        //return response(['status' => 'success', 'data' => $myArray]);    
        //print_r($myArray);
      }    

      $myArrayTotals = [];
      $myArrayTotals[sizeof($myArrayTotals)] = array('Total_CounterPoiMediaImage'=> $Total_CounterPoiMediaImage,'Total_CounterPoiMediaVideo' => $Total_CounterPoiMediaVideo, 'Total_CounterPoiMediaZip' => $Total_CounterPoiMediaZip, 'Total_CounterPoiMediaStoryTelling' => $Total_CounterPoiMediaStoryTelling, 'Total_CounterPoiMedia360Tour' => $Total_CounterPoiMedia360Tour, 'Total_CounterPoiMedia360TourVR' => $Total_CounterPoiMedia360TourVR, 'Total_CounterPoiMedia360Single' => $Total_CounterPoiMedia360Single);    
      
      return response(['status' => 'success', 'data' => $myArrayTotals, 'detailed_data' => $myArray]);
    }


    //-------------------------------------------------------------------------------------


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $thematicroutes= Thematicroute::orderBy('created_at','desc')->paginate(10);
        return view('thematicroutes.index')->with('thematicroutes',$thematicroutes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('thematicroutes.create');
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
        //'description' => 'required',        
      ]);

      //create POI
      $thematicroute = new Thematicroute;
      $thematicroute->name=$request->input('name');
      $thematicroute->name_gr=$request->input('name_gr');
      $thematicroute->name_ru=$request->input('name_ru');
      $thematicroute->name_it=$request->input('name_it');
      $thematicroute->name_fr=$request->input('name_fr');
      $thematicroute->name_ge=$request->input('name_ge');
      $thematicroute->description=$request->input('description');
      $thematicroute->description_gr=$request->input('description_gr');
      $thematicroute->description_ru=$request->input('description_ru');
      $thematicroute->description_it=$request->input('description_it');
      $thematicroute->description_fr=$request->input('description_fr');
      $thematicroute->description_ge=$request->input('description_ge');

      //handle image file upload
      if($request->hasFile('path_img'))
      {
        //get filename with the extension
        $filenameWithExt=$request->file('path_img')->getClientOriginalName();
        //get just $filename
        $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just the extension
        $extension= $request->file('path_img')->getClientOriginalExtension();
        //filename to store
        $fileNameToStore=$filename.'_'.time().'.'.$extension;
        //upload image
        $path= $request->file('path_img')->storeAs("public/media/Thematicroutes/", $fileNameToStore);

        if ($thematicroute->path_img!="")
        {
           Storage::delete('public/media/'.$thematicroute->path_img);
        }

        $thematicroute->path_img= "Thematicroutes/" . $fileNameToStore;
      }
     
      //---------------------------------------------------------------------------------
      //handle thumbnail file upload
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
        //upload image
        $path= $request->file('path_thumbnail')->storeAs("public/media/Thematicroutes/", $fileNameToStore);

        if ($thematicroute->path_thumbnail!="")
        {
           Storage::delete('public/media/'.$thematicroute->path_thumbnail);
        }

        $thematicroute->path_thumbnail= "Thematicroutes/" . $fileNameToStore;
      }

      $thematicroute->save();
      return redirect('/thematicroutes')->with('success', 'Thematic route created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $thematicroute= Thematicroute::find($id);
         
         $thematicroutesites = \DB::table('thematicroutesites')
            ->join('sites', 'sites.id', '=', 'thematicroutesites.site_id')
            ->select('sites.name as name', 'thematicroutesites.id')
            ->where('thematicroutesites.thematicroute_id', $thematicroute->id)
            ->get();

        return view('thematicroutes.show')->with('thematicroute',$thematicroute)->with('thematicroutesites',$thematicroutesites);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $thematicroute= Thematicroute::find($id);             
        return view('thematicroutes.edit')->with('thematicroute', $thematicroute);
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
        //'description' => 'required',        
        ]);

        $thematicroute = Thematicroute::find($id);

        $thematicroute->name=$request->input('name');
        $thematicroute->name_gr=$request->input('name_gr');
        $thematicroute->name_ru=$request->input('name_ru');
        $thematicroute->name_it=$request->input('name_it');
        $thematicroute->name_fr=$request->input('name_fr');
        $thematicroute->name_ge=$request->input('name_ge');
        $thematicroute->description=$request->input('description');
        $thematicroute->description_gr=$request->input('description_gr');
        $thematicroute->description_ru=$request->input('description_ru');
        $thematicroute->description_it=$request->input('description_it');
        $thematicroute->description_fr=$request->input('description_fr');
        $thematicroute->description_ge=$request->input('description_ge');


        //handle image file upload
      if($request->hasFile('path_img'))
      {
        //get filename with the extension
        $filenameWithExt=$request->file('path_img')->getClientOriginalName();
        //get just $filename
        $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just the extension
        $extension= $request->file('path_img')->getClientOriginalExtension();
        //filename to store
        $fileNameToStore=$filename.'_'.time().'.'.$extension;
        //upload image
        $path= $request->file('path_img')->storeAs("public/media/Thematicroutes/", $fileNameToStore);

        if ($thematicroute->path_img!="")
        {
           Storage::delete('public/media/'.$thematicroute->path_img);
        }

        $thematicroute->path_img= "Thematicroutes/" . $fileNameToStore;
      }
     
      //---------------------------------------------------------------------------------
      //handle thumbnail file upload
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
        //upload image
        $path= $request->file('path_thumbnail')->storeAs("public/media/Thematicroutes/", $fileNameToStore);

        if ($thematicroute->path_thumbnail!="")
        {
           Storage::delete('public/media/'.$thematicroute->path_thumbnail);
        }

        $thematicroute->path_thumbnail= "Thematicroutes/" . $fileNameToStore;
      }
     
     
      $thematicroute->save();
      return redirect('/thematicroutes')->with('success', 'Thematic route updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $thematicroute = Thematicroute::find($id);
      Storage::delete('public/media/'.$thematicroute->path_img);//delete the previous img if exist
      Storage::delete('public/media/'.$thematicroute->path_thumbnail);//delete the previous thumbnail if exist
      $thematicroute->delete();

      return redirect('/thematicroutes')->with('success', 'Thematic route removed!');
    }
}
