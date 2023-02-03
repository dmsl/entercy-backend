@extends('layouts.app')

@section('content')

<a href="{{ URL::to('/') }}/qrrooms/{{$qr_room->id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Show Qr room Groups</u></h1>
<br>

    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', $qr_room_grouped->name, ['class' => 'form-control', 'disabled' => 'Name'] )}}
    </div>
    <div class="form-group">
        {{Form::label('x_position', 'X position')}}
        {{Form::number('x_position', $qr_room_grouped->x_position, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_position', 'Y position')}}
        {{Form::number('y_position', $qr_room_grouped->y_position, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_position', 'Z position')}}
        {{Form::number('z_position', $qr_room_grouped->z_position, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>

      <div class="form-group">
        {{Form::label('x_scale', 'X scale')}}
        {{Form::number('x_scale', $qr_room_grouped->x_scale, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_scale', 'Y scale')}}
        {{Form::number('y_scale', $qr_room_grouped->y_scale, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_scale', 'Z scale')}}
        {{Form::number('z_scale', $qr_room_grouped->z_scale, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>

      <div class="form-group">
        {{Form::label('x_rotation', 'X rotation')}}
        {{Form::number('x_rotation', $qr_room_grouped->x_rotation, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_rotation', 'Y rotation')}}
        {{Form::number('y_rotation', $qr_room_grouped->y_rotation, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_rotation', 'Z rotation')}}
        {{Form::number('z_rotation', $qr_room_grouped->z_rotation, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
    <div class="form-group" hidden>
        {{Form::label('poiid', 'poiid')}}
        {{Form::text('poiid', $qr_room_grouped->qr_room_id , ['class' => 'form-control', 'disabled' => '' ] )}}
      </div>
    <br>
    <hr>
    <small>Created on {{$qr_room->created_at}} </small>
    <hr>

        <a href="{{ URL::to('/') }}/qrrooms_groups/{{$qr_room_grouped->id}}/edit" class="btn btn-warning">Edit QR room Group</a>

        <div class="float-right">
          {!!Form::open(['action' => ['Qr_room_groupsController@destroy', $qr_room_grouped->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete QR room Group', ['class' => 'btn btn-danger'] )}}
          {!!Form::close()!!}
        </div>

        <br>
        <hr>

<br>
<hr>

<div><h1><u>QR Room links</u></h1> </div>

  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Manage QR Room links</div>
                <div class="card-body">
                    <div class="panel-body">
                      <div>
                        <div class="float-md-left"><h1>QR Room links</h1> </div>
                        <div class="float-md-right"><a href="{{ URL::to('/') }}/create-qrrooms-linked/{{$qr_room_grouped->id}}" class="btn btn-primary">Create QR Room link</a> </div>
                      </div>

                      @if(count($qr_room_links)>0)
                        <table class="table table-striped">
                            <tr>
                              <th>Name</th>
                              <th></th>
                              <th></th>
                            </tr>
                            @foreach($qr_room_links as $qr_room_link)
                            <tr>
                              <td>
                                <h5><a href="{{ URL::to('/') }}/qrrooms_linked/{{$qr_room_link->id}}">{{$qr_room_link->name}}</a></h5>
                              </td>
                              <td><a href="{{ URL::to('/') }}/qrrooms_linked/{{$qr_room_link->id}}/edit" class="btn btn-warning">Edit</a></td>
                              <td>
                                {!!Form::open(['action' => ['Qr_room_linksController@destroy', $qr_room_link->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
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
