<!--- layouts.app --->
@extends('layouts.appServices')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!---the below 2 are added cause of the searchable dropdown--->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<a href="{{ URL::to('/') }}/pois/{{$poimedia->poi_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Edit POI Media</u></h1>
<br>
  {!! Form::open(['action' => ['PoimediasController@update', $poimedia->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    <div class="form-group">
      {{Form::label('type', 'Type')}}
      <br>
      <select id="typeOption" onchange="val()" class="browser-default custom-select form-control" name="type" required >
        <option disabled >Please select media type</option>
        @foreach($pmtype as $pmtype)
          <option value="{{$pmtype->id}}">{{$pmtype->name}}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      {{Form::label('name', 'Description/Name')}}
      {{Form::text('name', $poimedia->name , ['class' => 'form-control', 'placeholder' => 'Description/Name'] )}}
    </div>

    <div class="form-group textURI">
      {{Form::label('uri', 'Text')}}
      @if (strpos($pmtype_current->name, 'Text') !== false)
        {{Form::textarea('uri', $poimedia->uri, ['class' => 'form-control', 'placeholder' => 'Text'] )}}
      @else
        {{Form::textarea('uri', '' , ['class' => 'form-control', 'placeholder' => 'Text'] )}}
      @endif
    </div>

    <div class="form-group fileURI">
      {{Form::label('uri', 'File to upload')}} <br>
      @if (strpos($pmtype_current->name, 'Text') !== false)
      @else
        <input class="form-control" disabled value="{{$poimedia->uri}}"> <br>
      @endif
      {{Form::file('uri')}}
    </div>

    <div class="form-group fileURI2">
      {{Form::label('uri2', 'File to upload (iPhone format only)')}} <br>
      @if (strpos($pmtype->name, '3D Model Mobile (GTFL)') !== false)
        <input class="form-control" disabled value="{{$poimedia->uri2}}"> <br>
      @endif
      {{Form::file('uri2')}}
    </div>

    <div class="form-group">
      {{Form::label('path_thumbnail', 'Thumbnail to upload')}} <br>
      <input class="form-control" disabled value="{{$poimedia->path_thumbnail}}"> <br>
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

    <br>

    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}


<script>
  
  //preselect the artifact POI
  $( document ).ready(function() {
      document.getElementById('artifact_poi_id').value = '{{$poimedia->artifact_poi_id}}'; //preselect type option
    });

  //the below 3 lines are added cause of the searchabke dropdown
  $(document).ready(function() {
    $('.select2').select2();
  });

  $( document ).ready(function() {
    //alert("{{$poimedia->name}}");
    document.getElementById('typeOption').value = '{{$pmtype_current->id}}'; //preselect type option
    var optionType="{{$pmtype_current->name}}";
    if (optionType.includes("Tour")) {
      $(".textURI").hide();
      $(".fileURI").show();
      $(".fileURI2").hide();
      document.getElementById('name').readOnly = false;
    }
    else if (optionType.includes("POI Cover Image"))
    {
      $(".textURI").hide();
      $(".fileURI").show();
      $(".fileURI2").hide();
      document.getElementById('name').readOnly = true;
    }
    else if (optionType.includes("Text"))
    {
      $(".textURI").show();
      $(".fileURI").hide();
      $(".fileURI2").hide();
      document.getElementById('name').readOnly  = true;
    }
    else if (optionType.includes("3D Model Mobile (GTFL)"))
    {
      $(".textURI").hide();
      $(".fileURI").show();
      $(".fileURI2").show();
      document.getElementById('name').readOnly = false;
    }
    else {
      $(".textURI").hide();
      $(".fileURI").show();
      $(".fileURI2").hide();
      document.getElementById('name').readOnly = false;
    }

  });

  function val() {
    var e = document.getElementById("typeOption");
    var optionType = e.options[e.selectedIndex].text;
    //alert(optionType);    
    if (optionType.includes("Tour")) {
      $(".textURI").show();
      $(".fileURI").hide();
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
