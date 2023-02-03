@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/pois/{{$beacon->poi_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Edit Beacons</u></h1>
<br>
  {!! Form::open(['action' => ['BeaconsController@update', $beacon->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}


    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', $beacon->name, ['class' => 'form-control', 'placeholder' => 'Name'] )}}
    </div>
    <div class="form-group ">
        {{Form::label('beacon_id', 'Beacon Id')}}
      {{Form::textarea('beacon_id', $beacon->beacon_id, ['class' => 'form-control', 'placeholder' => 'Text'] )}}
    </div>
    <div class="form-group" hidden>
        {{Form::label('poiid', 'poiid')}}
        {{Form::text('poiid', $beacon->poi_id , ['class' => 'form-control', 'placeholder' => '' ] )}}
      </div>
    <br>
    <div class="form-group ">
        {{Form::label('path', 'Audio File to upload')}} <br>
        {{Form::file('path')}}
      </div>
    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}



@endsection
