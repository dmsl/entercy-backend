<!--- layouts.app --->
@extends('layouts.appServices')


@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!---the below 2 are added cause of the searchable dropdown--->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <h1><u>Create POI Media</u></h1>
<br>
  {!! Form::open(['action' => 'PoimediasController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    <div class="form-group">
      {{Form::label('type', 'Type')}}
      <br>
      <select id="typeOption" onchange="val()" class="browser-default custom-select form-control" name="type" required >
        <option selected disabled >Please select media type</option>
        @foreach($pmtype as $pmtype)
          <option value="{{$pmtype->id}}">{{$pmtype->name}}</option>
        @endforeach
      </select>
    </div>
    
    <div class="form-group">
      {{Form::label('name', 'Description/Name')}}
      {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Description/Name'] )}}
    </div>
    <div class="form-group textURI">
      {{Form::label('uri', 'Link/Text')}}
      {{Form::textarea('uri', '', ['class' => 'form-control', 'placeholder' => 'Link/Text'] )}}
    </div>
    <div class="form-group" hidden>
      {{Form::label('poiid', 'Site ID')}}
      {{Form::text('poiid', $poiid , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>

    <div class="form-group fileURI">
      {{Form::label('uri', 'File to upload')}} <br>
      {{Form::file('uri')}}
    </div>

    <div class="form-group fileURI2">
      {{Form::label('uri2', 'File to upload (iPhone format only)')}} <br>
      {{Form::file('uri2')}}
    </div>

    <div class="form-group ">
      {{Form::label('path_thumbnail', 'Thumbnail to upload')}} <br>
      {{Form::file('path_thumbnail')}}
    </div>

    <div class="form-group">
      {{Form::label('artifact_poi_id', 'Please select where the artifact was originally found (if applicable)')}}
      <br>
      <select name="artifact_poi_id" id="artifact_poi_id" class="form-control select2" >
         <option selected disabled >Please select where the artifact was originally found</option>
          @foreach($pois as $pois)
                <option value="{{$pois->id}}">{{$pois->name}}</option>
          @endforeach     
      </select>
    </div>
    
    <hr>    

    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}


<script>

  //the below 3 lines are added cause of the searchabke dropdown
  $(document).ready(function() {
    $('.select2').select2();
  });

  $( document ).ready(function() {
    $(".textURI").hide();
    $(".fileURI").hide();
    $(".fileURI2").hide();
  });

  function val() {
    var e = document.getElementById("typeOption");
    var optionType = e.options[e.selectedIndex].text;
    //alert(optionType);
    if (optionType.includes("Tour")) {
      $(".textURI").hide();
      $(".fileURI").show();
      $(".fileURI2").hide();
      document.getElementById('name').value='';
      document.getElementById('name').readOnly = false;
      document.getElementById('uri').value='';
      document.getElementById('uri2').value='';
    }   
    else if (optionType.includes("POI Cover Image"))
    {
      $(".textURI").hide();
      $(".fileURI").show();
      $(".fileURI2").hide();
      document.getElementById('name').value=optionType;
      document.getElementById('name').readOnly = true;
      document.getElementById('uri').value='';
      document.getElementById('uri2').value='';
    }
    else if (optionType.includes("Text"))
    {
      $(".textURI").show();
      $(".fileURI").hide();
      $(".fileURI2").hide();
      document.getElementById('name').value=optionType;
      document.getElementById('name').readOnly  = true;
      document.getElementById('uri').value='';
      document.getElementById('uri2').value='';
    }
    else if (optionType.includes("3D Model Mobile (GTFL)"))
    {
      $(".textURI").hide();
      $(".fileURI").show();
      $(".fileURI2").show();
      document.getElementById('name').value='';
      document.getElementById('name').readOnly = false;
      document.getElementById('uri').value='';
      document.getElementById('uri2').value='';
    }
    else {
      $(".textURI").hide();
      $(".fileURI").show();
      $(".fileURI2").hide();
      document.getElementById('name').value='';
      document.getElementById('name').readOnly = false;
      document.getElementById('uri').value='';
      document.getElementById('uri2').value='';
    }


  }
</script>

@endsection
