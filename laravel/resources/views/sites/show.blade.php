@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/sites" class="btn btn-primary">Go Back</a>
<br><br>
  <h1><u>{{$site->name}}</u></h1>
  <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$site->path}}">
  <br><br><br>
  @if(($site->path)!='noimage.jpg')
  <a href="{{ URL::to('/') }}/storage/media/{{$site->path}}" download>
    <button type="button" class="btn btn-info"><i class="fa fa-download"></i> Download Image</button>
  </a>
  <br><br><br>
  @endif
  <div class="form-group ">
      {{Form::label('name', 'Name in English')}}
      {{Form::text('name', $site->name , ['class' => 'form-control ', 'disabled','placeholder' => ''] )}}
    </div>
     <div class="form-group">
      {{Form::label('name_gr', 'Name in Greek')}}
      {{Form::text('name_gr',$site->name_gr , ['class' => 'form-control ', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ru', 'Name in Russian')}}
      {{Form::text('name_ru',$site->name_ru, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_it', 'Name in Italian')}}
      {{Form::text('name_it', $site->name_it, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_fr', 'Name in French')}}
      {{Form::text('name_fr', $site->name_fr, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Name in German')}}
      {{Form::text('name_ge', $site->name_ge, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>

  <div class="form-group">
    <br>
    <label>District:</label>
    <select id="typeOption" class="browser-default custom-select form-control" name="district" disabled >
      <option selected disabled >{{$cydistrict->name}}</option>
    </select>
  </div>
  <div class="form-group">
     <label>Town in English:</label>
     <input class="form-control" disabled value="{{$site->town}}">
  </div>
  <div class="form-group">
     <label>Town in Greek:</label>
     <input class="form-control" disabled value="{{$site->town_gr}}">
  </div>
  <div class="form-group">
     <label>Town in Russian:</label>
     <input class="form-control" disabled value="{{$site->town_ru}}">
  </div>
  <div class="form-group">
     <label>Town in Italian:</label>
     <input class="form-control" disabled value="{{$site->town_it}}">
  </div>
  <div class="form-group">
     <label>Town in French:</label>
     <input class="form-control" disabled value="{{$site->town_fr}}">
  </div>
  <div class="form-group">
     <label>Town in German:</label>
     <input class="form-control" disabled value="{{$site->town_ge}}">
  </div>
  <div class="form-group">
    <br>
    <label>Category:</label>
    <select id="typeOption" class="browser-default custom-select form-control" name="category" disabled >
      <option selected disabled >{{$sitecategory->name}}</option>
    </select>
  </div>
  <div class="form-group">
     <label>Charging Fee:</label>
     <input class="form-control" disabled value="{{$site->fee}}">
  </div>
  <div class="form-group">
     <label>Telephone:</label>
     <input class="form-control" disabled value="{{$site->contact_tel}}">
  </div>
  <div class="form-group">
     <label>Website:</label>
     <input class="form-control" disabled value="{{$site->url}}">
  </div>

  <hr>
  <small>Created on {{$site->created_at}} </small>
  <hr>


      <a href="{{ URL::to('/') }}/sites/{{$site->id}}/edit" class="btn btn-warning">Edit Site</a>

      <div class="float-right">
        {!!Form::open(['action' => ['SitesController@destroy', $site->id], 'method' => 'POST', 'class' => 'pull-right', 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete Site', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>



<br><br>
<hr>
  <h1><u>Main POI</u></h1>

  <div class="alert alert-primary" role="alert">
   The Main POI contains additional information about the Site. Right after the creation of the Site, it is recommended by the user to create a Main POI in order to fill out useful and related details.
  </div>

  @if(($poi)!='')
      <div class="card card-body bg-light">
        <div class="row">
           <div class="col-md-1 col-sm-1">
            <!--<img style="width:100%" src="/storage/cover_images/{{$poi->cover_image}}">-->
          </div>
          <div class="col-md-9 col-sm-9">
            <h3><a href="{{ URL::to('/') }}/pois/{{$poi->id}}">{{$poi->name}}</a></h3>
              <small>Created on {{$poi->created_at}}</small>
          </div>
          <div class="col-md-2 col-sm-2">
            <a href="{{ URL::to('/') }}/pois/{{$poi->id}}/edit" class="btn btn-warning">Edit</a>
            <div class="float-right">
            {!!Form::open(['action' => ['PoisController@destroy', $poi->id], 'method' => 'POST', 'class' => 'pull-right', 'onsubmit' => "return confirm('Please confirm deletion')"])!!}
              {{Form::hidden('_method', 'DELETE')}}
              {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
            {!!Form::close()!!}
            </div>
          </div>
        </div>
      </div>
      <br>
  @else
    <a href="{{ URL::to('/') }}/create-main-poi/{{$site->id}}" class="btn btn-primary">Create Main POI</a>
    <br><br>
  @endif


   <br>
   <hr>

    <div><h1><u>Site Accessibility Types</u></h1> </div>

    <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Manage Site Accessibility Types</div>
                  <div class="card-body">
                      <div class="panel-body">
                        <div>
                          <div class="float-md-left"><h1>Accessibility Types</h1> </div>
                          <div class="float-md-right"><a href="{{ URL::to('/') }}/create-accessibility/{{$site->id}}" class="btn btn-primary">Add Accessibility</a> </div>
                        </div>

                        @if(count($siteaccessibility)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($siteaccessibility as $siteaccessibility)
                              <tr>
                                <td>
                                  <h5 style="color: #3490dc;">{{$siteaccessibility->name}}</h5>
                                  <!---<h5><a href="{{ URL::to('/') }}/siteaccessibilities/{{$siteaccessibility->id}}">{{$siteaccessibility->name}}</a></h5>--->
                                </td>
                                <td></td>
                                <td>
                                   {!!Form::open(['action' => ['SiteaccessibilitiesController@destroy', $siteaccessibility->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')"])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                  {!!Form::close()!!}
                                </td>
                              </tr>
                              @endforeach
                          </table>
                        @else
                          <br><br><br>
                          <p>There are no registered Accessibility Types.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>




   <br>
   <hr>

    <div><h1><u>Site Working Hours</u></h1> </div>

    <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Manage Site Working Hours</div>
                  <div class="card-body">
                      <div class="panel-body">
                        <div>
                          <div class="float-md-left"><h1>Working Hours</h1> </div>
                          <div class="float-md-right"><a href="{{ URL::to('/') }}/create-siteworkinghours/{{$site->id}}" class="btn btn-primary">Add Working Hours</a> </div>
                        </div>

                        @if(count($siteworkinghour)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($siteworkinghour as $siteworkinghour)
                              <tr>
                                <td>
                                  <h5>
                                    <a href="{{ URL::to('/') }}/siteworkinghours/{{$siteworkinghour->id}}">{{$siteworkinghour->day}}
                                      @if (($siteworkinghour->open_time == '') && ($siteworkinghour->close_time == ''))
                                        [CLOSED]
                                      @endif
                                    </a>
                                  </h5>
                                </td>
                                <td>
                                  <a href="{{ URL::to('/') }}/siteworkinghours/{{$siteworkinghour->id}}/edit" class="btn btn-warning">Edit</a>
                                </td>
                                <td>
                                   {!!Form::open(['action' => ['SiteworkinghoursController@destroy', $siteworkinghour->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')"])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                  {!!Form::close()!!}
                                </td>
                              </tr>
                              @endforeach
                          </table>
                        @else
                          <br><br><br>
                          <p>There are no registered Working Hours.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>



@endsection
