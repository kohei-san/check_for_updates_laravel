<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- icon -->
    <link rel="icon" href="{{asset('image/CorSin.png')}}">

    {{-- fontawsome / column sortable用 --}}
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/search.js') }}" defer></script>
  </head>
  <body class="font-sans antialiased">
    <div class="min-h-screen bg-white">

      <!-- Page Heading -->
      {{-- <header class="bg-white shadow">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          </div>
      </header> --}}

      <!-- Page Content -->
      <main>
        <section class="text-gray-600 body-font">
          <div class="container mx-auto flex px-5 py-24 items-center justify-center flex-col">
            <x-application-logo class="xl:w-1/3 lg:w-1/2 md:w-1/2 w-3/4 mb-10 object-cover object-center rounded" />
            <div class="flex justify-center">
              @auth
                <a href="{{ url('/dashboard') }}" class="inline-flex text-white border-0 py-2 px-6 focus:outline-none rounded text-lg bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500">Dashboardへ</a>
              @else
                <a href="{{ url('register') }}" class="inline-flex text-white border-0 py-2 px-6 focus:outline-none rounded text-lg bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500">新規登録</a>
                <a href="{{ route('login') }}" class="ml-4 inline-flex text-white border-0 py-2 px-6 focus:outline-none rounded text-lg bg-gradient-to-l from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500">ログイン</a>
              @endauth
            </div>
          </div>
        </section>
      </main>
    </div>
  </body>
</html>
