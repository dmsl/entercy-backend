@extends('layouts.appServices')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <!---<img style="width:100%" src="https://www.imhbusiness.com/media/zoo/images/1920X550_2a6a99f26408661fd20e8e1a8d4d3b2e.jpg">--->
  <img style="width:100%;" src="{{ URL::to('/') }}/storage/media/ServicesForm/header.jpg">
  <br> <br>
  <h4><u>Include your Services on the EnterCY platform.</u></h4>

  <p>The EnterCY project is developing an online platform and smartphone application, through which tourists will be informed about the rich cultural heritage of Cyprus, as well as being provided with recommendations on a variety of activities, sightseeing and services based on their location.</p>

  <p>If you would like your establishment to be included in the list of suggested services on both the EnterCY platform and related application, please complete the form to express your interest. Note that you need to submit a copy of a valid Catering and Entertainment Establishments license, which should be uploaded on the field provided in the form. Information about your business’s services will be displayed on user request or through automatic recommendations according to the user’s preferences.</p>

  <p>For more information about the EnterCY project and platform, please follow this <a href="https://www.entercyprus.com/" target="_blank">link</a>.</p>

  <p>* Required fields.</p>

    <hr><br>
    <h4><u>Contact Information</u></h4>

    <div class="form-group">
      {{Form::label('registered_service', 'Establishment/service *')}}
      <br>
      <select name="registeredservice_id" id="registeredservice_id" class="form-control select2" required disabled>
         <option selected disabled >Please select establishment/service</option>
          @foreach($registered_services as $registered_services)
                <option value="{{$registered_services->id}}">{{$registered_services->name}}</option>
          @endforeach     
      </select>
    </div>

    <!---<div class="form-group">
    {{Form::label('name', 'Name of establishment (English) *')}}
    {{Form::text('name', $service->name, ['class' => 'form-control', 'disabled' => 'Name of establishment'] )}}
    </div>--->

    <div class="form-group">
        {{Form::label('email', 'E-mail Address *')}}
        {{Form::text('email', $service->email, ['class' => 'form-control', 'disabled' => 'E-mail Address'] )}}
      </div>

      <div class="form-group">
        {{Form::label('telephone', 'Phone Number *')}}
        {{Form::text('telephone', $service->telephone, ['class' => 'form-control', 'disabled' => 'Phone Number'] )}}
      </div>

      <div>
        {{Form::label('address', 'Address ( Please press the button to check address/coordinates online ) *')}}
          <div class="row">
              <div class="col-md-9">
                {{Form::text('address', $service->address, ['class' => 'form-control', 'disabled' => 'Address'] )}}
              </div>
              <div class="col-md-3">
               <!---<a add target="_blank" onclick="this.href='https://www.google.com/search?q=coordinates+'+document.getElementById('coordinates').value" class="btn btn-info" role="button">Check Coordinates Online</a>  --->
               <a onclick="val()" class="btn btn-info" role="button">Check Address/Coordinates Online</a>
              </div>
          </div>
        </div>
        <br>

        <div class="form-group coords ">
            {{Form::label('coords', "Coordinates (Please right click the establishment's location on the map to get the exact coordinates)")}}
            <div class="row">
                <div class="col-md">
                  {{Form::label('coord_lat', 'Latitude:')}}
                  {{Form::text('coord_lat', $service->coord_lat, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
                <div class="col-md">
                  {{Form::label('coord_long', 'Longitude:')}}
                  {{Form::text('coord_long', $service->coord_long, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
            </div>
          </div>

          <hr><br>
            <h4><u>General Information</u></h4>

            <div class="form-group licenseANDlogo ">
            {{Form::label('licenseANDlogo', "Please attach a copy of the establishment's licence and logo")}}
            <div class="row">
                <div class="col-md">
                    {{Form::label('license', 'Shows a copy of the license: *')}}
                    <br>
                    @if ($service->license != '')
                    <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$service->license}}">
                    @endif
                    <br><br>
                    <a href="{{ URL::to('/') }}/storage/media/{{$service->license}}" download>
                    <button type="button" class="btn btn-info"><i class="fa fa-download"></i> Download license</button>
                    </a>
                </div>
                <div class="col-md">
                    {{Form::label('logo', 'Show the selected logo:')}}
                    <br>
                    @if ($service->path_logo != '')
                    <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$service->path_logo}}">
                    @endif
                    <br><br>
                    <a href="{{ URL::to('/') }}/storage/media/{{$service->path_logo}}" download>
                      <button type="button" class="btn btn-info"><i class="fa fa-download"></i> Download Logo</button>
                    </a>
                </div>
                <div class="col-md">
                </div>
            </div>
            </div>

            <div class="form-group imgs ">
                {{Form::label('imgs', "Please attach the images that you would like to appear along with the establishment's information")}}
                <div class="row">
                    <div class="col-md">
                        {{Form::label('image_1', 'Show selected image:')}}
                        <br>
                        @if ($service->path_img1 != '')
                        <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$service->path_img1}}">
                        @endif
                        <br><br>
                        <a href="{{ URL::to('/') }}/storage/media/{{$service->path_img1}}" download>
                        <button type="button" class="btn btn-info"><i class="fa fa-download"></i> Download Image</button>
                        </a>
                    </div>
                    <div class="col-md">
                        {{Form::label('image_2', 'Show selected second image:')}}
                        <br>
                        @if ($service->path_img2 != '')
                        <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$service->path_img2}}">
                        @endif
                        <br><br>
                        <a href="{{ URL::to('/') }}/storage/media/{{$service->path_img2}}" download>
                        <button type="button" class="btn btn-info"><i class="fa fa-download"></i> Download Image</button>
                        </a>
                    </div>
                    <div class="col-md">
                        {{Form::label('image_3', 'Show selected third image:')}}
                        <br>
                        @if ($service->path_img3 != '')
                        <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$service->path_img3}}">
                        @endif
                        <br><br>
                        <a href="{{ URL::to('/') }}/storage/media/{{$service->path_img3}}" download>
                        <button type="button" class="btn btn-info"><i class="fa fa-download"></i> Download Image</button>
                        </a>
                    </div>
                </div>
              </div>

          <div class="form-group">
            {{Form::label('description', 'Description (English)')}}
            {{Form::textarea('description', $service->description, ['class' => 'form-control', 'rows'=>'3', 'disabled' => 'Description in English'] )}}
          </div>

          <div class="form-group">
            {{Form::label('website', 'Website')}}
            {{Form::text('website', $service->website, ['class' => 'form-control', 'disabled' => 'Website'] )}}
          </div>
          <div class="form-group">
            {{Form::label('servicecategory', 'Service Category *')}}
            <br>
            <select id="servicecategoryOption" onchange="val2()" class="browser-default custom-select form-control" name="service_category" disabled >
              <option selected disabled >Please select service category</option>
              @foreach($servicecategories as $servicecategories)
                <option value="{{$servicecategories->id}}">{{$servicecategories->name}}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            {{Form::label('other_servicecategory', 'If "Other" Service Category selected, please specify:')}}
            {{Form::text('other_servicecategory', $service->other_servicecategory, ['class' => 'form-control', 'disabled' => 'If "Other" Service Category selected, please specify'] )}}
          </div>
          <div class="form-group">
            {{Form::label('premises', 'Premises')}}
            {{Form::text('premises', $service->premises, ['class' => 'form-control', 'disabled' => 'Premises'] )}}
          </div>

          <div class="form-group time ">
            {{Form::label('time', 'Hours of operation')}}
            <div class="row">
                <div class="col-md">
                  {{Form::label('monday_start', 'Monday open time:')}}
                  {{Form::time('monday_start', $service->monday_start, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
                <div class="col-md">
                  {{Form::label('monday_end', 'Monday close time:')}}
                  {{Form::time('monday_end', $service->monday_end, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md">
                  {{Form::label('tuesday_start', 'Tuesday open time:')}}
                  {{Form::time('tuesday_start', $service->tuesday_start, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
                <div class="col-md">
                  {{Form::label('tuesday_end', 'Tuesday close time:')}}
                  {{Form::time('tuesday_end', $service->tuesday_end, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md">
                  {{Form::label('wednesday_start', 'Wednesday open time:')}}
                  {{Form::time('wednesday_start', $service->wednesday_start, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
                <div class="col-md">
                  {{Form::label('wednesday_end', 'Wednesday close time:')}}
                  {{Form::time('wednesday_end', $service->wednesday_end, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
          </div><br>
          <div class="row">
                <div class="col-md">
                  {{Form::label('thursday_start', 'Thursday open time:')}}
                  {{Form::time('thursday_start', $service->thursday_start, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
                <div class="col-md">
                  {{Form::label('thurday_end', 'Thursday close time:')}}
                  {{Form::time('thursday_end', $service->thursday_end, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
            </div><br>
          <div class="row">
                <div class="col-md">
                  {{Form::label('friday_start', 'Friday open time:')}}
                  {{Form::time('friday_start', $service->friday_start, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
                <div class="col-md">
                  {{Form::label('friday_end', 'Friday close time:')}}
                  {{Form::time('friday_end', $service->friday_end, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
            </div><br>
          <div class="row">
                <div class="col-md">
                  {{Form::label('saturday_start', 'Saturday open time:')}}
                  {{Form::time('saturday_start', $service->saturday_start, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
                <div class="col-md">
                  {{Form::label('saturday_end', 'Saturday close time:')}}
                  {{Form::time('saturday_end', $service->saturday_end, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
            </div><br>
          <div class="row">
                <div class="col-md">
                  {{Form::label('sunday_start', 'Sunday open time:')}}
                  {{Form::time('sunday_start', $service->sunday_start, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
                <div class="col-md">
                  {{Form::label('sunday_end', 'Sunday close time:')}}
                  {{Form::time('sunday_end', $service->sunday_end, ['class' => 'form-control', 'disabled' => ''] )}}
                </div>
            </div>
          </div>
          <br>
    <div class="form-group coords ">
      {{Form::label('monthsclosed', 'Please specify the period of time for which the establishment is out of operation (if applicable)')}}
      <div class="row">
          <div class="col-md">
            {{Form::label('monthclosed_from', 'From month:')}}
            {{Form::text('monthclosed_from', $service->month_closed_from, ['class' => 'form-control', 'disabled' => 'From month:'] )}}
          </div>
          <div class="col-md">
            {{Form::label('monthclosed_to', 'To month:')}}
            {{Form::text('monthclosed_to', $service->month_closed_to, ['class' => 'form-control', 'disabled' => 'To month:'] )}}
          </div>
      </div>
    </div>

    <div class="form-group cuisines ">
      <br>
      {{Form::label('cuisines', 'Types of cuisine')}}
      <div class="row">
          <div class="col-md">
              {{Form::label('cuisine_type1', 'Show cuisine type 1:')}}
          <br>
              {{Form::text('cuisine_type1', $service->cuisine_type1, ['class' => 'form-control', 'disabled' => 'cuisine_type1:'] )}}
          <br>
          </div>
          <div class="col-md">
              {{Form::label('cuisine_type2', 'Show cuisine type 2:')}}
          <br>
          {{Form::text('cuisine_type2', $service->cuisine_type2, ['class' => 'form-control', 'disabled' => 'cuisine_type2:'] )}}
          <br>
          </div>
          <div class="col-md">
              {{Form::label('cuisine_type3', 'Show cuisine type 3:')}}
          <br>
              {{Form::text('cuisine_type3', $service->cuisine_type3, ['class' => 'form-control', 'disabled' => 'cuisine_type3:'] )}}
          <br>
          </div>
          <div class="col-md">
              {{Form::label('cuisine_type4', 'Show cuisine type 4:')}}
          <br>
              {{Form::text('cuisine_type4', $service->cuisine_type4, ['class' => 'form-control', 'disabled' => 'cuisine_type4:'] )}}
          <br>
          </div>
          <div class="col-md">
              {{Form::label('cuisine_type5', 'Show cuisine type 5:')}}
          <br>
          {{Form::text('cuisine_type5', $service->cuisine_type5, ['class' => 'form-control', 'disabled' => 'cuisine_type5:'] )}}
          <br>
          </div>
      </div>
    </div>

    <div class="form-group other_cusine_type">
        {{Form::label('other_cusine_type', 'If "Other" cuisine type selected, please specify:')}}
        {{Form::text('other_cusine_type', $service->other_cuisine_type, ['class' => 'form-control', 'disabled' => 'If "Other" cuisine type selected, please specify'] )}}
    </div>

    <div class="form-group dietary ">
      <br>
      {{Form::label('dietary', 'Dietary restrictions')}}
      <div class="row">
          <div class="col-md">
              {{Form::label('dietary_restr1', 'Show dietary restriction 1:')}}
          <br>
              {{Form::text('dietary_restr1', $service->dietary_restr1, ['class' => 'form-control', 'disabled' => 'Show dietary restriction 1:'] )}}
          <br>
          </div>
          <div class="col-md">
               {{Form::label('dietary_restr2', 'Show dietary restriction 2:')}}
          <br>
          {{Form::text('dietary_restr2', $service->dietary_restr2, ['class' => 'form-control', 'disabled' => 'Show dietary restriction 2:'] )}}
          <br>
          </div>
          <div class="col-md">
               {{Form::label('dietary_restr3', 'Show dietary restriction 3:')}}
          <br>
              {{Form::text('dietary_restr3', $service->dietary_restr3, ['class' => 'form-control', 'disabled' => 'Show dietary restriction 3:'] )}}
          <br>
          </div>
      </div>
    </div>

    <div class="form-group price ">
        <br>
          <div class="row">
              <div class="col-md">
                  {{Form::label('price', 'Show price range')}}
                  <br>
                  {{Form::text('price', $service->price, ['class' => 'form-control', 'disabled' => 'Show price range:'] )}}
                  <br>
              </div>
          </div>
        </div>



        <div class="form-group hotel_class ">
        <br>
          <div class="row">
              <div class="col-md">
                  {{Form::label('hotel_class', 'Hotel class')}}
                  <br>
                  {{Form::text('hotel_class', $service->hotel_class, ['class' => 'form-control', 'disabled' => 'Show hotel class:'] )}}
                  <br>
              </div>
          </div>
        </div>

<img style="width:100%;" src="{{ URL::to('/') }}/storage/media/ServicesForm/footer.jpg">

<script>

    $( document ).ready(function() {

    $(".cuisines").hide();
    $(".dietary").hide();
    $(".price").hide();
    $(".hotel_class").hide();
    $(".other_cusine_type").hide();
    document.getElementById('servicecategoryOption').value = '{{$service->servicecategory_id}}'; //preselect type option
    document.getElementById('registeredservice_id').value = '{{$service->registeredservice_id}}'; //preselect type option
    val2(); //to enable/show/hide the right fields
    });

    function val2() {
      var e = document.getElementById("servicecategoryOption");
      var optionType = e.options[e.selectedIndex].text;
      //alert(optionType);
      if (optionType.includes("Restaurant")) {
          $(".cuisines").show();
        $(".dietary").show();
        $(".price").show();
        $(".hotel_class").hide();
        $(".other_cusine_type").show();
      }
      else if (optionType.includes("Bar"))
      {
          $(".cuisines").show();
        $(".dietary").show();
        $(".price").show();
        $(".hotel_class").hide();
        $(".other_cusine_type").show();
      }
      else if (optionType.includes("Hotel"))
      {
          $(".cuisines").hide();
        $(".dietary").hide();
        $(".price").hide();
        $(".hotel_class").show();
        $(".other_cusine_type").hide();
      }
      else {
          $(".cuisines").hide();
        $(".dietary").hide();
        $(".price").hide();
        $(".hotel_class").hide();
        $(".other_cusine_type").hide();
      }
  }

  </script>
@endsection
