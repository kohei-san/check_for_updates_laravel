<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-flash-message status="session('status')" />
                    こんにちは{{ Auth::user()->name }}さん
                </div>
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

                    {{-- <div class="p-6 bg-white border-b border-gray-200">
                        <a href="{{ route('writecustomerid') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ml-3">
                            {{ __('LineRegisterテーブル、customer_id書き換え') }}
                        </a>
                    </div> --}}
                    

                @endif

                {{-- css bar --}}
                <div class="max-w-7xl mx-auto p-8">
                    <div class="text-center p-4">ブログ導入顧客(6200件)</div>
                    <div class="px-8">
                        <div class="overflow-hidden">
                            <div class="flex">
                                <div class="w-1/6 bg-gray-200 h-20 rounded-l-lg border-r-2"></div>
                                <div class="w-1/6 bg-gray-200 h-20 border-r-2 border-black">10月目標<br>2000顧客が更新</div>
                                <div class="w-1/6 bg-gray-200 h-20 border-r-2"></div>
                                <div class="w-1/6 bg-gray-200 h-20 flex">
                                    <span class="w-1/2 bg-gray-200 h-20 border-r-2 border-black">11月目標<br>4000件</span>
                                    <span class="w-1/2 bg-gray-200 h-20"></span>
                                </div>
                                <div class="w-1/6 bg-gray-200 h-20 border-r-2 border-black">80%<br>16期目標（12月）</div>
                                <div class="w-1/6 bg-gray-200 h-20 rounded-r-lg border-r-2"></div>
                            </div>
                            <div class="chart-bar w-full h-20 bg-yellow-300 rounded-lg z-10" style="transform: translate({{ '-'.$sabun['rate']}}%, -100%);"></div>
                            {{-- @dd($sabun) --}}
                        </div>
                    </div>
                </div>


                {{-- <div class="p-6 bg-white border-b border-gray-200">
                    <div class="progress-bar horizontal">
                        <div class="progress-track">
                            <div class="progress-fill">
                                <span>100%</span>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- css donuts --}}
                <div class="flex flex-wrap justify-center">
                    <div>
                        <div>メールアドレス保有</div>
                        <div class="donuts" style="background-image: radial-gradient(#f2f2f2 30%, transparent 31%), conic-gradient(#d5525f 0% 60%, #d9d9d9 60% 100%);">
                            60%
                        </div>
                    </div>
                    <div>
                        <div>ライン保有</div>
                        <div class="donuts" style="background-image: radial-gradient(#f2f2f2 30%, transparent 31%), conic-gradient(#d5525f 0% {{$linedata['rate']}}%, #d9d9d9 {{$linedata['rate']}}% 100%);">
                            {{$linedata['rate']}}%<br>{{$linedata['all']}}件
                        </div>
                    </div>

                    <div>
                        <div>10/1以降の電話接触</div>
                        <div class="donuts" style="background-image: radial-gradient(#f2f2f2 30%, transparent 31%), conic-gradient(#d5525f 0% 60%, #d9d9d9 60% 100%);">
                            60%
                        </div>
                    </div>
                </div>

                {{--  --}}
            

            </div>
        </div>
    </div>
</x-app-layout>
