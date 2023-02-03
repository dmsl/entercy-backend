@extends('layouts.app')

@section('content')

  <h1><u>Add Site to the Thematic Route</u></h1>
<br>

   <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">

              <div class="card">
                  <div class="card-header">Please select a Site to add</div>
                  <div class="card-body">                          
                      <div class="panel-body">
                        <div>
                          	{!! Form::open(['action' => 'ThematicroutesitesController@search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
              							<div>							  
              							  <div class="row">
              							      <div class="col-md-10">
              								      {{Form::text('keyword', '', ['class' => 'form-control', 'placeholder' => 'Search by Site Name here...'] )}}
              							      </div>
              							      <div class="form-group" hidden>
              							        {{Form::label('thematicroute_id', 'thematicroute ID')}}
              							        {{Form::text('thematicroute_id', $thematicroute_id , ['class' => 'form-control', 'placeholder' => '' ] )}}
              							      </div>
              							      <div class="col-md-2">
              							       {{Form::submit('Search', ['class' => 'btn btn-primary'] )}}  
              							      </div>
              							  </div>
              							</div>
						              	{!! Form::close() !!}
						            	<br>
                        </div>

                        @if(count($sites)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Site Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($sites as $sites)

                              {!! Form::open(['action' => 'ThematicroutesitesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                              <tr>
                                <td>
                                  <h5 style="color: #3490dc;">{{$sites->name}}</h5>
                                  
                                </td>
                                <td></td>
                                <td>
                                	<div class="form-group" hidden>
								      {{Form::label('thematicroute_id', 'thematicroute ID')}}
								      {{Form::text('thematicroute_id', $thematicroute_id , ['class' => 'form-control', 'placeholder' => '' ] )}}
								    </div>
								    <div class="form-group" hidden>
								      {{Form::label('siteid', 'Site ID')}}
								      {{Form::text('siteid', $sites->id , ['class' => 'form-control', 'placeholder' => '' ] )}}
								    </div>
								    
                                   {{Form::submit('Add', ['class' => 'btn btn-success'] )}}
  								   {!! Form::close() !!}
                                </td>
                              </tr>
                              @endforeach
                          </table>
                        @else
                          <br><br><br>
                          <p>No Sites found.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>



@endsection
