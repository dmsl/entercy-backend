@extends('layouts.app')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<a href="{{ URL::to('/') }}/pois/{{$poimedia->poi_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>{{$poimedia->name}}</u></h1>

  <div class="form-group">
    <br>
    <label>Type</label>
    <select id="typeOption" onchange="val()" class="browser-default custom-select form-control" name="type" disabled >
      <option selected disabled >{{$pmtype->name}}</option>
      <option value="image">Image File</option>
      <option value="video">Video File</option>
      <option value="text_en">Text in English</option>
      <option value="text_gr">Text in Greek</option>
      <option value="zip">Zip File</option>
    </select>
  </div>

  @if (strpos($pmtype->name, 'Text') !== false)
    <div class="form-group">
       <label>Text</label>
       <textarea class="form-control" rows="5" disabled> {{$poimedia->uri}} </textarea >
    </div>
  @else
    <div class="form-group">
       <label>File</label>
       <input class="form-control" disabled value="{{$poimedia->uri}}">
       <!---just displaying the media--->
       <br>
         @if (strpos($pmtype->name, 'Image') !== false) 
              @if ($poimedia->uri != '')
              <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$poimedia->uri}}">
              @endif
         @elseif (strpos($pmtype->name, 'Video') !== false) 
              @if ($poimedia->uri != '')
              <video width="100%" controls>
              <source src="{{ URL::to('/') }}/storage/media/{{$poimedia->uri}}">
              Your browser does not support the video tag.
              </video>
             @endif
         @endif

         @if (strpos($pmtype->name, '3D Model Mobile (GTFL)') !== false) 
              <label>File (iPhone format only)</label>
              <input class="form-control" disabled value="{{$poimedia->uri2}}">
              @if ($poimedia->uri2 != '')
              <br>
              <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$poimedia->uri2}}">
              @endif
         @endif
       <!---end of displaying the media--->
    </div>
  @endif

  <div class="form-group">
       <label>Thumbnail File</label>
       <input class="form-control" disabled value="{{$poimedia->path_thumbnail}}">
       <!---just displaying the media--->
       <br>
        @if ($poimedia->path_thumbnail != '')
        <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$poimedia->path_thumbnail}}">
        @endif       
  </div>

   <div class="form-group">
      {{Form::label('artifact_poi_id', 'Please select where the artifact was originally found (if applicable)')}}
      <br>
      <select name="artifact_poi_id" id="artifact_poi_id" class="form-control select2" disabled>
         <option selected disabled >Please select where the artifact was originally found</option>
          @foreach($pois as $pois)
                <option value="{{$pois->id}}">{{$pois->name}}</option>
          @endforeach     
      </select>
    </div>


  <hr>
  <small>Created on {{$poimedia->created_at}} </small>
  <hr>


      <a href="{{ URL::to('/') }}/poimedia/{{$poimedia->id}}/edit" class="btn btn-warning">Edit POI Media</a>

      <div class="float-right">
        {!!Form::open(['action' => ['PoimediasController@destroy', $poimedia->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete POI Media', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>


<script>

    $( document ).ready(function() {
      document.getElementById('artifact_poi_id').value = '{{$poimedia->artifact_poi_id}}'; //preselect type option
    });


  </script>
@endsection

