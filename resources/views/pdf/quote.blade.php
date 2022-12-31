<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
    </style>
</head>
<body>
    <h5>Client Information</h5>
    <p>{{$data['client_information']['contact_person']}}</p>
    <p>{{$data['client_information']['company_name']}}</p>
    <p>{{$data['client_information']['phone_number']}}</p>
    <p>{{$data['client_information']['email']}}</p>

    <hr>

    <h5>Introduction</h5>
    <p>{{$data['introduction']}}</p>

    <hr>

    <h5>Services</h5>
    @foreach ($data['services'] as $service)

    <p><span>{{$service['title']}}</span> &nbsp;&nbsp; <span>{{isset($service['total_amount']) ? $service['total_amount'] : $service->pivot->total_amount}}</span></p>

    @endforeach



</body>
</html>
