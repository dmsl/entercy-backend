@extends('layouts.appServices')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <!---<img style="width:100%" src="https://www.imhbusiness.com/media/zoo/images/1920X550_2a6a99f26408661fd20e8e1a8d4d3b2e.jpg">--->
  <img style="width:100%;" src="{{ URL::to('/') }}/storage/media/ServicesForm/header.jpg">
  <br><br>
  <h4><u>Include your Services on the EnterCY platform</u></h4>

  <p>The EnterCY project is developing an online platform and smartphone application, through which tourists will be informed about the rich cultural heritage of Cyprus, as well as being provided with recommendations on a variety of activities, sightseeing and services based on their location.</p>

  <p>If you would like your establishment to be included in the list of suggested services on both the EnterCY platform and related application, please complete the form to express your interest. Note that you need to submit a copy of a valid Catering and Entertainment Establishments license, which should be uploaded on the field provided in the form. Information about your business’s services will be displayed on user request or through automatic recommendations according to the user’s preferences.</p>

  <p>For more information about the EnterCY project and platform, please follow this <a href="https://www.entercyprus.com/" target="_blank">link</a>.</p>

  <p>* Required fields.</p>

  {!! Form::open(['action' => 'ServicesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

  	<hr><br>
  	<h4><u>Contact Information</u></h4>
    <div class="form-group">
      {{Form::label('name', 'Name of establishment (English) *')}}
      {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name of establishment'] )}}
    </div>

    <div class="form-group">
      {{Form::label('email', 'E-mail Address *')}}
      {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'E-mail Address'] )}}
    </div>

    <div class="form-group">
      {{Form::label('telephone', 'Phone Number *')}}
      {{Form::text('telephone', '', ['class' => 'form-control', 'placeholder' => 'Phone Number'] )}}
    </div>

    <div>    
    {{Form::label('address', 'Address ( Please press the button to check address/coordinates online ) *')}}           
      <div class="row">
          <div class="col-md-9">            
            {{Form::text('address', '', ['class' => 'form-control', 'placeholder' => 'Address'] )}}
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
            {{Form::text('coord_lat', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('coord_long', 'Longitude:')}}
            {{Form::text('coord_long', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
      </div>
    </div>


    <hr><br>
  	<h4><u>General Information</u></h4>

    <div class="form-group licenseANDlogo ">
      {{Form::label('licenseANDlogo', "Please attach a copy of the establishment's licence and logo")}}
      <div class="row">
          <div class="col-md">
              {{Form::label('license', 'Please select a copy of the license: *')}}
		      <br>
		      {{Form::file('license')}}
		      <br><br>
          </div>
          <div class="col-md">
              {{Form::label('logo', 'Please select a logo:')}}
		      <br>
		      {{Form::file('logo')}}
          </div>
          <div class="col-md">
          </div>
      </div>
    </div>

    <div class="form-group imgs ">
      {{Form::label('imgs', "Please attach the images that you would like to appear along with the establishment's information")}}
      <div class="row">
          <div class="col-md">
              {{Form::label('image_1', 'Please select an image:')}}
		      <br>
		      {{Form::file('image_1')}}
		      <br>
          </div>
          <div class="col-md">
              {{Form::label('image_2', 'Please select a second image:')}}
		      <br>
		      {{Form::file('image_2')}}
		      <br>
          </div>
          <div class="col-md">
              {{Form::label('image_3', 'Please select a third image:')}}
		      <br>
		      {{Form::file('image_3')}}
		      <br><br>
          </div>
      </div>
    </div>

     <div class="form-group">
      {{Form::label('description', 'Description (English)')}}
      {{Form::textarea('description', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in English'] )}}
    </div>
 
    <div class="form-group">
      {{Form::label('website', 'Website')}}
      {{Form::text('website', '', ['class' => 'form-control', 'placeholder' => 'Website'] )}}
    </div>

    <div class="form-group">
      {{Form::label('servicecategory', 'Service Category *')}}
      <br>
      <select id="servicecategoryOption" onchange="val2()" class="browser-default custom-select form-control" name="service_category" required >
        <option selected disabled >Please select service category</option>
        @foreach($servicecategories as $servicecategories)
          <option value="{{$servicecategories->id}}">{{$servicecategories->name}}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      {{Form::label('other_servicecategory', 'If "Other" Service Category selected, please specify:')}}
      {{Form::text('other_servicecategory', '', ['class' => 'form-control', 'placeholder' => 'If "Other" Service Category selected, please specify'] )}}
    </div>

    <div class="form-group">
      {{Form::label('premises', 'Premises')}}
      <br>
      <select id="premisesOption" onchange="val3()" class="browser-default custom-select form-control" name="premisesType" >
        <option selected disabled >Please select premises</option>        
          <option value="Outdoor">Outdoor</option>
          <option value="Indoor">Indoor</option>
          <option value="Both">Both</option>
      </select>
    </div>

    <div class="form-group time ">
      {{Form::label('time', 'Hours of operation')}}
      <div class="row">
          <div class="col-md">
            {{Form::label('monday_start', 'Monday open time:')}}
            {{Form::time('monday_start', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('monday_end', 'Monday close time:')}}
            {{Form::time('monday_end', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>          
      </div><br>
      <div class="row">
          <div class="col-md">
            {{Form::label('tuesday_start', 'Tuesday open time:')}}
            {{Form::time('tuesday_start', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('tuesday_end', 'Tuesday close time:')}}
            {{Form::time('tuesday_end', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
      </div><br>
      <div class="row">
          <div class="col-md">
            {{Form::label('wednesday_start', 'Wednesday open time:')}}
            {{Form::time('wednesday_start', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('wednesday_end', 'Wednesday close time:')}}
            {{Form::time('wednesday_end', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
    </div><br>
    <div class="row">
          <div class="col-md">
            {{Form::label('thursday_start', 'Thursday open time:')}}
            {{Form::time('thursday_start', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('thurday_end', 'Thursday close time:')}}
            {{Form::time('thursday_end', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
      </div><br>
    <div class="row">
          <div class="col-md">
            {{Form::label('friday_start', 'Friday open time:')}}
            {{Form::time('friday_start', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('friday_end', 'Friday close time:')}}
            {{Form::time('friday_end', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
      </div><br>
    <div class="row">
          <div class="col-md">
            {{Form::label('saturday_start', 'Saturday open time:')}}
            {{Form::time('saturday_start', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('saturday_end', 'Saturday close time:')}}
            {{Form::time('saturday_end', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
      </div><br>
    <div class="row">
          <div class="col-md">
            {{Form::label('sunday_start', 'Sunday open time:')}}
            {{Form::time('sunday_start', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('sunday_end', 'Sunday close time:')}}
            {{Form::time('sunday_end', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
      </div>
    </div>

    <br>
    <div class="form-group coords ">
      {{Form::label('monthsclosed', 'Please specify the period of time for which the establishment is out of operation (if applicable)')}}
      <div class="row">
          <div class="col-md">
            {{Form::label('monthclosed_from', 'From month:')}}
            <select id="monthclosed_from" name="monthclosed_from" class="browser-default custom-select form-control">
            	<option selected disabled >Please select month</option>
			    <option value="January">January</option>
			    <option value="February">February</option>
			    <option value="March">March</option>
			    <option value="April">April</option>
			    <option value="May">May</option>
			    <option value="June">June</option>
			    <option value="July">July</option>
			    <option value="August">August</option>
			    <option value="September">September</option>
			    <option value="October">October</option>
			    <option value="November">November</option>
			    <option value="December">December</option>
			</select>
          </div>
          <div class="col-md">
            {{Form::label('monthclosed_to', 'To month:')}}
            <select id="monthclosed_to" name="monthclosed_to" class="browser-default custom-select form-control">
            	<option selected disabled >Please select month</option>
			    <option value="January">January</option>
			    <option value="February">February</option>
			    <option value="March">March</option>
			    <option value="April">April</option>
			    <option value="May">May</option>
			    <option value="June">June</option>
			    <option value="July">July</option>
			    <option value="August">August</option>
			    <option value="September">September</option>
			    <option value="October">October</option>
			    <option value="November">November</option>
			    <option value="December">December</option>
			</select>
          </div>
      </div>
    </div>

    
    <div class="form-group cuisines ">
    	<br>
      {{Form::label('cuisines', 'Types of cuisine')}}
      <div class="row">
          <div class="col-md">
              {{Form::label('cuisine_type1', 'Please select cuisine type 1:')}}
		      <br>
		      <select id="cuisine_type1" name="cuisine_type1" class="browser-default custom-select form-control">
            	<option selected disabled >Please select cuisine </option>
            	<option value="Traditional">Traditional</option>
			    <option value="Greek">Greek</option>
			    <option value="Chinese">Chinese</option>
			    <option value="Japanese">Japanese</option>
			    <option value="Sushi">Sushi</option>
			    <option value="Lebanese">Lebanese</option>
			    <option value="Thai">Thai</option>
			    <option value="Indian">Indian</option>
			    <option value="Asian">Asian</option>
			    <option value="American">American</option>
			    <option value="Italian">Italian</option>
			    <option value="Pizza">Pizza</option>
			    <option value="Mexican">Mexican</option>
			    <option value="International">International</option>
			    <option value="Grill">Grill</option>
			    <option value="Seafood">Seafood</option>
			    <option value="Steakhouse">Steakhouse</option>
			    <option value="Other">Other</option>
			  </select>
		      <br>
          </div>
          <div class="col-md">
              {{Form::label('cuisine_type2', 'Please select cuisine type 2:')}}
		      <br>
		      <select id="cuisine_type2" name="cuisine_type2" class="browser-default custom-select form-control">
            	<option selected disabled >Please select cuisine </option>
            	<option value="Traditional">Traditional</option>
			    <option value="Greek">Greek</option>
			    <option value="Chinese">Chinese</option>
			    <option value="Japanese">Japanese</option>
			    <option value="Sushi">Sushi</option>
			    <option value="Lebanese">Lebanese</option>
			    <option value="Thai">Thai</option>
			    <option value="Indian">Indian</option>
			    <option value="Asian">Asian</option>
			    <option value="American">American</option>
			    <option value="Italian">Italian</option>
			    <option value="Pizza">Pizza</option>
			    <option value="Mexican">Mexican</option>
			    <option value="International">International</option>
			    <option value="Grill">Grill</option>
			    <option value="Seafood">Seafood</option>
			    <option value="Steakhouse">Steakhouse</option>
			    <option value="Other">Other</option>
			  </select>
		      <br>
          </div>
          <div class="col-md">
              {{Form::label('cuisine_type3', 'Please select cuisine type 3:')}}
		      <br>
		      <select id="cuisine_type3" name="cuisine_type3" class="browser-default custom-select form-control">
            	<option selected disabled >Please select cuisine </option>
            	<option value="Traditional">Traditional</option>
			    <option value="Greek">Greek</option>
			    <option value="Chinese">Chinese</option>
			    <option value="Japanese">Japanese</option>
			    <option value="Sushi">Sushi</option>
			    <option value="Lebanese">Lebanese</option>
			    <option value="Thai">Thai</option>
			    <option value="Indian">Indian</option>
			    <option value="Asian">Asian</option>
			    <option value="American">American</option>
			    <option value="Italian">Italian</option>
			    <option value="Pizza">Pizza</option>
			    <option value="Mexican">Mexican</option>
			    <option value="International">International</option>
			    <option value="Grill">Grill</option>
			    <option value="Seafood">Seafood</option>
			    <option value="Steakhouse">Steakhouse</option>
			    <option value="Other">Other</option>
			  </select>
		      <br>
          </div>
          <div class="col-md">
              {{Form::label('cuisine_type4', 'Please select cuisine type 4:')}}
		      <br>
		      <select id="cuisine_type4" name="cuisine_type4" class="browser-default custom-select form-control">
            	<option selected disabled >Please select cuisine </option>
            	<option value="Traditional">Traditional</option>
			    <option value="Greek">Greek</option>
			    <option value="Chinese">Chinese</option>
			    <option value="Japanese">Japanese</option>
			    <option value="Sushi">Sushi</option>
			    <option value="Lebanese">Lebanese</option>
			    <option value="Thai">Thai</option>
			    <option value="Indian">Indian</option>
			    <option value="Asian">Asian</option>
			    <option value="American">American</option>
			    <option value="Italian">Italian</option>
			    <option value="Pizza">Pizza</option>
			    <option value="Mexican">Mexican</option>
			    <option value="International">International</option>
			    <option value="Grill">Grill</option>
			    <option value="Seafood">Seafood</option>
			    <option value="Steakhouse">Steakhouse</option>
			    <option value="Other">Other</option>
			  </select>
		      <br>
          </div>
          <div class="col-md">
              {{Form::label('cuisine_type5', 'Please select cuisine type 5:')}}
		      <br>
		      <select id="cuisine_type5" name="cuisine_type5" class="browser-default custom-select form-control">
            	<option selected disabled >Please select cuisine </option>
            	<option value="Traditional">Traditional</option>
			    <option value="Greek">Greek</option>
			    <option value="Chinese">Chinese</option>
			    <option value="Japanese">Japanese</option>
			    <option value="Sushi">Sushi</option>
			    <option value="Lebanese">Lebanese</option>
			    <option value="Thai">Thai</option>
			    <option value="Indian">Indian</option>
			    <option value="Asian">Asian</option>
			    <option value="American">American</option>
			    <option value="Italian">Italian</option>
			    <option value="Pizza">Pizza</option>
			    <option value="Mexican">Mexican</option>
			    <option value="International">International</option>
			    <option value="Grill">Grill</option>
			    <option value="Seafood">Seafood</option>
			    <option value="Steakhouse">Steakhouse</option>
			    <option value="Other">Other</option>
			  </select>
		      <br>
          </div>
      </div>
    </div>

    <div class="form-group other_cusine_type">
      {{Form::label('other_cusine_type', 'If "Other" cuisine type selected, please specify:')}}
      {{Form::text('other_cusine_type', '', ['class' => 'form-control', 'placeholder' => 'If "Other" cuisine type selected, please specify'] )}}
    </div>
    
	
	
    <div class="form-group dietary ">
    	<br>
      {{Form::label('dietary', 'Dietary restrictions')}}
      <div class="row">
          <div class="col-md">
              {{Form::label('dietary_restr1', 'Please select dietary restriction 1:')}}
		      <br>
		      <select id="dietary_restr1" name="dietary_restr1" class="browser-default custom-select form-control">
            	<option selected disabled >Please select dietary </option>
			    <option value="Vegetarian friendly">Vegetarian friendly</option>
			    <option value="Vegan options">Vegan options</option>
			    <option value="Gluten free options">Gluten free options</option>
			  </select>
		      <br>
          </div>
          <div class="col-md">
               {{Form::label('dietary_restr2', 'Please select dietary restriction 2:')}}
		      <br>
		      <select id="dietary_restr2" name="dietary_restr2" class="browser-default custom-select form-control">
            	<option selected disabled >Please select dietary </option>
			    <option value="Vegetarian friendly">Vegetarian friendly</option>
			    <option value="Vegan options">Vegan options</option>
			    <option value="Gluten free options">Gluten free options</option>
			  </select>
		      <br>
          </div>
          <div class="col-md">
               {{Form::label('dietary_restr3', 'Please select dietary restriction 3:')}}
		      <br>
		      <select id="dietary_restr3" name="dietary_restr3" class="browser-default custom-select form-control">
            	<option selected disabled >Please select dietary </option>
			    <option value="Vegetarian friendly">Vegetarian friendly</option>
			    <option value="Vegan options">Vegan options</option>
			    <option value="Gluten free options">Gluten free options</option>
			  </select>
		      <br>
          </div>
      </div>
    </div>   


	
    <div class="form-group price ">      
    <br>
      <div class="row">
          <div class="col-md">
              {{Form::label('price', 'Please select price range')}}
		      <br>
		      <select id="price" name="price" class="browser-default custom-select form-control">
            	<option selected disabled >Please select price range </option>
			    <option value="Cheap eats (quick serve or self service">Cheap eats (quick serve or self service)</option>
			    <option value="Mid-range (casual, table service)">Mid-range (casual, table service)</option>
			    <option value="Fine dining (more formal or dressy)">Fine dining (more formal or dressy)</option>
			  </select>
		      <br>
          </div>          
      </div>
    </div>  


    
    <div class="form-group hotel_class ">    
    <br>  
      <div class="row">
          <div class="col-md">
              {{Form::label('hotel_class', 'Please select hotel class')}}
		      <br>
		      <select id="hotel_class" name="hotel_class" class="browser-default custom-select form-control">
            	<option selected disabled >Please select hotel class </option>
			    <option value="2 stars">2 stars</option>
			    <option value="3 stars">3 stars</option>
			    <option value="4 stars">4 stars</option>
			    <option value="5 stars">5 stars</option>
			  </select>
		      <br>
          </div>          
      </div>
    </div>   


    <br><hr>
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}
<hr><br>

<img style="width:100%;" src="{{ URL::to('/') }}/storage/media/ServicesForm/footer.jpg">


<script>

	$( document ).ready(function() {
    $(".cuisines").hide();
    $(".dietary").hide();
    $(".price").hide();
    $(".hotel_class").hide();
    $(".other_cusine_type").hide();
  	});
  
  function val() {    
    var e = document.getElementById("address").value; 
    if (e === "") 
    {
      alert("Please enter in the text box a location name, an address or the coordinates of the place you want to search for.");
    }
    else 
    {
      window.open("https://www.google.com/maps/place/+"+e, '_blank');
    }
  };

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
