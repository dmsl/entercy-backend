@extends('layouts.app')

@section('content')

  <h1><u>Create Beacons</u></h1>
<br>
  {!! Form::open(['action' => 'BeaconsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}


    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'] )}}
    </div>
    <div class="form-group ">
        {{Form::label('beacon_id', 'Beacon Id')}}
        {{Form::textarea('beacon_id', '', ['class' => 'form-control', 'placeholder' => 'Text'] )}}
      </div>
    <div class="form-group" hidden>
      {{Form::label('poiid', 'poiid')}}
      {{Form::text('poiid', $poiid , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>

    <div class="form-group ">
      {{Form::label('path', 'Audio File to upload')}} <br>
      {{Form::file('path')}}
    </div>

    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}



@endsection
