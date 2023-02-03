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


class SiteaccessibilitiesController extends Controller
{

    public function api_getsiteaccessibility(Request $request)
    {
        $data=Siteaccessibility::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_getsiteaccessibilities_by_siteid(Request $request)
    {
        $data=Siteaccessibility::where('site_id', $request->input('id'))->get();
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
        $accessibility= Accessibility::get();
        return view('siteaccessibilities.create')->with('siteid', $siteid)->with('accessibility', $accessibility);
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
          'accessibility' => 'required'
        ]);

          $siteaccessibility = new Siteaccessibility;
          $siteaccessibility->site_id=$request->input('siteid');
          $siteaccessibility->accessibility_id=$request->input('accessibility');
          $siteaccessibility->save();

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
          $siteaccessibility = Siteaccessibility::find($id); 
          $siteID= $siteaccessibility->site_id;
          $siteaccessibility->delete(); 

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
