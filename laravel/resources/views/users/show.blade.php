@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (Auth::user()->role == 'admin')
              <a href="{{ URL::to('/') }}/users" class="btn btn-primary">Go Back</a>
            @endif
            <br><br>
            <div class="card">
                <div class="card-header">User: {{$user->name}} {{$user->surname}}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

                            <div class="col-md-6">

                                <select disabled class="browser-default custom-select form-control @error('role') is-invalid @enderror" name="role" value="{{ old('role') }}" required autocomplete="role" autofocus>
                                  <option selected disabled >{{$user->role}}</option>
                                  <option value="normal">normal</option>
                                  <option value="moderator">moderator</option>
                                  <option value="admin">admin</option>
                                </select>

                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="{{$user->name}}" disabled>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" required autocomplete="surname" autofocus placeholder="{{$user->surname}}" disabled>

                                @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">Gender</label>
                            <div class="col-md-6">
                                <select class="browser-default custom-select form-control" name="gender"  autocomplete="gender" autofocus disabled>
                                  <option selected disabled >{{$user->gender}}</option>
                                  <option value="Male">Male</option>
                                  <option value="Female">Female</option>
                                  <option value="Prefer not to say">Prefer not to say</option>
                                </select>                                
                            </div>
                        </div>

                        <div class="form-group row ">
                           <label for="dob" class="col-md-4 col-form-label text-md-right">Date of Birth</label>
                              <div class="col-md-6 disabled">
                                 <input type="text" class="form-control " name="dob" required autocomplete="dob" autofocus placeholder="{{$user->dateOfBirth}}" disabled >
                              </div>    
                        </div>

                        <div class="form-group row ">
                           <label for="country" class="col-md-4 col-form-label text-md-right">Country</label>
                           <div class="col-md-6">
                                <select  class="browser-default custom-select form-control" name="country"  autocomplete="country" autofocus disabled>
                                   <option selected disabled >{{$user->country}}</option>
                                 </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="{{$user->username}}" disabled >

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{$user->email}}" disabled>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </form>
                    <hr>
                    <a href="{{ URL::to('/') }}/users/{{$user->id}}/edit" class="btn btn-warning">Edit User</a>

                    @if (Auth::user()->role == 'admin')
                      <div class="float-right">
                        {!!Form::open(['action' => ['UsersController@destroy', $user->id], 'method' => 'POST', 'class' => 'pull-right', 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                          {{Form::hidden('_method', 'DELETE')}}
                          {{Form::submit('Delete User', ['class' => 'btn btn-danger'] )}}
                        {!!Form::close()!!}
                      </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
