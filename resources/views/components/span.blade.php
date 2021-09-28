@props(['registered'])

@php
$classes = ($registered ?? false)
            ? 'cursor-pointer bg-transparent bg-green-400 font-semibold text-white py-2 px-4 rounded lineregister'
            : 'cursor-pointer lineregister';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>