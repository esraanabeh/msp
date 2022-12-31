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
    <p>Hello {{$client->contact_person}}</p>
    <h5>Questions</h5>
    @foreach ($quote->questions as $question )
        <p>{{$question->question}}</p>
    @endforeach
    <hr>
    <p>You Can Make Decision on this Quote Using the Provided Link</p>
    <a href="{{$link}}">Click Here</a>
    <small>the provided link is one-time use</small>
</body>
</html>
