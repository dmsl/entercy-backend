@extends('layouts.app')

@section('content')

  <h1><u>Add Artifact </u></h1>
<br>


   <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">

            <div class="card">
                  <div class="card-header">Please select an Artifact to add</div>
                  <div class="card-body">
                      <div class="panel-body">
                        <div>
                          	{!! Form::open(['action' => 'PoisController@searchartifacts', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
              							<div>
              							  <div class="row">
              							      <div class="col-md-10">
              								      {{Form::text('keyword', '', ['class' => 'form-control', 'placeholder' => 'Search by Poi name here...'] )}}
              							      </div>
              							      <div class="form-group" hidden>
              							        {{Form::label('poiid', 'POI ID')}}
              							        {{Form::text('poiid', $poiid , ['class' => 'form-control', 'placeholder' => '' ] )}}
              							      </div>
              							      <div class="col-md-2">
              							       {{Form::submit('Search', ['class' => 'btn btn-primary'] )}}
              							      </div>
              							  </div>
              							</div>
						              	{!! Form::close() !!}
						            	<br>
                        </div>

                        @if(count($pois)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Artifact/Poi Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($pois as $poi)

                              {!! Form::open(['action' => 'PoisController@storeartifacts', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                              <tr>
                                <td>
                                  <h5 style="color: #3490dc;">
                                    Name: {{$poi->name}} <br>
                                    Poi ID: {{$poi->id}}
                                  </h5>

                                </td>
                                <td></td>
                                <td>
                                	<div class="form-group" hidden>
								      {{Form::label('poiid', 'POI ID')}}
								      {{Form::text('poiid', $poiid , ['class' => 'form-control', 'placeholder' => '' ] )}}
								    </div>
								    <div class="form-group" hidden>
								      {{Form::label('poi_to_be_added', 'poi_to_be_added')}}
								      {{Form::text('poi_to_be_added', $poi->id , ['class' => 'form-control', 'placeholder' => '' ] )}}
								    </div>

                                   {{Form::submit('Add', ['class' => 'btn btn-success'] )}}
  								   {!! Form::close() !!}
                                </td>
                              </tr>
                              @endforeach
                          </table>
                        @else
                          <br><br><br>
                          <p>No Pois found.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>



@endsection
