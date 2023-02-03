@extends('layouts.app')

@section('content')

   <a href="{{ url()->previous() }}" class="btn btn-primary">Back to Districts</a>
   <br><br><br>

  <h1><u>Edit District: {{$cydistricts->name}} </u></h1>

  {!! Form::open(['action' => ['Cy_districtsController@update', $cydistricts->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

  @if ($cydistricts->path != '')
  <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$cydistricts->path}}">
  @endif
  <br>

   <div class="form-group">
      <br>
      {{Form::label('path', 'Please select an image:')}}
      <br>
      {{Form::file('path')}}      
   </div>

   <hr>

    @if ($cydistricts->path_thumbnail != '')
  <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$cydistricts->path_thumbnail}}">
  @endif
  <br>

   <div class="form-group">
      <br>
      {{Form::label('path_thumbnail', 'Please select an thumbnail:')}}
      <br>
      {{Form::file('path_thumbnail')}}      
   </div>

   <hr>

   @if ($cydistricts->path_video != '')
    <video width="100%" controls>
    <source src="{{ URL::to('/') }}/storage/media/{{$cydistricts->path_video}}">
    Your browser does not support the video tag.
    </video>
   @endif

   <div class="form-group">
      <br>
      {{Form::label('path_video', 'Please select a video:')}}
      <br>
      {{Form::file('path_video')}}      
   </div>

    <hr><br>

    <div class="form-group">
      {{Form::label('name_gr', 'Name in Greek')}}
      {{Form::text('name_gr', $cydistricts->name_gr, ['class' => 'form-control', 'placeholder' => 'Name in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ru', 'Name in Russian')}}
      {{Form::text('name_ru', $cydistricts->name_ru, ['class' => 'form-control', 'placeholder' => 'Name in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_it', 'Name in Italian')}}
      {{Form::text('name_it', $cydistricts->name_it, ['class' => 'form-control', 'placeholder' => 'Name in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_fr', 'Name in French')}}
      {{Form::text('name_fr', $cydistricts->name_fr, ['class' => 'form-control', 'placeholder' => 'Name in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Name in German')}}
      {{Form::text('name_ge', $cydistricts->name_ge, ['class' => 'form-control', 'placeholder' => 'Name in German'] )}}
    </div>

     <hr>
     <br>

     <div class="form-group">
      {{Form::label('description', 'Description in English')}}
      {{Form::textarea('description', $cydistricts->description, ['class' => 'form-control', 'placeholder' => 'Description  in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_gr', 'Description in Greek')}}
      {{Form::textarea('description_gr', $cydistricts->description_gr, ['class' => 'form-control', 'placeholder' => 'Description in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_ru', 'Description in Russian')}}
      {{Form::textarea('description_ru', $cydistricts->description_ru, ['class' => 'form-control', 'placeholder' => 'Description in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_it', 'Description in Italian')}}
      {{Form::textarea('description_it', $cydistricts->description_it, ['class' => 'form-control', 'placeholder' => 'Description in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_fr', 'Description in French')}}
      {{Form::textarea('description_fr', $cydistricts->description_fr, ['class' => 'form-control', 'placeholder' => 'Description in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_ge', 'Description in German')}}
      {{Form::textarea('description_ge', $cydistricts->description_ge, ['class' => 'form-control', 'placeholder' => 'Description in German'] )}}
    </div>
 
    <hr>
    
    <div class="float-left">
        {{Form::hidden('_method', 'PUT')}}
    	{{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  		{!! Form::close() !!}
     </div>
     
	@if ($cydistricts->path != '')    
     <div class="float-right" style="margin-right: 5px; margin-left: 5px;">
      <a href="{{ URL::to('/') }}/delete_district_img/{{$cydistricts->id}}" onclick="return confirm('Please confirm image deletion')" class="btn btn-danger">Delete Image</a> 
     </div>    
   @endif

   @if ($cydistricts->path_video != '')       
     <div class="float-right">
      <a href="{{ URL::to('/') }}/delete_district_video/{{$cydistricts->id}}" onclick="return confirm('Please confirm video deletion')" class="btn btn-danger">Delete Video</a> 
     </div>    
   @endif

	 <br><br>
	 <hr>

@endsection
