@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/pois/{{$beacon->poi_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Show Qrrooms</u></h1>
<br>

<div class="form-group">
    {{Form::label('name', 'Name')}}
    {{Form::text('name', $beacon->name, ['class' => 'form-control', 'disabled' => 'Name'] )}}
  </div>
  <div class="form-group ">
      {{Form::label('beacon_id', 'Beacon Id')}}
    {{Form::textarea('beacon_id', $beacon->beacon_id, ['class' => 'form-control', 'disabled' => 'Text'] )}}
  </div>
  <div class="form-group" hidden>
      {{Form::label('poiid', 'poiid')}}
      {{Form::text('poiid', $beacon->poi_id , ['class' => 'form-control', 'disabled' => '' ] )}}
    </div>
    <div class="form-group">
        <label>Beacon File</label>
        <input class="form-control" disabled value="{{$beacon->img_path}}">
        <br>
        @if ($beacon->img_path != '')
        <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$beacon->img_path}}">
        @endif
     </div>
  <br>
  <hr>
  <small>Created on {{$beacon->created_at}} </small>
  <hr>

      <a href="{{ URL::to('/') }}/beacons/{{$beacon->id}}/edit" class="btn btn-warning">Edit Beacon</a>

      <div class="float-right">
        {!!Form::open(['action' => ['BeaconsController@destroy', $beacon->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete Beacon', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>
@endsection
