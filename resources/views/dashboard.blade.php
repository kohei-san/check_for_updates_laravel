<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('進捗状況シート') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-flash-message status="session('status')" />
                    こんにちは{{ Auth::user()->name }}さん
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-center p-4">(▼現在制作中)<br>ブログ導入顧客({{$record['blogCustomersAll']}}件)</div>
                    <div class="h-full p-4">
                        <canvas id="blog" data-blog="{{$record['updated']}}" data-all="{{$record['blogCustomersAll']}}" height="50px" class=""></canvas>
                        <script src="/Applications/MAMP/htdocs/check_for_updates_laravel/chartjs-plugin-annotation/src/annotation.js"></script>
                        <script src="{{ asset('js/blog-bar-chart.js') }}" defer></script>
                        </div>
                    
                        
                        <div class="flex">
                            <div class="w-1/3 p-4">
                                {{-- <h3>メールアドレス保有</h3> --}}
                                <canvas id="mail" data-mail="1" data-all="{{$record['blogCustomersAll']}}"></canvas>
                                <script src="{{ asset('js/mail-chart.js') }}" defer></script>
                            </div>
                            <div class="w-1/3 p-4 relative">
                                {{-- <span class="inline-block w-full h-full text-center -translate-y-2/3">55%</span>
                                <span class="inline-block w-full text-center absolute">---件</span> --}}
                                {{-- <h3>ライン保有</h3> --}}
                                {{-- <span class="">{{$record['lineRegisteredRate']}}%</span> --}}
                                <canvas id="LineRegister" data-line="{{$record['line']}}" data-all="{{$record['blogCustomersAll'] - $record['line']}}">555</canvas>
                                <script src="{{ asset('js/line-chart.js') }}" defer></script>
                                <div class="w-full absolute inset-1/2 font-bold text-gray-500">
                                    <p>{{$record['lineRegisterRate']}}%</p>
                                    <p>---件</p>
                                </div>
                            </div>
                            <div class="w-1/3 p-4">
                                {{-- <h3>10/1以降の電話接触</h3> --}}
                                <canvas id="call" data-call="{{$record['activeCall']}}" data-all="{{$record['blogCustomersAll'] - $record['activeCall']}}"></canvas>
                                <script src="{{ asset('js/call-chart.js') }}" defer></script>
                            </div>
                        </div>
                </div>
                
                
                {{-- ▼管理者メニュー --}}
                @if(Auth::user()->is_admin == 1)
                    <div class="p-6 bg-white border-b border-gray-200">
                        <a href="{{ route('python') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ml-3">
                            {{ __('差分取得システム始動') }}
                        </a>
                    </div>
                    <div class="p-6 bg-white border-b border-gray-200">
                        <a href="{{ route('not-active.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ml-3">
                            {{ __('停止中顧客一覧') }}
                        </a>
                    </div>
                
                    {{-- Lineテーブルへcustomer_idの書き込み（エクセル→DBにデータ移行するとき必要、120秒でタイムアウトするが、くじけず５回くらい実行する） --}}
                    {{-- <div class="p-6 bg-white border-b border-gray-200">
                        <a href="{{ route('writecustomerid') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ml-3">
                            {{ __('LineRegisterテーブル、customer_id書き換え') }}
                        </a>
                    </div> --}}
                    
                @endif
                {{-- ▲管理者メニュー --}}
            </div>
        </div>
    </div>
</x-app-layout>
