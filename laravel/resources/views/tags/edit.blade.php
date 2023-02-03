@extends('layouts.app')

@section('content')
  <h1><u>Edit Tag</u></h1>

  {!! Form::open(['action' => ['TagsController@update', $tag->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    <div class="form-group">
      {{Form::label('tagcode', 'Tag Code:')}}
      {{Form::text('tagcode', $tag->tag_code, ['class' => 'form-control', 'placeholder' => 'Tag_Code'] )}}
    </div>

    <div class="form-group">
      {{Form::label('tagname', 'Tag Name:')}}
      {{Form::text('tagname', $tag->tag_name, ['class' => 'form-control', 'placeholder' => 'TagName'] )}}
    </div> 
 
    
    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}

@endsection
