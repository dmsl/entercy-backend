@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/pois/{{$storytelling->poi_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>{{$storytelling->name}}</u></h1>


   <div class="form-group">
      {{Form::label('language', 'Language')}}
      <br>
      <select id="languageOption" onchange="val()" class="browser-default custom-select form-control" name="language" required disabled>
        <option selected disabled >{{$storytelling->language}}</option>        
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
      <select id="durationOption" onchange="val2()" class="browser-default custom-select form-control" name="duration" required disabled>
        <option selected disabled >{{$storytelling->duration}}</option>        
         <option value="Short">Short</option>
         <option value="Long">Long</option>      
      </select>
    </div>
       
    <div class="form-group ">
      {{Form::label('description', 'Description')}}
      {{Form::textarea('description', $storytelling->description, ['class' => 'form-control','disabled', 'placeholder' => ''] )}}
    </div>
       
    <div class="form-group">
       <label>Audio File</label>
       <input class="form-control" disabled value="{{$storytelling->path}}">
       <br>
       <audio controls>
          <source src="{{ URL::to('/') }}/storage/media/{{$storytelling->path}}">
        Your browser does not support the audio element.
       </audio>
    </div>

    <div class="form-group">
       <label>Thumbnail File</label>
       <input class="form-control" disabled value="{{$storytelling->path_thumbnail}}">
       <br>
       @if ($storytelling->path_thumbnail != '')
       <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$storytelling->path_thumbnail}}">
       @endif
    </div>

  <hr>
  <small>Created on {{$storytelling->created_at}} </small>
  <hr>

      <a href="{{ URL::to('/') }}/storytellings/{{$storytelling->id}}/edit" class="btn btn-warning">Edit Story Telling</a>

      <div class="float-right">
        {!!Form::open(['action' => ['StorytellingsController@destroy', $storytelling->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete Story Telling', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>


@endsection
