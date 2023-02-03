@extends('layouts.app')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <h1><u>Create POI</u></h1>

  {!! Form::open(['action' => 'PoisController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
      {{Form::label('name', 'Name in English')}}
      {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name in English'] )}}
    </div>
     <div class="form-group">
      {{Form::label('name_gr', 'Name in Greek')}}
      {{Form::text('name_gr','' , ['class' => 'form-control', 'placeholder' => 'Name in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ru', 'Name in Russian')}}
      {{Form::text('name_ru','', ['class' => 'form-control', 'placeholder' => 'Name in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_it', 'Name in Italian')}}
      {{Form::text('name_it', '', ['class' => 'form-control', 'placeholder' => 'Name in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_fr', 'Name in French')}}
      {{Form::text('name_fr', '', ['class' => 'form-control', 'placeholder' => 'Name in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Name in German')}}
      {{Form::text('name_ge', '', ['class' => 'form-control', 'placeholder' => 'Name in German'] )}}
    </div>


    <div class="form-group">
      {{Form::label('description', 'Description in English')}}
      {{Form::textarea('description', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description', 'Description in Greek')}}
      {{Form::textarea('description_gr', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description', 'Description in Russian')}}
      {{Form::textarea('description_ru', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description', 'Description in Italian')}}
      {{Form::textarea('description_it', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description', 'Description in French')}}
      {{Form::textarea('description_fr', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description', 'Description in German')}}
      {{Form::textarea('description_ge', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in German'] )}}
    </div>

    <div class="form-group">
        {{Form::label('choose Indoor or Outdoor', 'Indoor or Outdoor')}}
        <br>
        <select id="indoor_outdoor" onchange="val3()" class="browser-default custom-select form-control" aria-label="Default select example" name="indoor_outdoor">
         <option selected disabled >Please select  Indoor or Outdoor</option>
         <option value="Indoor">Indoor</option>
         <option value="Outdoor">Outdoor</option>
       </select>
     </div>
     <div class="form-group indoor_type_class">
      {{Form::label('indoor_type', 'Choose Indoor Type')}}
       <select id="indoor_type"  class="browser-default custom-select form-control" aria-label="Default select example" name="indoor_type">
        <option id="value" selected disabled >Please select Indoor Type</option>
         <option value="QR codes">QR codes</option>
         <option value="Beacons">Beacons</option>
         <option value="Finger Print">Finger Print</option>
       </select>
     </div>
    <div class="form-group">
      {{Form::label('chronological', 'Chronological')}}
      <br>
      <select id="typeOption" onchange="val2()" class="browser-default custom-select form-control" name="chronological" >
        <option selected disabled >Please select Chronological</option>
        @foreach($chronologicals as $chronological)
          @if ($chronological->parent_id == 0)
            <option value="{{$chronological->id}}">{{$chronological->name}}</option>
          @endif
        @endforeach
      </select>
    </div>

    <div class="form-group sub_chron">
      {{Form::label('sub_chronological', 'Sub-Chronological')}}
      <br>
      <select id="typeOption2" class="browser-default custom-select form-control" name="sub_chronological" >

      </select>
    </div>

    <div class="form-group">
      {{Form::label('year', 'Year')}}
      {{Form::text('year', '', ['class' => 'form-control', 'placeholder' => 'Year'] )}}
    </div>

    <div>
    {{Form::label('coordinates', 'Coordinates ( The format should be like this: Latitude, Longitude - Example: 34.7967, 33.3436 )')}}
      <div class="row">
          <div class="col-md-9">
            {{Form::text('coordinates', '', ['class' => 'form-control', 'placeholder' => 'Coordinates'] )}}
          </div>
          <div class="col-md-3">
           <!---<a add target="_blank" onclick="this.href='https://www.google.com/search?q=coordinates+'+document.getElementById('coordinates').value" class="btn btn-info" role="button">Check Coordinates Online</a>  --->
           <a onclick="val()" class="btn btn-info" role="button">Check Coordinates Online</a>
          </div>
      </div>
    </div>
    <br>

    <div class="form-group">
      {{Form::label('toponym', 'Toponym in English')}}
      {{Form::text('toponym', '', ['class' => 'form-control', 'placeholder' => 'Toponym in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('toponym_gr', 'Toponym in Greek')}}
      {{Form::text('toponym_gr', '', ['class' => 'form-control', 'placeholder' => 'Toponym in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('toponym_ru', 'Toponym in Russian')}}
      {{Form::text('toponym_ru', '', ['class' => 'form-control', 'placeholder' => 'Toponym in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('toponym_it', 'Toponym in Italian')}}
      {{Form::text('toponym_it', '', ['class' => 'form-control', 'placeholder' => 'Toponym in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('toponym_fr', 'Toponym in French')}}
      {{Form::text('toponym_fr', '', ['class' => 'form-control', 'placeholder' => 'Toponym in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('toponym_ge', 'Toponym in German')}}
      {{Form::text('toponym_ge', '', ['class' => 'form-control', 'placeholder' => 'Toponym in German'] )}}
    </div>

    <div class="form-group">
        {{Form::label('publicly_available', 'Publicly available')}}
        <br>
        <select id="publicly_available" class="browser-default custom-select form-control" name="publicly_available">
         <option selected disabled>Please select if publicly available</option>
         <option value="Yes">Yes</option>
         <option value="No">No</option>
       </select>
     </div>

    <hr>

    <div class="form-group" hidden>
      {{Form::label('siteid', 'Site ID')}}
      {{Form::text('siteid', $siteid , ['class' => 'form-control', 'placeholder' => '' ] )}}
      <!--- {{$siteid}} <?php echo $siteid; ?> --->
    </div>
    <div class="form-group" hidden>
      {{Form::label('parentpoi', 'parentpoi')}}
      {{Form::text('parentpoi', '' , ['class' => 'form-control', 'placeholder' => '' ] )}}
      <!--- {{$siteid}} <?php echo $siteid; ?> --->
    </div>
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}


<script>

  function val() {

    var e = document.getElementById("coordinates").value;
    if (e === "")
    {
      alert("Please enter in the text box a location name or the coordinates of the place you want to search.");
    }
    else
    {
      window.open("https://www.google.com/maps/place/+"+e, '_blank');
    }
  }

  $( document ).ready(function() {
    $(".sub_chron").hide();
    $(".indoor_type_class").hide();
  });

  function val2() {
    $("#typeOption2").empty();
    var e = document.getElementById("typeOption");
    var optionType = e.options[e.selectedIndex].text;
    var chronPerID = document.getElementById("typeOption").value;

    if (optionType!="") {
      $(".sub_chron").show();

      var chrons = @json($chronologicals);
      //alert(chrons.length);
      for (i = 0; i < chrons.length; i++) {
        if (i==0)
        {
          document.getElementById("typeOption2").innerHTML += '<option selected disabled >Please select Sub-Chronological</option>';
        }
        if (chronPerID == chrons[i].parent_id)
        {
          document.getElementById("typeOption2").innerHTML += '<option value="' + chrons[i].id + '">' + chrons[i].name + '</option>';
        }
      }
    }
  }

  function val3(){
    var e = document.getElementById("indoor_outdoor");
    var strUser = e.options[e.selectedIndex].text;
    if(strUser=="Indoor"){
        $(".indoor_type_class").show();
        document.getElementById("value").selected = "true";
    }
    else {
        $(".indoor_type_class").hide();
    }
  }

</script>

@endsection
