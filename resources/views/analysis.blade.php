<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('corsin') }}
        </h2>
    </x-slot>

  <div class="h-full p-4">
    <canvas id="blog" data-blog="{{$record['updated']}}" data-all="{{$record['blogCustomersAll']}}" height="50px" class=""></canvas>
    <script src="/Applications/MAMP/htdocs/check_for_updates_laravel/chartjs-plugin-annotation/src/annotation.js"></script>
    <script src="{{ asset('js/blog-bar-chart.js') }}" defer></script>
  </div>

  
  <div class="flex">
    <div class="w-1/3 p-4">
      <canvas id="mail" data-mail="1" data-all="{{$record['blogCustomersAll']}}"></canvas>
      <script src="{{ asset('js/mail-chart.js') }}" defer></script>
    </div>
    <div class="w-1/3 p-4">
      <canvas id="LineRegister" data-line="{{$record['line']}}" data-all="{{$record['blogCustomersAll'] - $record['line']}}"></canvas>
      <script src="{{ asset('js/line-chart.js') }}" defer></script>
    </div>
    <div class="w-1/3 p-4">
      <canvas id="call" data-call="{{$record['activeCall']}}" data-all="{{$record['blogCustomersAll'] - $record['activeCall']}}"></canvas>
      <script src="{{ asset('js/call-chart.js') }}" defer></script>
    </div>
  </div>
</x-app-layout>