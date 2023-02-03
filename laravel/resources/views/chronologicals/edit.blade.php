@extends('layouts.app')

@section('content')

   <a href="{{ url()->previous() }}" class="btn btn-primary">Back to Chronologicals</a>
   <br><br><br>

  <h1><u>Edit Chronological: {{$chronologicals->name}} </u></h1>

  {!! Form::open(['action' => ['ChronologicalsController@update', $chronologicals->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

  @if ($chronologicals->path_img != '')
  <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$chronologicals->path_img}}">
  @endif
  <br>

   <div class="form-group">
      <br>
      {{Form::label('path_img', 'Please select an image:')}}
      <br>
      {{Form::file('path_img')}}      
   </div>

   <hr>

   @if ($chronologicals->path_thumbnail != '')
  <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$chronologicals->path_thumbnail}}">
  @endif
  <br>

   <div class="form-group">
      <br>
      {{Form::label('path_thumbnail', 'Please select an thumbnail:')}}
      <br>
      {{Form::file('path_thumbnail')}}      
   </div>

   <hr>

   @if ($chronologicals->path_video != '')
    <video width="100%" controls>
    <source src="{{ URL::to('/') }}/storage/media/{{$chronologicals->path_video}}">
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
      {{Form::text('name_gr', $chronologicals->name_gr, ['class' => 'form-control', 'placeholder' => 'Name in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ru', 'Name in Russian')}}
      {{Form::text('name_ru', $chronologicals->name_ru, ['class' => 'form-control', 'placeholder' => 'Name in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_it', 'Name in Italian')}}
      {{Form::text('name_it', $chronologicals->name_it, ['class' => 'form-control', 'placeholder' => 'Name in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_fr', 'Name in French')}}
      {{Form::text('name_fr', $chronologicals->name_fr, ['class' => 'form-control', 'placeholder' => 'Name in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Name in German')}}
      {{Form::text('name_ge', $chronologicals->name_ge, ['class' => 'form-control', 'placeholder' => 'Name in German'] )}}
    </div>
     <hr>
     <br>

    <div class="form-group">
      {{Form::label('description', 'Description in English')}}
      {{Form::textarea('description', $chronologicals->description, ['class' => 'form-control', 'placeholder' => 'Description  in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_gr', 'Description in Greek')}}
      {{Form::textarea('description_gr', $chronologicals->description_gr, ['class' => 'form-control', 'placeholder' => 'Description in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_ru', 'Description in Russian')}}
      {{Form::textarea('description_ru', $chronologicals->description_ru, ['class' => 'form-control', 'placeholder' => 'Description in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_it', 'Description in Italian')}}
      {{Form::textarea('description_it', $chronologicals->description_it, ['class' => 'form-control', 'placeholder' => 'Description in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_fr', 'Description in French')}}
      {{Form::textarea('description_fr', $chronologicals->description_fr, ['class' => 'form-control', 'placeholder' => 'Description in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_ge', 'Description in German')}}
      {{Form::textarea('description_ge', $chronologicals->description_ge, ['class' => 'form-control', 'placeholder' => 'Description in German'] )}}
    </div>
 
    <hr>
    <div class="float-left">
        {{Form::hidden('_method', 'PUT')}}
    	{{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  		{!! Form::close() !!}
     </div>
     
	@if ($chronologicals->path_img != '')    
     <div class="float-right" style="margin-right: 5px; margin-left: 5px;">
      <a href="{{ URL::to('/') }}/delete_chronological_img/{{$chronologicals->id}}" onclick="return confirm('Please confirm image deletion')" class="btn btn-danger">Delete Image</a> 
     </div>    
   @endif

   @if ($chronologicals->path_video != '')       
     <div class="float-right">
      <a href="{{ URL::to('/') }}/delete_chronological_video/{{$chronologicals->id}}" onclick="return confirm('Please confirm video deletion')" class="btn btn-danger">Delete Video</a> 
     </div>    
   @endif

	 <br><br>
	 <hr>

   <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Sub-Chronologicals</div>

                  <div class="card-body">
                      @if (session('status'))
                          <div class="alert alert-success" role="alert">
                              {{ session('status') }}
                          </div>
                      @endif

                      <!---You are logged in!--->

                      <div class="panel-body">
                        <div>
                          <div class="float-md-left"><h1>Sub-Chronologicals</h1> </div>                          
                        </div>

                        @if(count($sub_chronologicals)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($sub_chronologicals as $sub_chronologicals)
                              <tr>
                                <td><h5 style="color: #3490dc;">{{$sub_chronologicals->name}}</td>
                                <td></td>
                                <td></td>
                              </tr>
                              @endforeach
                          </table>
                        @else
                        <br><br><br>
                          <p>There are no registered sub-chronologicals.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <br><br>


@endsection
