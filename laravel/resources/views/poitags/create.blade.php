@extends('layouts.app')

@section('content')

  <h1><u>Add POI Tag</u></h1>
<br>


   <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
<!--- ****na 8imase einai standalone SEARCH****
{!! Form::open(['action' => 'PoitagsController@search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div>
  {{Form::label('searchcriteria', 'Search Criteria')}}
  <div class="row">
      <div class="col-md-10">
	      {{Form::text('keyword', '', ['class' => 'form-control', 'placeholder' => 'Search by Tag Name here...'] )}}
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
--->
              <div class="card">
                  <div class="card-header">Please select a Tag to add</div>
                  <div class="card-body">                          
                      <div class="panel-body">
                        <div>
                          	{!! Form::open(['action' => 'PoitagsController@search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
              							<div>							  
              							  <div class="row">
              							      <div class="col-md-10">
              								      {{Form::text('keyword', '', ['class' => 'form-control', 'placeholder' => 'Search by Tag Name here...'] )}}
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

                        @if(count($tags)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Tag Name-Code</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($tags as $tags)

                              {!! Form::open(['action' => 'PoitagsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                              <tr>
                                <td>
                                  <h5 style="color: #3490dc;">
                                    Name: {{$tags->tag_name}} <br>
                                    Code: {{$tags->tag_code}} 
                                  </h5>
                                  
                                </td>
                                <td></td>
                                <td>
                                	<div class="form-group" hidden>
								      {{Form::label('poiid', 'POI ID')}}
								      {{Form::text('poiid', $poiid , ['class' => 'form-control', 'placeholder' => '' ] )}}
								    </div>
								    <div class="form-group" hidden>
								      {{Form::label('tagid', 'Tag ID')}}
								      {{Form::text('tagid', $tags->id , ['class' => 'form-control', 'placeholder' => '' ] )}}
								    </div>
								    
                                   {{Form::submit('Add', ['class' => 'btn btn-success'] )}}
  								   {!! Form::close() !!}
                                </td>
                              </tr>
                              @endforeach
                          </table>
                        @else
                          <br><br><br>
                          <p>No Tags found.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>



@endsection
