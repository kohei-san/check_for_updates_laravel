<x-app-layout>
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

  <div class="h-full p-4">
    <canvas id="blog" data-blog="{{$updated}}" data-all="{{$allCustomers}}" height="50px" class=""></canvas>
    <script src="/Applications/MAMP/htdocs/check_for_updates_laravel/chartjs-plugin-annotation/src/annotation.js"></script>
    <script src="{{ asset('js/blog-bar-chart.js') }}" defer></script>
  </div>

  
  <div class="flex">
    <div class="w-1/3 p-4">
      <canvas id="mail" data-mail="1" data-all="{{$allCustomers - $lineCount}}"></canvas>
      <script src="{{ asset('js/mail-chart.js') }}" defer></script>
    </div>
    <div class="w-1/3 p-4">
      <canvas id="LineRegister" data-line="{{$lineCount}}" data-all="{{$allCustomers - $lineCount}}"></canvas>
      <script src="{{ asset('js/line-chart.js') }}" defer></script>
    </div>
    <div class="w-1/3 p-4">
      <canvas id="call" data-call="{{$activeCallCount}}" data-all="{{$allCustomers - $activeCallCount}}"></canvas>
      <script src="{{ asset('js/call-chart.js') }}" defer></script>
    </div>
  </div>



</x-app-layout>
