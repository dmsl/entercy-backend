<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use App\App_content;
use App\Site;
use App\Poimedia;
use App\Poimediatype;
use App\Storytelling;

class App_ContentsController extends Controller
{

    public function api_get_app_contents()
    {
        return response(['status' => 'success', 'data' => App_content::get()]);
    }
    public function api_get_app_content(Request $request)
    {
        $data=App_content::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_get_app_content_video(Request $request)
    {
        $data=App_content::where('name', 'Explore (video)')->first();
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_landingpage_categories(Request $request)
    {
        $data=App_content::where('name', 'Sites')->orWhere('name', 'Content')->orWhere('name', 'Chronological')->orWhere('name', 'Location')->orWhere('name', 'Thematic Routes')->get();
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_login_info(Request $request)
    {
        $data=App_content::where('name', 'Login')->get();
        $name=$data[0]->name;
        $backgroundImg=$data[0]->path_img;
        $colour=$data[0]->description;
        $array[0] = array('name'=> $name,'backgroundImg' => $backgroundImg, 'colour' => $colour);

        return response(['status' => 'success', 'data' => $array]);
    }

    public function api_register_info(Request $request)
    {
        $data=App_content::where('name', 'Register')->get();
        $name=$data[0]->name;
        $backgroundImg=$data[0]->path_img;
        $colour=$data[0]->description;
        $array[0] = array('name'=> $name,'backgroundImg' => $backgroundImg, 'colour' => $colour);

        return response(['status' => 'success', 'data' => $array]);
    }

    public function api_join_info(Request $request)
    {
        $data=App_content::where('name', 'Join')->get();
        $name=$data[0]->name;
        $backgroundImg=$data[0]->path_img;
        $colour=$data[0]->description;
        $array[0] = array('name'=> $name,'backgroundImg' => $backgroundImg, 'colour' => $colour);

        return response(['status' => 'success', 'data' => $array]);
    }

    public function api_about_us(Request $request)
    {
        $myArray = [];
        $array3 =[];
        $aboutus=App_content::where('name', 'About us')->orWhere('name', 'Our aim')->orWhere('name', 'Contact us')->get();
        $array1 = array('About us' => $aboutus);

        $sites=count(Site::all());
        $stories=count(Storytelling::all());

        $images = \DB::table('poimedia')
            ->join('poimediatypes', 'poimediatypes.id', '=', 'poimedia.type')
            ->select('poimedia.id as poi media id')
            ->where('poimediatypes.name', 'Image File')
            ->get();
        $images=count($images);

        $videos = \DB::table('poimedia')
            ->join('poimediatypes', 'poimediatypes.id', '=', 'poimedia.type')
            ->select('poimedia.id as poi media id')
            ->where('poimediatypes.name', 'Video File')
            ->get();
        $videos=count($videos);

       /* $zipfiles360 = \DB::table('poimedia')
            ->join('poimediatypes', 'poimediatypes.id', '=', 'poimedia.type')
            ->select('poimedia.id as poi media id')
            ->where('poimediatypes.name', 'LIKE','%Zip%')
            ->get();
        $zipfiles360=count($zipfiles360);*/
        //3D Model Mobile (GTFL)
        $Model_Mobile = \DB::table('poimedia')
        ->join('poimediatypes', 'poimediatypes.id', '=', 'poimedia.type')
        ->select('poimedia.id as poi media id')
        ->where('poimediatypes.name', '3D Model Mobile (GTFL)')
        ->get();
        $Model_Mobile=count($Model_Mobile);
        //360 Tours
        $Tours = \DB::table('poimedia')
        ->join('poimediatypes', 'poimediatypes.id', '=', 'poimedia.type')
        ->select('poimedia.id as poi media id')
        ->where('poimediatypes.name', '360 Tour')
        ->get();
        $Tours=count($Tours);
        //360 Single Image 360 Images
        $single_image = \DB::table('poimedia')
        ->join('poimediatypes', 'poimediatypes.id', '=', 'poimedia.type')
        ->select('poimedia.id as poi media id')
        ->where('poimediatypes.name', '360 single (Image)')
        ->get();
        $single_image=count($single_image);
        $array3[sizeof($array3)] = array('Sites'=> $sites,'2D Videos' => $videos, 'Audio Story Tellings' => $stories, 'Virtual Reality Experiences' => $Tours, '2D Images' => $images, 'Augmented Reality Reconstructions' => $Model_Mobile, '360 Images' => $single_image);

        $array3 = array('About us COUNTER' => $array3);
        $merged_array = $array1 + $array3;
        array_push($myArray,  $merged_array );

        return response(['status' => 'success', 'data' => $myArray]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appcontents= App_content::get();
        return view('appcontent.index')->with('appcontents',$appcontents);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $appcontents= App_content::find($id);
        return view('appcontent.edit')->with('appcontents', $appcontents);
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
         //update app content
    $appcontents = App_content::find($id);

    $appcontents->description=$request->input('description');
    $appcontents->description_gr=$request->input('description_gr');
    $appcontents->description_ru=$request->input('description_ru');
    $appcontents->description_it=$request->input('description_it');
    $appcontents->description_fr=$request->input('description_fr');
    $appcontents->description_ge=$request->input('description_ge');
    $appcontents->name_gr=$request->input('name_gr');
    $appcontents->name_ru=$request->input('name_ru');
    $appcontents->name_it=$request->input('name_it');
    $appcontents->name_fr=$request->input('name_fr');
    $appcontents->name_ge=$request->input('name_ge');

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
        $path= $request->file('path_img')->storeAs("public/media/AppContent/", $fileNameToStore);

        if ($appcontents->path_img!="")
        {
           Storage::delete('public/media/'.$appcontents->path_img);
        }

        $appcontents->path_img= "AppContent/" . $fileNameToStore;
      }


      //---------------------------------------------------------------------------------
      //handle video file upload
      if($request->hasFile('path_video'))
      {
        //get filename with the extension
        $filenameWithExt=$request->file('path_video')->getClientOriginalName();
        //get just $filename
        $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just the extension
        $extension= $request->file('path_video')->getClientOriginalExtension();
        //filename to store
        $fileNameToStore=$filename.'_'.time().'.'.$extension;
        //upload image
        $path= $request->file('path_video')->storeAs("public/media/AppContent/", $fileNameToStore);

        if ($appcontents->path_video!="")
        {
           Storage::delete('public/media/'.$appcontents->path_video);
        }

        $appcontents->path_video= "AppContent/" . $fileNameToStore;
      }


      $appcontents->save();
      return redirect('/appcontent')->with('success', 'App content updated');
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

    public function delete_appcontent_img($id)
    {
         $appcontents = App_content::find($id);
         if ($appcontents->path_img!="")
        {
           Storage::delete('public/media/'.$appcontents->path_img);
           $appcontents->path_img= "";
           $appcontents->save();
        }

        return redirect('/appcontent')->with('success', "App content's image deleted" );
    }

    public function delete_appcontent_video($id)
    {
         $appcontents = App_content::find($id);
         if ($appcontents->path_video!="")
        {
           Storage::delete('public/media/'.$appcontents->path_video);
           $appcontents->path_video= "";
           $appcontents->save();
        }

        return redirect('/appcontent')->with('success', "App content's video deleted" );
    }

}
