@extends('layouts.app')

@section('content')

  <h1><u>Add Accessibility Type</u></h1>
<br>
  {!! Form::open(['action' => 'SiteaccessibilitiesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    <div class="form-group">
      {{Form::label('type', 'Type')}}
      <br>
      <select id="accessibilityOption" class="browser-default custom-select form-control" name="accessibility" required >
        <option selected disabled >Please select accessibility type</option>
        @foreach($accessibility as $accessibility)
          <option value="{{$accessibility->id}}">{{$accessibility->name}}</option>
        @endforeach
      </select>
    </div>
    
    <div class="form-group" hidden>
      {{Form::label('siteid', 'Site ID')}}
      {{Form::text('siteid', $siteid , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>

    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}


@endsection
