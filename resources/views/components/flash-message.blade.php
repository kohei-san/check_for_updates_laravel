@props(['status' => 'info'])

@php
if(session('status') === 'info'){$bgColor = 'text-blue-500';}
if(session('status') === 'alert'){$bgColor = 'text-red-500';}
@endphp

@if(session('message'))
  <div class="{{ $bgColor }} p-2 my-4">
    {{ session('message' )}}
  </div>
@endif
