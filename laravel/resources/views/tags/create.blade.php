@extends('layouts.app')

@section('content')

  <h1><u>Create Tag</u></h1>
<br>
  {!! Form::open(['action' => 'TagsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

  
    <div class="form-group">
      {{Form::label('tagcode', 'Tag Code:')}}
      {{Form::text('tagcode', '', ['class' => 'form-control', 'placeholder' => 'Tag_Code'] )}}
    </div>

    <div class="form-group">
      {{Form::label('tagname', 'Tag Name:')}}
      {{Form::text('tagname', '', ['class' => 'form-control', 'placeholder' => 'TagName'] )}}
    </div> 

    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}


@endsection
