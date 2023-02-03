<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Outdoor_link;
use App\Outdoor_group;
use App\Qr_room_link;
use App\Qr_room;
use App\Poi;
use App\Poimedia;
use App\Qr_room_group;
use App\Storytelling;
use Illuminate\Support\Facades\Validator;

class Outdoor_linksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function api_get_sites_by_outdoor_link_id(Request $request){
        $outdoor_link_id = $request->input('outdoor_link_id');
        $outdoor_link=Outdoor_link::find($outdoor_link_id);

        if(isset($outdoor_link)){
          return response(['status' => 'success', 'data' => $outdoor_link]);
        }
        return response(['status' => 'success', 'data' => '']);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($outdoor_group_id)
    {
        #$outdoor_link = Outdoor_link::find($outdoor_link_id);
        $outdoor_group = Outdoor_group::find($outdoor_group_id);

        $poi = Poi::find($outdoor_group->poi_id);
        $tempTable = [$poi->id];
        $isZero = false;
        $poimedia_merged = [];
        $poimedia_DONT_include = [];
        $storytelling_merged = [];
        $storytelling_DONT_include = [];
        while ($isZero == false) {
            $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
            $poimedia = Poimedia::where('poi_id', $tempTable[0])->get();
            $storytellings = Storytelling::where('poi_id', $tempTable[0])->get();
            $temp_outdoor_groups = Outdoor_group::where('poi_id', $tempTable[0])->get();
            foreach ($poimedia as $poi_media) {
                foreach ($temp_outdoor_groups as $temp_outdoor_group) {
                    $temp_outdoor_links = Outdoor_link::where('outdoor_group_id', $temp_outdoor_group->id)->where('poi_media_id', $poi_media->id)->get();
                    if (count($temp_outdoor_links) > 0) { //if not empty goes here-ara iparxi etsi push sto DONT INCLUDE
                        array_push($poimedia_DONT_include, $poi_media);
                    } else { //if  empty goes here -ara den iparxi etsi to kanume push kai to dixnume
                        array_push($poimedia_merged, $poi_media);
                    }
                }
            }
            foreach ($storytellings as $storytelling) {
                foreach ($temp_outdoor_groups as $temp_outdoor_group) {
                    $temp_outdoor_links = Outdoor_link::where('outdoor_group_id', $temp_outdoor_group->id)->where('storytelling_id', $storytelling->id)->get();
                    if (count($temp_outdoor_links) > 0) { //if not empty goes here-ara iparxi etsi push sto DONT INCLUDE
                        array_push($storytelling_DONT_include, $storytelling);
                    } else { //if  empty goes here -ara den iparxi etsi to kanume push kai to dixnume
                        array_push($storytelling_merged, $storytelling);
                    }
                }
            }

            //array_push($poimedia_merged, $poimedia);
            \array_splice($tempTable, 0, 1); //removes 1st record from array
            $loopIter = count($poichildren);
            for ($x = 0; $x < $loopIter; $x++) {
                array_push($tempTable, $poichildren[$x]->id);
            }
            if (($loopIter == 0) && (count($tempTable) == 0)) {
                $isZero = true;
            }
        }

        //removes from array the ones that have already added to qr room links
        foreach ($poimedia_DONT_include as $pm_DONT_include) {
            $poimedia_merged = array_diff($poimedia_merged, [$pm_DONT_include]);
        }
        foreach ($storytelling_DONT_include as $story_DONT_include) {
            $storytelling_merged = array_diff($storytelling_merged, [$story_DONT_include]);
        }

        $poimedia_merged = array_unique($poimedia_merged);
        $storytelling_merged = array_unique($storytelling_merged);

        return view('outdoor_links.create')->with('outdoor_group', $outdoor_group)->with('poimedia_merged', $poimedia_merged)->with('storytelling_merged', $storytelling_merged);
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
            'name' => 'required'
        ]);

        $poi_media = Validator::make($request->all(), [
            'poi_media_id' => 'required',
        ]);
        $storytelling = Validator::make($request->all(), [
            'storytelling_id' => 'required',
        ]);
        if (!$storytelling->fails() && !$poi_media->fails()) {
            return redirect('create-outdoor-links/' . $request->input('outdoor_group_id'))
                ->withErrors("You cant choose both poi media and storytelling choose only one of them")
                ->withInput();
        } else if ($storytelling->fails() && $poi_media->fails()) {
            return redirect('create-outdoor-links/' . $request->input('outdoor_group_id'))
                ->withErrors("You need to choose one poi media or one storytelling not both")
                ->withInput();
        }

        $outdoor_link = new Outdoor_link();
        $outdoor_link->name = $request->input('name');
        $outdoor_link->outdoor_group_id = $request->input('outdoor_group_id');

        $outdoor_link->x_scale = $request->input('x_scale');
        $outdoor_link->y_scale = $request->input('y_scale');
        $outdoor_link->z_scale = $request->input('z_scale');

        $outdoor_link->x_rotation = $request->input('x_rotation');
        $outdoor_link->y_rotation = $request->input('y_rotation');
        $outdoor_link->z_rotation = $request->input('z_rotation');

        $outdoor_link->latitude = $request->input('latitude');
        $outdoor_link->longitude = $request->input('longitude');

        $outdoor_link->poi_media_id = $request->input('poi_media_id');
        $outdoor_link->storytelling_id = $request->input('storytelling_id');

        $outdoor_link->save();

        $outdoor_groups = Outdoor_group::find($outdoor_link->outdoor_group_id);
        $outdoor_links = Outdoor_link::where('outdoor_group_id', $outdoor_groups->id)->get();
        return view('outdoor_groups.show')->with('outdoor_groups', $outdoor_groups)->with('outdoor_links', $outdoor_links);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($outdoor_link_id)
    {
        $outdoor_link = Outdoor_link::find($outdoor_link_id);
        $outdoor_group = Outdoor_group::find($outdoor_link->outdoor_group_id);
        $poimedia = Poimedia::find($outdoor_link->poi_media_id);
        $storytelling = Storytelling::find($outdoor_link->storytelling_id);
        return view('outdoor_links.show')->with('outdoor_group', $outdoor_group)->with('outdoor_links', $outdoor_link)->with('poimedia', $poimedia)->with('storytelling', $storytelling);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($outdoor_link_id)
    {
        $outdoor_link = Outdoor_link::find($outdoor_link_id);
        $outdoor_group = Outdoor_group::find($outdoor_link->outdoor_group_id);

        $poi = Poi::find($outdoor_group->poi_id);
        $tempTable = [$poi->id];
        $isZero = false;
        $poimedia_merged = [];
        $poimedia_DONT_include = [];
        $storytelling_merged = [];
        $storytelling_DONT_include = [];
        while ($isZero == false) {
            $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
            $poimedia = Poimedia::where('poi_id', $tempTable[0])->get();
            $storytellings = Storytelling::where('poi_id', $tempTable[0])->get();
            $temp_outdoor_groups = Outdoor_group::where('poi_id', $tempTable[0])->get();
            foreach ($poimedia as $poi_media) {
                foreach ($temp_outdoor_groups as $temp_outdoor_group) {
                    $temp_outdoor_links = Outdoor_link::where('outdoor_group_id', $temp_outdoor_group->id)->where('poi_media_id', $poi_media->id)->get();
                    if (count($temp_outdoor_links) > 0) { //if not empty goes here-ara iparxi etsi push sto DONT INCLUDE
                        array_push($poimedia_DONT_include, $poi_media);
                    } else { //if  empty goes here -ara den iparxi etsi to kanume push kai to dixnume
                        array_push($poimedia_merged, $poi_media);
                    }
                }
            }
            foreach ($storytellings as $storytelling) {
                foreach ($temp_outdoor_groups as $temp_outdoor_group) {
                    $temp_outdoor_links = Outdoor_link::where('outdoor_group_id', $temp_outdoor_group->id)->where('storytelling_id', $storytelling->id)->get();
                    if (count($temp_outdoor_links) > 0) { //if not empty goes here-ara iparxi etsi push sto DONT INCLUDE
                        array_push($storytelling_DONT_include, $storytelling);
                    } else { //if  empty goes here -ara den iparxi etsi to kanume push kai to dixnume
                        array_push($storytelling_merged, $storytelling);
                    }
                }
            }

            //array_push($poimedia_merged, $poimedia);
            \array_splice($tempTable, 0, 1); //removes 1st record from array
            $loopIter = count($poichildren);
            for ($x = 0; $x < $loopIter; $x++) {
                array_push($tempTable, $poichildren[$x]->id);
            }
            if (($loopIter == 0) && (count($tempTable) == 0)) {
                $isZero = true;
            }
        }

        //removes from array the ones that have already added to qr room links
        foreach ($poimedia_DONT_include as $pm_DONT_include) {
            $poimedia_merged = array_diff($poimedia_merged, [$pm_DONT_include]);
        }
        foreach ($storytelling_DONT_include as $story_DONT_include) {
            $storytelling_merged = array_diff($storytelling_merged, [$story_DONT_include]);
        }

        //gets current selections for edit window preselect options
        $current_poi_media_selected = Poimedia::where('id', $outdoor_link->poi_media_id)->get();
        if (count($current_poi_media_selected) > 0) {
            array_push($poimedia_merged, $current_poi_media_selected[0]);

            $current_selection_media_OR_story = "poimedia";
            $current_selection = $current_poi_media_selected;
        }

        $current_storytelling_selected = Storytelling::where('id', $outdoor_link->storytelling_id)->get();
        if (count($current_storytelling_selected) > 0) {
            array_push($storytelling_merged,  $current_storytelling_selected[0]);

            $current_selection_media_OR_story = "storytelling";
            $current_selection = $current_storytelling_selected;
        }

        $poimedia_merged = array_unique($poimedia_merged);
        $storytelling_merged = array_unique($storytelling_merged);


        return view('outdoor_links.edit')->with('outdoor_group', $outdoor_group)->with('poimedia_merged', $poimedia_merged)->with('storytelling_merged', $storytelling_merged)->with('outdoor_links', $outdoor_link)->with('current_selection_media_OR_story',$current_selection_media_OR_story)->with('current_selection',$current_selection);
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
        ]);

        $poi_media = Validator::make($request->all(), [
            'poi_media_id' => 'required',
        ]);
        $storytelling = Validator::make($request->all(), [
            'storytelling_id' => 'required',
        ]);
        if (!$storytelling->fails() && !$poi_media->fails()) {
            return redirect('outdoor_links/' . $id . '/edit')
                ->withErrors("You cant choose both poi media and storytelling choose only one of them")
                ->withInput();
        } else if ($storytelling->fails() && $poi_media->fails()) {
            return redirect('outdoor_links/' . $id . '/edit')
                ->withErrors("You need to choose one poi media or one storytelling not both")
                ->withInput();
        }

        $outdoor_link = Outdoor_link::find($id);
        $outdoor_link->name = $request->input('name');
        #$outdoor_link->outdoor_group_id = $request->input('outdoor_group_id');

        $outdoor_link->x_scale = $request->input('x_scale');
        $outdoor_link->y_scale = $request->input('y_scale');
        $outdoor_link->z_scale = $request->input('z_scale');

        $outdoor_link->x_rotation = $request->input('x_rotation');
        $outdoor_link->y_rotation = $request->input('y_rotation');
        $outdoor_link->z_rotation = $request->input('z_rotation');

        $outdoor_link->latitude = $request->input('latitude');
        $outdoor_link->longitude = $request->input('longitude');

        $outdoor_link->poi_media_id = $request->input('poi_media_id');
        $outdoor_link->storytelling_id = $request->input('storytelling_id');

        $outdoor_link->save();

        $outdoor_groups = Outdoor_group::find($outdoor_link->outdoor_group_id);
        $outdoor_links = Outdoor_link::where('outdoor_group_id', $outdoor_groups->id)->get();
        return view('outdoor_groups.show')->with('outdoor_groups', $outdoor_groups)->with('outdoor_links', $outdoor_links);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($outdoor_link_id)
    {
        $outdoor_link = Outdoor_link::find($outdoor_link_id);
        $outdoor_groups = Outdoor_group::find($outdoor_link->outdoor_group_id);
        $outdoor_link->delete();
        $outdoor_links = Outdoor_link::where('outdoor_group_id', $outdoor_groups->id)->get();
        return view('outdoor_groups.show')->with('outdoor_groups', $outdoor_groups)->with('outdoor_links', $outdoor_links);
    }
}
