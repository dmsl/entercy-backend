<style type="text/css">

ul li {
  display: block;
  position: relative;
  float: left;
}

li ul {
  display: none;
}

li:hover ul {
  display: block;
  position: absolute;
}

</style>
<!--- navbar-light bg-white --->
<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
    <div class="container">
        @guest
        <a class="navbar-brand" href="{{ url('/cms') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        @else
        <a class="navbar-brand" href="{{ url('/sites') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        @endguest
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

              @guest

              @else
                <li class="nav-item">
                  <a class="nav-link" href="{{ URL::to('/') }}/users/{{Auth::user()->id}}">{{Auth::user()->name}} {{Auth::user()->surname}}</a>
                </li>
              @endguest

            </ul>

            <!-- <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="/">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/sites">Sites</a>
              </li>
            </ul> -->

            <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
              <!-- Authentication Links -->
              @guest
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                  </li>
                  @if (Route::has('register'))
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                      </li>
                  @endif
              @else

                <!-- touto doulefki -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
                </form>

                @if(Auth::user()->role != 'normal')
                  <li class="nav-item">
                    <a class="nav-link" href="{{ URL::to('/') }}/appcontent">App Content</a>
                  </li>
                   <li class="nav-item">
                    <a class="nav-link" href="{{ URL::to('/') }}/sites">Sites</a>
                  </li>
                  <!---<li class="nav-item">
                    <a class="nav-link" href="{{ URL::to('/') }}/tags">Tags</a>
                  </li>--->
                  <li class="nav-item">
                    <a class="nav-link" href="{{ URL::to('/') }}/cydistricts">Districts</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ URL::to('/') }}/sitecategories">Categories</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ URL::to('/') }}/chronologicals">Chronologicals</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ URL::to('/') }}/thematicroutes">Thematic-Routes</a>
                  </li>
                  <!---<li class="nav-item">
                    <a class="nav-link" href="{{ URL::to('/') }}/poimediatypes">Media-Types</a>
                  </li>--->
                  <li class="nav-item">
                    <a class="nav-link" href="">More Tools</a>
                    <ul style="background-color: #343a40;  padding: 5px 15px 5px 15px;">
                      <li ><a style="color: rgba(255, 255, 255, 0.5);" href="{{ URL::to('/') }}/questions">Questionnaire</a> </li>
                      <li ><a style="color: rgba(255, 255, 255, 0.5); " href="{{ URL::to('/') }}/poimediatypes">Media-Types</a></li>
                      <li ><a style="color: rgba(255, 255, 255, 0.5); " href="{{ URL::to('/') }}/servicecategories">ServiceCategories</a></li>
                      <li ><a style="color: rgba(255, 255, 255, 0.5); " href="{{ URL::to('/') }}/tags">Tags</a></li>
                      <li ><a style="color: rgba(255, 255, 255, 0.5); " href="{{ URL::to('/') }}/push-notification">Push-notification</a></li>
                      <li ><a style="color: rgba(255, 255, 255, 0.5); " href="{{ URL::to('/') }}/services">Services</a></li>
                    </ul>
                  </li>
                @endif

                @if(Auth::user()->role == 'admin')
                  <li class="nav-item">
                    <a class="nav-link" href="{{ URL::to('/') }}/users">Users</a>
                  </li>
                @endif
                <li class="nav-item">
                   <a class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
                      {{ __('Logout') }}
                   </a>
                </li>
              @endguest
          </ul>

        </div>
    </div>
</nav>

<!---<nav class="navbar navbar-expand-md navbar-dark bg-dark  ">
  <a class="navbar-brand" href="/">{{config('app.name', 'SOTOSdev')}}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/about">About</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/services">Services</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/posts">Blog(Sites)</a>
      </li>
    </ul>
    <ul class="navbar-nav navbar-right">
      <li class="nav-item">
        <a class="nav-link" href="/posts/create">Create Site</a>
      </li>
    </ul>

  </div>
</nav>
<br> --->
