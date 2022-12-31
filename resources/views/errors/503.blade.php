<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Happy Texting</title>
        <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.css')}}">
    <style>
        body{
            font-family: 'Montserrat', sans-serif;
        }
        .wrapper .body-wrapper {
    background-color: white;
            }

            .maintenance-image {
                /* height: 51%; */
                width: 30%;
            }

            .maintenance-subtitle{
                margin-top: 20px;
            }

            .maintenance-subtitle h1 {
                color: #92CDD2 !important;
                font-weight: bold;
                font-size: 30px;
                word-break: break-word;
            }

            .maintenance-subtitle p {
                color: #92CDD2;
            }

            .maintenance-page{
                background-color: white;
                text-align:center;
            }

            .maintenance-page{
                height: 100vh;
            }

            @media screen and (max-width : 767px) {
                .maintenance-subtitle h1 {
                    font-size: 20px;
                }
                .maintenance-image {
                    /* height: 51%; */
                    width: 50%;
                }

            }
            @media screen and (max-width : 560px) {
                .maintenance-subtitle h1 {
                    font-size: 16px;
                }
            }
    </style>
    </head>
    <body>
        <div id="app">
            <div class="wrapper compact-wrapper">
                <div class="body-wrapper null">
                    <div class="d-flex flex-column text-center align-items-center py-3 maintenance-page">
                            <img class="img-fluid maintenance-image text-center" src="{{asset('assets/images/maintenance.jpg')}}"  alt="logo" />
                            <div class="text-center maintenance-subtitle">
                                <h1>This site is under maintenance</h1>
                                <p>We're preparing to serve you better</p>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   </body>
</html>
