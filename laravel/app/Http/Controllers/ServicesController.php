<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Servicecategory;
use App\Service;
use App\Registeredservices;
use Illuminate\Support\Facades\Mail;
use DB;

class ServicesController extends Controller
{
    public function api_getservices()
    {
        $services = \DB::table('services')
            ->join('registeredservices', 'services.registeredservice_id', '=', 'registeredservices.id')
            ->select( 'registeredservices.name as service_name', 'services.*')            
            ->get();

        return response(['status' => 'success', 'data' => $services]);
    }


    public function api_getservice(Request $request)
    {
        
        $service = \DB::table('services')
            ->join('registeredservices', 'services.registeredservice_id', '=', 'registeredservices.id')
            ->select( 'registeredservices.name as service_name', 'services.*')            
            ->where('services.id',$request->input('id'))
            ->get();

        if (count($service)>0)
        {
            return response(['status' => 'success', 'data' => $service[0]]);
        }
        else
        {
            return response(['status' => 'success', 'data' => 'No service found!']);
        }

        
    }

    public function api_getservicecategories()
    {
        return response(['status' => 'success', 'data' => Servicecategory::get()]);
    }

    public function service_email_reminder()
    {        
        $services = Service::get()->where('telephone', '=',null);
        foreach ($services as $service) {

            $data = array('email'=>$service->email,'service_id'=>$service->id); 
            Mail::send([], $data, function ($message) use ($data){
              $message
              ->to($data['email'])
              ->subject('Confirmation of Registration-EnterCY')
              ->setBody("Welcome to EnterCY platform and congratulations about your establishment's registration! \n ---------------------------- \n\n Please follow the link below in order to complete additional details about your establishment: \n https://entercy.cs.ucy.ac.cy/services/" . $data['service_id'] . "/edit  \n\n Respectfully, \n EnterCY Team"); // assuming text/plain
            });
        }
        return response(['status' => 'success', 'data' => $services]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = \DB::table('services')
            ->join('registeredservices', 'services.registeredservice_id', '=', 'registeredservices.id')
            ->select('services.id', 'registeredservices.name')            
            ->get();

        //$services= Service::orderBy('created_at','desc')->paginate(10);
        return view('services.index')->with('services',$services);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $servicecategories = Servicecategory::get();
        $registered_services = Registeredservices::get();
        return view('services.create')->with('servicecategories', $servicecategories)->with('registered_services', $registered_services);
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
            'registeredservice_id' => 'required',
            //'name' => 'required',
            'email' => 'required',
            //'telephone' => 'required',
            //'address' => 'required',
            //'license' => 'mimes:jpeg,jpg,png,pdf|required',
            //'logo' => 'mimes:jpeg,jpg,png|required',
            //'email' => 'required',
            //'image_1' => 'mimes:jpeg,jpg,png|required',
            //'service_category' => 'required',
        ],
        [  'registeredservice_id.required' => 'Please select establishment/service. It is a required field!',
        ]);

        $service = new Service;
        $service->registeredservice_id = $request->input('registeredservice_id');
        $service->name = $request->input('name');
        $service->name_gr = $request->input('name_gr');
        $service->name_ru = $request->input('name_ru');
        $service->name_it = $request->input('name_it');
        $service->name_fr = $request->input('name_fr');
        $service->name_ge = $request->input('name_ge');
        $service->description = $request->input('description');
        $service->description_gr = $request->input('description_gr');
        $service->description_ru = $request->input('description_ru');
        $service->description_it = $request->input('description_it');
        $service->description_fr = $request->input('description_fr');
        $service->description_ge = $request->input('description_ge');
        $service->email = $request->input('email');
        $service->telephone = $request->input('telephone');
        $service->address = $request->input('address');
        $service->coord_lat = $request->input('coord_lat');
        $service->coord_long = $request->input('coord_long');
        $service->website = $request->input('website');
        $service->servicecategory_id = $request->input('service_category');
        $service->other_servicecategory = $request->input('other_servicecategory');
        $service->monday_start = $request->input('monday_start');
        $service->monday_end = $request->input('monday_end');
        $service->tuesday_start = $request->input('tuesday_start');
        $service->tuesday_end = $request->input('tuesday_end');
        $service->wednesday_start = $request->input('wednesday_start');
        $service->wednesday_end = $request->input('wednesday_end');
        $service->thursday_start = $request->input('thursday_start');
        $service->thursday_end = $request->input('thursday_end');
        $service->friday_start = $request->input('friday_start');
        $service->friday_end = $request->input('friday_end');
        $service->saturday_start = $request->input('saturday_start');
        $service->saturday_end = $request->input('saturday_end');
        $service->sunday_start = $request->input('sunday_start');
        $service->sunday_end = $request->input('sunday_end');
        $service->month_closed_from = $request->input('monthclosed_from');
        $service->month_closed_to = $request->input('monthclosed_to');
        $service->premises = $request->input('premisesType');
        $service->cuisine_type1 = $request->input('cuisine_type1');
        $service->cuisine_type2 = $request->input('cuisine_type2');
        $service->cuisine_type3 = $request->input('cuisine_type3');
        $service->cuisine_type4 = $request->input('cuisine_type4');
        $service->cuisine_type5 = $request->input('cuisine_type5');
        $service->other_cuisine_type = $request->input('other_cusine_type');
        $service->dietary_restr1 = $request->input('dietary_restr1');
        $service->dietary_restr2 = $request->input('dietary_restr2');
        $service->dietary_restr3 = $request->input('dietary_restr3');
        $service->price = $request->input('price');
        $service->hotel_class = $request->input('hotel_class');
        $service->enabled = 'Yes';
        $service->save();

        //handle file upload
        if ($request->hasFile('logo')) {
            //get filename with the extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('logo')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_logo_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('logo')->storeAs("public/media/Services/{$service->id}", $fileNameToStore);

            if ($service->path_logo != "") {
                Storage::delete('public/media/' . $service->path_logo);
            }

            //save only the image on the right folder path
            $service->path_logo = "Services/" . $service->id . "/" . $fileNameToStore;
            $service->save();
        }

        //handle file upload
        if ($request->hasFile('license')) {
            //get filename with the extension
            $filenameWithExt = $request->file('license')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('license')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_license_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('license')->storeAs("public/media/Services/{$service->id}", $fileNameToStore);

            if ($service->license != "") {
                Storage::delete('public/media/' . $service->license);
            }

            //save only the image on the right folder path
            $service->license = "Services/" . $service->id . "/" . $fileNameToStore;
            $service->save();
        }

        //handle file upload
        if ($request->hasFile('image_1')) {
            //get filename with the extension
            $filenameWithExt = $request->file('image_1')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('image_1')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_image_1_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('image_1')->storeAs("public/media/Services/{$service->id}", $fileNameToStore);

            if ($service->path_img1 != "") {
                Storage::delete('public/media/' . $service->path_img1);
            }

            //save only the image on the right folder path
            $service->path_img1 = "Services/" . $service->id . "/" . $fileNameToStore;
            $service->save();
        }

        //handle file upload
        if ($request->hasFile('image_2')) {
            //get filename with the extension
            $filenameWithExt = $request->file('image_2')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('image_2')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_image_2_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('image_2')->storeAs("public/media/Services/{$service->id}", $fileNameToStore);

            if ($service->path_img2 != "") {
                Storage::delete('public/media/' . $service->path_img2);
            }

            //save only the image on the right folder path
            $service->path_img2 = "Services/" . $service->id . "/" . $fileNameToStore;
            $service->save();
        }

        //handle file upload
        if ($request->hasFile('image_3')) {
            //get filename with the extension
            $filenameWithExt = $request->file('image_3')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('image_3')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_image_3_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('image_3')->storeAs("public/media/Services/{$service->id}", $fileNameToStore);

            if ($service->path_img3 != "") {
                Storage::delete('public/media/' . $service->path_img3);
            }

            //save only the image on the right folder path
            $service->path_img3 = "Services/" . $service->id . "/" . $fileNameToStore;
            $service->save();
        }


         //REMEMBER THIS WORKS ONLY IN PRODUCTION SERVER
        /*$data = array('email'=>'sotiriscon92@gmail.com');
        Mail::send([], $data, function ($message) use ($data){
          $message
          ->to($data['email'])
          ->subject('Confirmation of Registration-EnterCY')
          ->setBody('Congratulations for your Registration, welcome to EnterCY platform!'); // assuming text/plain
        });*/
        
         $data = array('email'=>$service->email,'service_id'=>$service->id); 
        Mail::send([], $data, function ($message) use ($data){
          $message
          ->to($data['email'])
          ->subject('Confirmation of Registration-EnterCY')
          ->setBody("Welcome to EnterCY platform and congratulations about your establishment's registration! \n ---------------------------- \n\n Please follow the link below in order to complete additional details about your establishment: \n https://entercy.cs.ucy.ac.cy/services/" . $data['service_id'] . "/edit  \n\n Respectfully, \n EnterCY Team"); // assuming text/plain
        });

        return view('services.success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::find($id);
        $servicecategories = Servicecategory::get();
        $registered_services = Registeredservices::get();
        return view('services.show')->with('service', $service)->with('servicecategories', $servicecategories)->with('registered_services', $registered_services);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::find($id);
        $servicecategories = Servicecategory::get();        
        $registered_services = Registeredservices::get();
        return view('services.edit')->with('service', $service)->with('servicecategories', $servicecategories)->with('registered_services', $registered_services);
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
            //'name' => 'required',
            'email' => 'required',
            'telephone' => 'required',
            'address' => 'required',
            //'license' => 'mimes:jpeg,jpg,png,pdf|required',
            //'logo' => 'mimes:jpeg,jpg,png|required',            
            //'image_1' => 'mimes:jpeg,jpg,png|required',
            'service_category' => 'required',
        ]);

        $service = Service::find($id);
        $service->name = $request->input('name');
        $service->name_gr = $request->input('name_gr');
        $service->name_ru = $request->input('name_ru');
        $service->name_it = $request->input('name_it');
        $service->name_fr = $request->input('name_fr');
        $service->name_ge = $request->input('name_ge');
        $service->description = $request->input('description');
        $service->description_gr = $request->input('description_gr');
        $service->description_ru = $request->input('description_ru');
        $service->description_it = $request->input('description_it');
        $service->description_fr = $request->input('description_fr');
        $service->description_ge = $request->input('description_ge');
        $service->email = $request->input('email');
        $service->telephone = $request->input('telephone');
        $service->address = $request->input('address');
        $service->coord_lat = $request->input('coord_lat');
        $service->coord_long = $request->input('coord_long');
        $service->website = $request->input('website');
        $service->servicecategory_id = $request->input('service_category');
        $service->other_servicecategory = $request->input('other_servicecategory');
        $service->monday_start = $request->input('monday_start');
        $service->monday_end = $request->input('monday_end');
        $service->tuesday_start = $request->input('tuesday_start');
        $service->tuesday_end = $request->input('tuesday_end');
        $service->wednesday_start = $request->input('wednesday_start');
        $service->wednesday_end = $request->input('wednesday_end');
        $service->thursday_start = $request->input('thursday_start');
        $service->thursday_end = $request->input('thursday_end');
        $service->friday_start = $request->input('friday_start');
        $service->friday_end = $request->input('friday_end');
        $service->saturday_start = $request->input('saturday_start');
        $service->saturday_end = $request->input('saturday_end');
        $service->sunday_start = $request->input('sunday_start');
        $service->sunday_end = $request->input('sunday_end');
        $service->month_closed_from = $request->input('monthclosed_from');
        $service->month_closed_to = $request->input('monthclosed_to');
        $service->premises = $request->input('premisesType');
        $service->cuisine_type1 = $request->input('cuisine_type1');
        $service->cuisine_type2 = $request->input('cuisine_type2');
        $service->cuisine_type3 = $request->input('cuisine_type3');
        $service->cuisine_type4 = $request->input('cuisine_type4');
        $service->cuisine_type5 = $request->input('cuisine_type5');
        $service->other_cuisine_type = $request->input('other_cusine_type');
        $service->dietary_restr1 = $request->input('dietary_restr1');
        $service->dietary_restr2 = $request->input('dietary_restr2');
        $service->dietary_restr3 = $request->input('dietary_restr3');
        $service->price = $request->input('price');
        $service->hotel_class = $request->input('hotel_class');
        $service->enabled = 'Yes';
        $service->save();

        //handle file upload
        if ($request->hasFile('logo')) {
            //get filename with the extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('logo')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_logo_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('logo')->storeAs("public/media/Services/{$service->id}", $fileNameToStore);

            if ($service->path_logo != "") {
                Storage::delete('public/media/' . $service->path_logo);
            }

            //save only the image on the right folder path
            $service->path_logo = "Services/" . $service->id . "/" . $fileNameToStore;
            $service->save();
        }

        //handle file upload
        if ($request->hasFile('license')) {
            //get filename with the extension
            $filenameWithExt = $request->file('license')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('license')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_license_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('license')->storeAs("public/media/Services/{$service->id}", $fileNameToStore);

            if ($service->license != "") {
                Storage::delete('public/media/' . $service->license);
            }

            //save only the image on the right folder path
            $service->license = "Services/" . $service->id . "/" . $fileNameToStore;
            $service->save();
        }

        //handle file upload
        if ($request->hasFile('image_1')) {
            //get filename with the extension
            $filenameWithExt = $request->file('image_1')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('image_1')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_image_1_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('image_1')->storeAs("public/media/Services/{$service->id}", $fileNameToStore);

            if ($service->path_img1 != "") {
                Storage::delete('public/media/' . $service->path_img1);
            }

            //save only the image on the right folder path
            $service->path_img1 = "Services/" . $service->id . "/" . $fileNameToStore;
            $service->save();
        }

        //handle file upload
        if ($request->hasFile('image_2')) {
            //get filename with the extension
            $filenameWithExt = $request->file('image_2')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('image_2')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_image_2_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('image_2')->storeAs("public/media/Services/{$service->id}", $fileNameToStore);

            if ($service->path_img2 != "") {
                Storage::delete('public/media/' . $service->path_img2);
            }

            //save only the image on the right folder path
            $service->path_img2 = "Services/" . $service->id . "/" . $fileNameToStore;
            $service->save();
        }

        //handle file upload
        if ($request->hasFile('image_3')) {
            //get filename with the extension
            $filenameWithExt = $request->file('image_3')->getClientOriginalName();
            //get just $filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just the extension
            $extension = $request->file('image_3')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_image_3_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('image_3')->storeAs("public/media/Services/{$service->id}", $fileNameToStore);

            if ($service->path_img3 != "") {
                Storage::delete('public/media/' . $service->path_img3);
            }

            //save only the image on the right folder path
            $service->path_img3 = "Services/" . $service->id . "/" . $fileNameToStore;
            $service->save();
        }

        return view('services.success');
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
