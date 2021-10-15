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

  <canvas id="blog" data-blog="{{$updated}}" data-all="{{$allCustomers}}" width="400" height="100"></canvas>
  <script src="{{ asset('js/blog-bar-chart.js') }}" defer></script>

  
    <div class="flex fl">
    <div>
      <canvas id="mail" data-mail="1" data-all="{{$allCustomers - $lineCount}}" width="400" height="400"></canvas>
      <script src="{{ asset('js/mail-chart.js') }}" defer></script>
    </div>
    <div>
      <canvas id="LineRegister" data-line="{{$lineCount}}" data-all="{{$allCustomers - $lineCount}}" width="400" height="400"></canvas>
      <script src="{{ asset('js/line-chart.js') }}" defer></script>
    </div>
    <div>
      <canvas id="call" data-call="{{$activeCallCount}}" data-all="{{$allCustomers - $activeCallCount}}" width="400" height="400"></canvas>
      <script src="{{ asset('js/call-chart.js') }}" defer></script>
    </div>
  </div>



</x-app-layout>
