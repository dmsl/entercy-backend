@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/pois/{{$qr_room->poi_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Show Qrrooms</u></h1>
<br>

    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', $qr_room->name, ['class' => 'form-control', 'disabled' => 'Name'] )}}
    </div>
    <div class="form-group ">
       {{Form::label('qr_code', 'QR Code')}}
      {{Form::textarea('qr_code', $qr_room->qr_code, ['class' => 'form-control', 'disabled' => 'Text'] )}}
    </div>
    <div class="form-group" hidden>
        {{Form::label('poiid', 'poiid')}}
        {{Form::text('poiid', $qr_room->poi_id , ['class' => 'form-control', 'disabled' => '' ] )}}
      </div>
    <br>
    <hr>
    <small>Created on {{$qr_room->created_at}} </small>
    <hr>

        <a href="{{ URL::to('/') }}/qrrooms/{{$qr_room->id}}/edit" class="btn btn-warning">Edit QR room</a>

        <div class="float-right">
          {!!Form::open(['action' => ['Qr_roomsController@destroy', $qr_room->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete QR room', ['class' => 'btn btn-danger'] )}}
          {!!Form::close()!!}
        </div>

        <br>
        <hr>

        <div><h1><u>QR Room Groups</u></h1> </div>

          <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">Manage QR Room Groups</div>
                        <div class="card-body">
                            <div class="panel-body">
                              <div>
                                <div class="float-md-left"><h1>QR Room Groups</h1> </div>
                                <div class="float-md-right"><a href="{{ URL::to('/') }}/create-qrrooms-grouped/{{$qr_room->id}}" class="btn btn-primary">Create QR Room Group</a> </div>
                              </div>

                              @if(count($qr_room_grouped)>0)
                                <table class="table table-striped">
                                    <tr>
                                      <th>Name</th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                    @foreach($qr_room_grouped as $qr_room_group)
                                    <tr>
                                      <td>
                                        <h5><a href="{{ URL::to('/') }}/qrrooms_groups/{{$qr_room_group->id}}">{{$qr_room_group->name}}</a></h5>
                                      </td>
                                      <td><a href="{{ URL::to('/') }}/qrrooms_groups/{{$qr_room_group->id}}/edit" class="btn btn-warning">Edit</a></td>
                                      <td>
                                         {!!Form::open(['action' => ['Qr_room_groupsController@destroy', $qr_room_group->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                          {{Form::hidden('_method', 'DELETE')}}
                                          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                        {!!Form::close()!!}
                                      </td>
                                    </tr>
                                    @endforeach
                                </table>
                              @else
                                <br><br><br>
                                <p>There are no registered Linked QR Rooms.</p>
                              @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
