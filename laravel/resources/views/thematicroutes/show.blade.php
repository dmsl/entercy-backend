@extends('layouts.app')

@section('content')

<a href="{{ URL::to('/') }}/thematicroutes" class="btn btn-primary">Back to Thematic Routes</a>


<br><br>
  <h1><u>{{$thematicroute->name}}</u>  </h1>
  
  <br>

   @if ($thematicroute->path_img != '')
    Image:
    <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$thematicroute->path_img}}">
    <br><br>
   @endif
 

   @if ($thematicroute->path_thumbnail != '')
    Thumbnail:
    <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$thematicroute->path_thumbnail}}">
    <br><br>
   @endif
  
 
  <div class="form-group ">
      {{Form::label('name', 'Name in English')}}
      {{Form::text('name', $thematicroute->name , ['class' => 'form-control ', 'disabled','placeholder' => ''] )}}
    </div>
     <div class="form-group">
      {{Form::label('name_gr', 'Name in Greek')}}
      {{Form::text('name_gr',$thematicroute->name_gr , ['class' => 'form-control ', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ru', 'Name in Russian')}}
      {{Form::text('name_ru',$thematicroute->name_ru, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_it', 'Name in Italian')}}
      {{Form::text('name_it', $thematicroute->name_it, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_fr', 'Name in French')}}
      {{Form::text('name_fr', $thematicroute->name_fr, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Name in German')}}
      {{Form::text('name_ge', $thematicroute->name_ge, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>

    <div class="form-group">
     <label>Description in English:</label>
     <textarea class="form-control" rows="3" disabled> {{$thematicroute->description}} </textarea >
    </div>
    <div class="form-group">
     <label>Description in Greek:</label>
     <textarea class="form-control" rows="3" disabled> {{$thematicroute->description_gr}} </textarea >
    </div>
    <div class="form-group">
     <label>Description in Russian:</label>
     <textarea class="form-control" rows="3" disabled> {{$thematicroute->description_ru}} </textarea >
    </div>
    <div class="form-group">
     <label>Description in Italian:</label>
     <textarea class="form-control" rows="3" disabled> {{$thematicroute->description_it}} </textarea >
    </div>
    <div class="form-group">
     <label>Description in French:</label>
     <textarea class="form-control" rows="3" disabled> {{$thematicroute->description_fr}} </textarea >
    </div>
    <div class="form-group">
     <label>Description in German:</label>
     <textarea class="form-control" rows="3" disabled> {{$thematicroute->description_ge}} </textarea >
    </div>


  <hr>
  <small>Created on {{$thematicroute->created_at}}  </small>
  <hr>

  <a href="{{ URL::to('/') }}/thematicroutes/{{$thematicroute->id}}/edit" class="btn btn-warning">Edit</a>

      <div class="float-right">
        {!!Form::open(['action' => ['ThematicroutesController@destroy', $thematicroute->id], 'method' => 'POST', 'class' => 'pull-right', 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>




      <br>
      <hr>
      <br>
     
          <div><h1><u>Thematic Route's Sites</u></h1> </div>

          <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">Manage Thematic Route's Sites</div>
                        <div class="card-body">                          
                            <div class="panel-body">
                              <div>
                                <div class="float-md-left"><h1>Thematic Route's Sites</h1> </div>
                                <div class="float-md-right"><a href="{{ URL::to('/') }}/create-thematicroutesites/{{$thematicroute->id}}" class="btn btn-primary">Add site</a> </div>
                              </div>

                              @if(count($thematicroutesites)>0)
                                <table class="table table-striped">
                                    <tr>
                                      <th>Site Name</th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                    @foreach($thematicroutesites as $thematicroutesites)
                                    <tr>
                                      <td>
                                        <h5 style="color: #3490dc;">{{$thematicroutesites->name}}</h5>
                                      </td>
                                      <td></td>
                                      <td>
                                         {!!Form::open(['action' => ['ThematicroutesitesController@destroy', $thematicroutesites->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                          {{Form::hidden('_method', 'DELETE')}}
                                          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                        {!!Form::close()!!}
                                      </td>
                                    </tr>
                                    @endforeach
                                </table>
                              @else
                                <br><br><br>
                                <p>There are no registered sites.</p>
                              @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <hr>


@endsection
