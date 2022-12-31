<div class="col-6">
    {{-- @yield('breadcrumb-title') --}}
    <?php
        $array_of_segments =Request::segments();
        $title = '';
        foreach($array_of_segments as $segment){
            if($segment == 'admin' || is_numeric($segment) || in_array($segment,['show','create','edit'])) continue;
            $title .=' '. $segment;
            // break;
        }
    ?>
            <h3>{{$title}}</h3>

</div>
<div class="col-6">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}"> <i data-feather="home"></i></a></li>
<?php $segments = ''; ?>
@foreach($array_of_segments as $segment)
    <?php $segments .= '/' . $segment; ?>
    @if ($segment != 'admin')
        <li style="list-style-type:none;" class="breadcrumb-item {{$loop->last ? 'active' : ''}}">
            <a href="{{ in_array($segment,['show','create','edit','dashboard']) ? '#':$segments }}">{{ $segment }}</a>
        </li>
    @endif
@endforeach
</ol>
</div>
