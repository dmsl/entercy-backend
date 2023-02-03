@extends('layouts.app')


@section('content')
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Manage App Content</div>

                  <div class="card-body">
                      @if (session('status'))
                          <div class="alert alert-success" role="alert">
                              {{ session('status') }}
                          </div>
                      @endif

                      <!---You are logged in!--->

                      <div class="panel-body">
                        <div>
                          <div class="float-md-left"><h1>App Content</h1> </div>                          
                        </div>

                        @if(count($appcontents)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($appcontents as $appcontents)
                              <tr>
                                <td><h5 style="color: #3490dc;">{{$appcontents->name}}</td>
                                <td></td>
                                <td>
                                 <a href="{{ URL::to('/') }}/appcontent/{{$appcontents->id}}/edit" class="btn btn-warning">Edit</a>
                                </td>
                              </tr>
                              @endforeach
                          </table>
                        @else
                        <br><br><br><br>
                          <p>There are no registered records.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection

