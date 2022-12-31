@if (session()->has('success'))
<div class="alert alert-success">
    <ul>
        <h5>{{ session()->get('success') }}</h5>
    </ul>
</div>
@endif
@if (session()->has('error'))
<div class="alert alert-danger">
    <ul>
        <h5>{{ session()->get('error') }}</h5>
    </ul>
</div>
@endif
@if($errors->all())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul>
</div>
@endif
