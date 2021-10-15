{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('corsin') }}
        </h2>
    </x-slot>

    @foreach ($users as $user)
      {{$user->name}}
      
    @endforeach
    {{$lineCount}}
    {{$activeCallCount}}
    {{$reviewCount}}


</x-app-layout> --}}

<x-app-layout>

<x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('corsin') }}
  </h2>
</x-slot>

    <example-component></example-component>

    
</x-app-layout>