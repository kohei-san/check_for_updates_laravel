@props(['haveDifference'])

@php
$classes = ($haveDifference ?? false)
            ? 'cursor-pointer bg-transparent bg-red-500 hover:bg-red-700 font-semibold text-white py-2 px-4 rounded opacity-90'
            : 'py-2 px-4 rounded';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>