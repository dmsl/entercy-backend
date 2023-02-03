@extends('layouts.app')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<a href="{{ URL::to('/') }}/pois/{{$storytelling->poi_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
 <h1><u>Edit POI Story Telling</u></h1>
<br>
  {!! Form::open(['action' => ['StorytellingsController@update', $storytelling->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

     <div class="form-group">
      {{Form::label('language', 'Language')}}
      <br>
      <select id="languageOption" onchange="val()" class="browser-default custom-select form-control" name="language" required >
        <option selected disabled >Please select Story Telling language</option>
         <option value="English">English</option>
         <option value="Greek">Greek</option>
         <option value="Russian">Russian</option>
         <option value="Italian">Italian</option>
         <option value="French">French</option>
         <option value="German">German</option>
      </select>
    </div>

    <div class="form-group">
      {{Form::label('duration', 'Duration')}}
      <br>
      <select id="durationOption" onchange="val2()" class="browser-default custom-select form-control" name="duration" required >
        <option selected disabled >Please select duration</option>
         <option value="Short">Short</option>
         <option value="Long">Long</option>
      </select>
    </div>

    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', $storytelling->name, ['class' => 'form-control', 'placeholder' => 'Name'] )}}
    </div>
    <div class="form-group ">
      {{Form::label('description', 'Description')}}
      {{Form::textarea('description', $storytelling->description, ['class' => 'form-control', 'placeholder' => 'Text'] )}}
    </div>

    <div class="form-group ">
      {{Form::label('path', 'Audio File to upload')}} <br>
      {{Form::file('path')}}
    </div>

    <div class="form-group ">
      {{Form::label('path_thumbnail', 'Thumbnail to upload')}} <br>
      {{Form::file('path_thumbnail')}}
    </div>
    <br>

    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}


<script>

  $( document ).ready(function() {

    document.getElementById('languageOption').value = '{{$storytelling->language}}'; //preselect language option
    document.getElementById('durationOption').value = '{{$storytelling->duration}}'; //preselect duration option

  });

</script>

@endsection
