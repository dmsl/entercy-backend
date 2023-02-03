<html>

<head>
    <title>Title of the document.</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @extends('layouts.app')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{ csrf_field() }}
    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <button onclick="startFCM()" class="btn btn-danger btn-flat">Allow notification</button>

                        <div class="card mt-3">
                            <div class="card-body">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form action="{{ route('send.web-notification') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Message Title</label>
                                        <input type="text" class="form-control" name="title">
                                    </div>
                                    <div class="form-group">
                                        <label>Message Body</label>
                                        <textarea class="form-control" name="body"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block">Send Notification</button>
                                </form>

                            </div>
                        </div>
                </div>
            </div>
        </div>

        <!-- The core Firebase JS SDK is always required and must be listed first -->
        <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script>

            //This works
            /*var firebaseConfig = {
                apiKey: "AIzaSyBPH2XtLUKBlo-4F-Gzg_quBITFsA8yx3M",
                authDomain: "entercy-4ee78.firebaseapp.com",
                databaseURL: "https://entercy-4ee78-default-rtdb.firebaseio.com",
                projectId: "entercy-4ee78",
                storageBucket: "entercy-4ee78.appspot.com",
                messagingSenderId: "559252959640",
                appId: "1:559252959640:web:8374564f8337ffffdcfbed",
                measurementId: "G-PCCWEB7PYJ"
            };*/

            //This doesn't works
            var firebaseConfig = {
                apiKey: "AIzaSyAp2Cqcveo14I8H_4c_zSGItWwliKB658w",
                authDomain: "entercy-official.firebaseapp.com",
                projectId: "entercy-official",
                storageBucket: "entercy-official.appspot.com",
                messagingSenderId: "844319687008",
                appId: "1:844319687008:web:4d263946cbcbc455291e05",
                measurementId: "G-T761H71NF5"
            };

            

            firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();


            function startFCM() {
                messaging
                    .requestPermission()
                    .then(function() {
                        console.log('Notification permission granted.');
                        messaging.getToken()
                            .then((currentToken) => {
                                if (currentToken) {
                                    console.log('Token: ' + currentToken);
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });
                                    var pass = {
                                        'token': currentToken,
                                    };
                                    $.ajax({
                                        type: 'POST',
                                        url: '{{ route("save-token") }}',
                                        data: pass,
                                        success: function(currentToken) {
                                            console.log(currentToken.msg);
                                        },
                                        error: function(error) {
                                            alert(error);
                                        },
                                    });
                                } else {
                                    //doesn't reach here
                                    console.log('No Instance ID token available. Request permission to generate one.');
                                    setTokenSentToServer(false);
                                }
                            })
                            .catch(function(err) {
                                //doesn't reach here either
                                console.log('An error occurred while retrieving token. ', err);
                            });
                    })
                    .catch(function(err) {
                        console.log('Unable to get permission to notify. ', err);
                    });

                messaging.onMessage(function(payload) {
                    const title = payload.notification.title;
                    const options = {
                        body: payload.notification.body,
                        icon: payload.notification.icon,
                    };
                    new Notification(title, options);
                });
            }
        </script>
    @endsection
</body>

</html>
