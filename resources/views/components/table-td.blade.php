@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-white border-t-2 border-b-2 border-gray-200 px-4 py-3'
            : 'bg-gray-300 border-t-2 border-b-2 border-gray-200 px-4 py-3';
@endphp

<td {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</td>