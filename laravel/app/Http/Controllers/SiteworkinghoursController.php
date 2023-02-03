<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Site;
use App\Poi;
use App\Poichildren;
use App\Poimedia;
use App\User;
use App\Cy_district;
use App\Sitecategory;
use App\Accessibility;
use App\Siteaccessibility;
use App\Siteworkinghour;
use DB;

class SiteworkinghoursController extends Controller
{

    public function api_getsiteworkinghour(Request $request)
    {
        $data=Siteworkinghour::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_getsiteworkinghour_by_siteid(Request $request)
    {
        $data=Siteworkinghour::where('site_id', $request->input('id'))->get();
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
    public function create($siteid)
    {
        return view('siteworkinghours.create')->with('siteid', $siteid);
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
          'siteid' => 'required',
          'day' => 'required'
        ]);

          $siteworkinghour = new Siteworkinghour;
          $siteworkinghour->site_id=$request->input('siteid');
          $siteworkinghour->day=$request->input('day');
          $siteworkinghour->open_time=$request->input('opentime');
          $siteworkinghour->close_time=$request->input('closetime');
          $siteworkinghour->break_time_start=$request->input('breaktimestart');
          $siteworkinghour->break_time_end=$request->input('breaktimeend');
          $siteworkinghour->day_gr=$request->input('day_gr');
          $siteworkinghour->day_ru=$request->input('day_ru');
          $siteworkinghour->day_it=$request->input('day_it');
          $siteworkinghour->day_fr=$request->input('day_fr');
          $siteworkinghour->day_ge=$request->input('day_ge');
          $siteworkinghour->save();

          $site= Site::find($request->input('siteid'));
          $poi= Poi::find($site->main_poi);
          $sitecategory= Sitecategory::find($site->category);
          $cydistrict= Cy_district::find($site->district);

          $siteaccessibility = \DB::table('siteaccessibilities')
            ->join('accessibilities', 'accessibilities.id', '=', 'siteaccessibilities.accessibility_id')
            ->select('accessibilities.name as name', 'siteaccessibilities.id')
            ->where('siteaccessibilities.site_id', $site->id)
            ->get();

           $siteworkinghour=Siteworkinghour::where('site_id', $site->id)->get();

          return view('sites.show')->with('site',$site)->with('poi',$poi)->with('sitecategory', $sitecategory)->with('cydistrict', $cydistrict)->with('siteaccessibility', $siteaccessibility)->with('siteworkinghour', $siteworkinghour);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $siteworkinghour= Siteworkinghour::find($id);      
      return view('siteworkinghours.show')->with('siteworkinghour',$siteworkinghour);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $siteworkinghour= Siteworkinghour::find($id);
        return view('siteworkinghours.edit')->with('siteworkinghour',$siteworkinghour);
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
          'day' => 'required'
        ]);

          $siteworkinghour= Siteworkinghour::find($id);
          $siteworkinghour->day=$request->input('day');
          $siteworkinghour->open_time=$request->input('opentime');
          $siteworkinghour->close_time=$request->input('closetime');
          $siteworkinghour->break_time_start=$request->input('breaktimestart');
          $siteworkinghour->break_time_end=$request->input('breaktimeend');
          $siteworkinghour->day_gr=$request->input('day_gr');
          $siteworkinghour->day_ru=$request->input('day_ru');
          $siteworkinghour->day_it=$request->input('day_it');
          $siteworkinghour->day_fr=$request->input('day_fr');
          $siteworkinghour->day_ge=$request->input('day_ge');
          $siteworkinghour->save();

          $site= Site::find($siteworkinghour->site_id);
          $poi= Poi::find($site->main_poi);
          $sitecategory= Sitecategory::find($site->category);
          $cydistrict= Cy_district::find($site->district);

          $siteaccessibility = \DB::table('siteaccessibilities')
            ->join('accessibilities', 'accessibilities.id', '=', 'siteaccessibilities.accessibility_id')
            ->select('accessibilities.name as name', 'siteaccessibilities.id')
            ->where('siteaccessibilities.site_id', $site->id)
            ->get();

           $siteworkinghour=Siteworkinghour::where('site_id', $site->id)->get();

          return view('sites.show')->with('site',$site)->with('poi',$poi)->with('sitecategory', $sitecategory)->with('cydistrict', $cydistrict)->with('siteaccessibility', $siteaccessibility)->with('siteworkinghour', $siteworkinghour);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          $siteworkinghour = Siteworkinghour::find($id); 
          $siteID= $siteworkinghour->site_id;
          $siteworkinghour->delete(); 

          $site= Site::find($siteID);
          $poi= Poi::find($site->main_poi);
          $sitecategory= Sitecategory::find($site->category);
          $cydistrict= Cy_district::find($site->district);

          $siteaccessibility = \DB::table('siteaccessibilities')
            ->join('accessibilities', 'accessibilities.id', '=', 'siteaccessibilities.accessibility_id')
            ->select('accessibilities.name as name', 'siteaccessibilities.id')
            ->where('siteaccessibilities.site_id', $site->id)
            ->get();

           $siteworkinghour=Siteworkinghour::where('site_id', $site->id)->get();

          return view('sites.show')->with('site',$site)->with('poi',$poi)->with('sitecategory', $sitecategory)->with('cydistrict', $cydistrict)->with('siteaccessibility', $siteaccessibility)->with('siteworkinghour', $siteworkinghour);
    }
}
