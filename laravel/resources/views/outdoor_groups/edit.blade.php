@extends('layouts.app')
@section('content')
        <a href="{{ URL::to('/') }}/pois/{{ $outdoor_groups->poi_id }}" class="btn btn-primary">Go Back</a>
        <br><br><br>
        <h1><u>Edit Outdoor Groups</u></h1>
        <br>
        {!! Form::open(['action' => ['Outdoor_groupsController@update', $outdoor_groups->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}


        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', $outdoor_groups->name, ['class' => 'form-control', 'placeholder' => 'Name']) }}
        </div>
        <div class="form-group">
            {{ Form::label('latitude', 'Latitude') }}
            {{ Form::number('latitude', $outdoor_groups->latitude, ['class' => 'form-control', 'step' => 'any', 'placeholder' => 'Enter latitude Value']) }}
        </div>
        <div class="form-group">
            {{ Form::label('longitude', 'Longitude') }}
            {{ Form::number('longitude', $outdoor_groups->longitude, ['class' => 'form-control', 'step' => 'any', 'placeholder' => 'Enter longitude Value']) }}
        </div>
        <div class="form-group">
            {{ Form::label('altitude', 'Altitude') }}
            {{ Form::number('altitude', $outdoor_groups->altitude, ['class' => 'form-control', 'step' => 'any', 'placeholder' => 'Enter latitude Value']) }}
        </div>
        <div class="form-group" hidden>
            {{ Form::label('poiid', 'poiid') }}
            {{ Form::text('poiid', $outdoor_groups->poi_id, ['class' => 'form-control', 'placeholder' => '']) }}
        </div>
        <br>

        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
        {!! Form::close() !!}



@endsection
