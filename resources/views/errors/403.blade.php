<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>403 - Forbidden</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.3/css/bootstrap.min.css"
        integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            height: 100vh;
            background-color: #fff;
        }

        .container {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .information {
            margin-top: 3rem;
            text-align: center;
        }

        .information h1 {
            color: #3F3D56;
        }

        .information p {
            color: #3F3D56;
        }

        .btn-success {
            color: #f1f5f9;
            font-family: 'Poppins';
            background-color: #42B549;
        }

        .btn-success:hover,
        .btn-success:focus,
        .btn-success:active {
            background-color: #3ca343 !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
            <img src="{{ asset('assets/image/errors/403.jpg') }}" class="img-fluid" alt="404 Not Found">

            <div class="information">
                <h1>Stop, Forbidden Pages</h1>
                <p>Sorry, you do not have permission to access this page</p>
            </div>

            <center><button class="btn btn-success mt-3" onclick="window.history.back()">Return to previous
                    page</button></center>
        </div>
    </div>
</body>

</html>
