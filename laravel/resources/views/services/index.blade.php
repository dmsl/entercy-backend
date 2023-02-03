@extends('layouts.app')


  @section('content')
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Manage Services</div>

                  <div class="card-body">
                      @if (session('status'))
                          <div class="alert alert-success" role="alert">
                              {{ session('status') }}
                          </div>
                      @endif

                      <!---You are logged in!--->

                      <div class="panel-body">
                        <div>
                          <div class="float-md-left"><h1>Services</h1> </div>
                          <div class="float-md-right"><a href="{{ URL::to('/') }}/services/create" class="btn btn-primary">Create Service</a> </div>
                        </div>

                        @if(count($services)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($services as $service)
                              <tr>
                                <td><a href="{{ URL::to('/') }}/services/{{$service->id}}">{{$service->name}}</a></td>
                                <td><a href="{{ URL::to('/') }}/services/{{$service->id}}/edit" class="btn btn-warning">Edit</a></td>
                                <td>
                                  {!!Form::open(['action' => ['ServicesController@destroy', $service->id], 'method' => 'POST', 'class' => 'pull-right', 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                  {!!Form::close()!!}
                                </td>
                              </tr>
                              @endforeach                              
                          </table>
                          <!---removed since $services it's a join in the controller and laravel doesn't like the links function---> 
                          <!---{ {$services->links()} }---> 
                        @else
                          <br><br><br>
                          <p>There are no registered services.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  @endsection

