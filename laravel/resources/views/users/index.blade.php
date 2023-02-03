@extends('layouts.app')

@if (Auth::user()->role == 'admin')
  @section('content')
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Manage User Accounts</div>

                  <div class="card-body">
                      @if (session('status'))
                          <div class="alert alert-success" role="alert">
                              {{ session('status') }}
                          </div>
                      @endif

                      <!---You are logged in!--->

                      <div class="panel-body">
                        <div>
                          <div class="float-md-left"><h1>Users</h1> </div>
                          <div class="float-md-right"><a href="{{ URL::to('/') }}/users/create" class="btn btn-primary">Create User</a> </div>
                        </div>

                        @if(count($users)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Full Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($users as $user)
                              <tr>
                                <td><a href="{{ URL::to('/') }}/users/{{$user->id}}">{{$user->name}} {{$user->surname}} [{{$user->role}}]</a></td>
                                <td><a href="{{ URL::to('/') }}/users/{{$user->id}}/edit" class="btn btn-warning">Edit</a></td>
                                <td>
                                  {!!Form::open(['action' => ['UsersController@destroy', $user->id], 'method' => 'POST', 'class' => 'pull-right', 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                  {!!Form::close()!!}
                                </td>
                              </tr>
                              @endforeach                              
                          </table>
                          {{$users->links()}}
                        @else
                          <p>There are no registered users.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  @endsection
@endif
