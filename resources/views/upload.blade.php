@foreach ($array as $item)
    @if (!empty($item))
        {{ $item }}] <br>
    @endif
@endforeach
