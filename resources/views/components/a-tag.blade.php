{{-- @props(['url']) --}}

@php
    $classes = "w-full h-full block";
@endphp

<a target="_blank" rel="noopener" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>