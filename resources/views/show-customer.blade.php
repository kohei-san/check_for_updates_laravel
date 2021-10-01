<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('顧客情報詳細表示') }}
        </h2>
    </x-slot>

    <section class="text-gray-600 body-font">
    <div class="xl:container px-5 py-10 mx-auto">
        <section class="text-gray-600 body-font">
            <div class="container px-5 mb-5 mx-auto flex items-center md:flex-row flex-col">
              <div class="flex flex-col md:pr-10 md:mb-0 mb-6 pr-0 w-full md:w-auto md:text-left text-center">
                <h2 class="text-s text-blue-800 tracking-widest font-medium title-font mb-1">{{ $customerPages[0]->customer->support_id }}</h2>
                <h1 class="md:text-3xl text-2xl font-medium font-mono text-gray-900">{{ $customerPages[0]->customer->customer_name }}</h1>
              </div>
              <div class="flex md:ml-auto md:mr-10 mx-auto items-center flex-shrink-0 space-x-4" id="show_customer">
                @if($customerPages[0]->customer->line_register != null)
                    @if($customerPages[0]->customer->line_register->line_flg == 1)
                        <x-span :registered="true" class="" id="{{$customerPages[0]->customer->support_id}}">
                            {{ __('登録済み') }}
                        </x-span>
                    @else
                        <x-span :registered="false" class="" id="{{$customerPages[0]->customer->support_id}}">
                            {{ __('未登録') }}
                        </x-span>
                    @endif
                @else
                    <x-span :registered="false" class="" id="{{$customerPages[0]->customer->support_id}}">
                        {{ __('未登録') }}
                    </x-span>
                @endif
              </div>
            </div>
        </section>
                
        <div class="w-full mx-auto overflow-auto">
        <table class="table-auto border-separate border w-full text-left whitespace-no-wrap">
            <thead>
            <tr>
                <x-table-th>{{ __('No.') }}</x-table-th>
                {{-- <x-table-th>{{ __('サポートID') }}</x-table-th>
                <x-table-th>{{ __('顧客名') }}</x-table-th> 
                --}}
                <x-table-th>{{ __('URL') }}</x-table-th>
                <x-table-th>{{ __('前回ファイル') }}</x-table-th>
                <x-table-th>{{ __('最新ファイル') }}</x-table-th>
                <x-table-th>{{ __('差分あり') }}</x-table-th>
                <x-table-th>{{ __('ファイル取得日') }}</x-table-th>
            </tr>
            </thead>
            <!-- 顧客情報表示 -->
            
            @foreach($customerPages as $customerPage)
                <tbody>
                    <tr>
                        <x-table-td :active="$loop->iteration % 2 == 1">{{ __($loop->iteration) }}</x-table-td>

                        <x-table-td :active="$loop->iteration % 2 == 1">
                            <a href="{{ $customerPage->page_url }}">    
                                {{ __($customerPage->page_url) }}
                            </a>
                        </x-table-td>
                        {{-- ▼前回、前々回の表示 --}}
                        @php
                            $newhtmlPath = $htmlPreDirs['full'][0] . $customerPage->page_id . "." . "html";
                            $pre1htmlPath = $htmlPreDirs['full'][1] . $customerPage->page_id . "." . "html";
                            $pre2htmlPath = $htmlPreDirs['full'][2] . $customerPage->page_id . "." . "html";
                        @endphp

                        @if( Illuminate\Support\Facades\File::exists(app_path($newhtmlPath)))

                            @if( Illuminate\Support\Facades\File::exists(app_path($pre1htmlPath)))
                                <x-table-td :active="$loop->iteration % 2 == 1">
                                    <a href="/prehtml/{{ $htmlPreDirs['filename'][1] }}/{{ $customerPage->page_id }}"class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" >
                                        {{ __("開く") }}
                                    </a>
                                </x-table-td>
                            @else
                                <x-table-td :active="$loop->iteration % 2 == 1">

                                </x-table-td>
                            @endif
                            <x-table-td :active="$loop->iteration % 2 == 1">
                                <a href="/prehtml/{{ $htmlPreDirs['filename'][0] }}/{{ $customerPage->page_id }}"class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" >
                                    {{ __("開く") }}
                                </a>
                            </x-table-td>
                        @elseif( Illuminate\Support\Facades\File::exists(app_path($pre1htmlPath)))

                            @if( Illuminate\Support\Facades\File::exists(app_path($pre2htmlPath)))
                                <x-table-td :active="$loop->iteration % 2 == 1">
                                    <a href="/prehtml/{{ $htmlPreDirs['filename'][2] }}/{{ $customerPage->page_id }}"class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" >
                                        {{ __("開く") }}
                                    </a>
                                </x-table-td>
                            @else
                                <x-table-td :active="$loop->iteration % 2 == 1">

                                </x-table-td>
                            @endif
                            <x-table-td :active="$loop->iteration % 2 == 1">
                                <a href="/prehtml/{{ $htmlPreDirs['filename'][1] }}/{{ $customerPage->page_id }}"class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" >
                                    {{ __("開く") }}
                                </a>
                            </x-table-td>
                        @else

                            <x-table-td :active="$loop->iteration % 2 == 1">

                            </x-table-td>

                            <x-table-td :active="$loop->iteration % 2 == 1">

                            </x-table-td>
                        
                        @endif
                        {{-- ▲ --}}

                        {{-- ▼差分の表示 --}}
                        @if($customerPage->short_diff->difference_flg == 1)
                        <x-table-td :active="$loop->iteration % 2 == 1">
                            @php
                            $htmlfile = app_path($htmlShortDifDir . $customerPage->page_id . "." . "html");
                            @endphp

                            @if( Illuminate\Support\Facades\File::exists($htmlfile))
                            {{-- <x-sabun-a href="#" :haveDifference="true" onclick="window.open({{$htmlfile}}, 'chrome','width=1280,height=720,noopener'); return false;" class=""> --}}
                            <x-sabun-a href="/different/short/{{$customerPage->page_id}}" :haveDifference="true">
                                {{ __('差分あり') }}
                            </x-sabun-a>
                            @else
                            <x-sabun-a href="#!" :haveDifference="true" class="">
                                {{ __('差分あり(-)') }}
                            </x-sabun-a>
                            @endif
                        </x-table-td>
                        @else
                        <x-table-td :active="$loop->iteration % 2 == 1">
                            <x-sabun-a :haveDifference="false" class="">
                            {{ __('差分なし') }}
                            </x-sabun-a>
                        </x-table-td>
                        @endif
                        {{-- ▲ --}}

                        <x-table-td :active="$loop->iteration % 2 == 1">{{ __('') }}</x-table-td>
                    </tr>
                </tbody>
            @endforeach
        </table>
        </div>

    </div>
    </section>
</x-app-layout>
