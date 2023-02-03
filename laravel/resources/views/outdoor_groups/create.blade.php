@extends('layouts.app')

@section('content')

  <h1><u>Create Outdoor Group</u></h1>
<br>
  {!! Form::open(['action' => 'Outdoor_groupsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}


  <div class="form-group" hidden>
    {{Form::label('poiid', 'poiid')}}
    {{Form::text('poiid', $poiid , ['class' => 'form-control', 'placeholder' => '' ] )}}
  </div>
    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'] )}}
    </div>
    <div class="form-group">
        {{Form::label('latitude', 'Latitude')}}
        {{Form::number('latitude', '', ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter latitude Value'] )}}
    </div>
    <div class="form-group">
        {{Form::label('longitude', 'Longitude')}}
        {{Form::number('longitude', '', ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter longitude Value'] )}}
    </div>
    <div class="form-group">
        {{Form::label('altitude', 'Altitude')}}
        {{Form::number('altitude', '', ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter latitude Value'] )}}
    </div>


    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}



@endsection
