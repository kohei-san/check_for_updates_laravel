@props(['registered'])

@php
$classes = ($registered ?? false)
            ? 'cursor-pointer bg-transparent bg-yellow-500 hover:bg-green-500 font-semibold text-white py-2 px-4 rounded'
            : 'cursor-pointer py-2 px-4 rounded hover:bg-gray-500 hover:text-white hover:opacity-50';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>