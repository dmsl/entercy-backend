@extends('layouts.app')


@section('content')
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Manage Service Categories</div>

                  <div class="card-body">
                      @if (session('status'))
                          <div class="alert alert-success" role="alert">
                              {{ session('status') }}
                          </div>
                      @endif

                      <!---You are logged in!--->

                      <div class="panel-body">
                        <div>
                          <div class="float-md-left"><h1>Service Categories</h1> </div>
                        </div>

                        @if(count($service_category)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($service_category as $service_cat)
                              <tr>
                                <td><h5 style="color: #3490dc;">{{$service_cat->name}}</td>
                                <td></td>
                                <td>
                                 <a href="{{ URL::to('/') }}/servicecategories/{{$service_cat->id}}/edit" class="btn btn-warning">Edit</a>
                                </td>
                              </tr>
                              @endforeach
                          </table>
                        @else
                          <p>There are no registered Service Categories.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection

