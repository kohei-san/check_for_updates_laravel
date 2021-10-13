@php
    use Illuminate\Support\Facades\File;  
    use \Carbon\Carbon;
    if($customerPages[0]->customer->active_call != null){
        $updated = $customerPages[0]->customer->active_call->updated_at;
        $recent_activecall = Carbon::parse($customerPages[0]->customer->active_call->updated_at)->addDays(1);
        // $recent_activecall = Carbon::parse($customerPages[0]->customer->active_call->updated_at)->addHours(1);
    }
    if($customerPages[0]->customer->review != null){
        $review_created = $customerPages[0]->customer->review->created_at;
        $recent_review = Carbon::parse($customerPages[0]->customer->review->created_at)->addMonth(3);
    }

    // ---------変数定義---------
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('顧客情報詳細表示') }}
        </h2>
    </x-slot>

    <section class="text-gray-600 body-font">
    <div class="xl:container px-5 py-10 mx-auto">
        {{-- <section class="text-gray-600 body-font"> --}}
            <div class="container px-5 mb-5 mx-auto flex items-center md:flex-row flex-col">
              <div class="flex flex-col md:pr-10 md:mb-0 mb-6 pr-0 w-full md:w-auto md:text-left text-center">
                <h2 class="text-s text-blue-800 tracking-widest font-medium title-font mb-1">{{ $customerPages[0]->customer->support_id }}</h2>
                <h1 class="md:text-3xl text-2xl font-medium font-mono text-gray-900">{{ $customerPages[0]->customer->customer_name }}</h1>
                {{-- デバッグ用 --}}
                {{-- <h1 class="md:text-3xl text-2xl font-medium font-mono text-gray-900">{{ $customerPages[0]->customer->customer_id }}</h1> --}}
                {{--  --}}
              </div>
              <div class="flex md:ml-auto md:mr-10 mx-auto items-center flex-shrink-0 space-x-4" id="show_customer">
                <table class="table-auto">
                    <thead>
                      <tr>
                        <th class="px-4 py-2">口コミ</th>
                        <th class="px-4 py-2">アクティブコール</th>
                        <th class="px-4 py-2">ライン登録</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        {{-- ▼▼▼口コミ登録▼▼▼ --}}
                        @if ($customerPages[0]->customer->review != null)
                            @if ($customerPages[0]->customer->review->review_flg == 1 && Carbon::now()->greaterThan($recent_review))
                                <td class="px-4 py-2">
                                    <x-review :registered="true" data-registered=1 data-customerid="{{$customerPages[0]->customer->customer_id}}" class="border border-blue-500 hover:bg-blue-500 hover:text-white" id="review">
                                        {{ __( Carbon::parse($review_created)->diffForHumans() ) }}
                                    </x-review>
                                </td>
                            @elseif($customerPages[0]->customer->review->review_flg == 1)
                                <td class="px-4 py-2">
                                    <x-review :registered="true" data-registered=1 data-customerid="{{$customerPages[0]->customer->customer_id}}" class="bg-blue-500 text-white hover:bg-green-500" id="review">
                                        {{ __( Carbon::parse($review_created)->diffForHumans() ) }}
                                    </x-review>
                                </td>
                            @else
                                <td class="px-4 py-2">
                                    <x-review :registered="false" data-registered=0 data-customerid="{{$customerPages[0]->customer->customer_id}}" class="" id="review">
                                        {{ __('未登録') }}
                                    </x-review>
                                </td>
                            @endif
                        @else
                            <td class="px-4 py-2">
                                <x-review :registered="false" data-registered=0 data-customerid="{{$customerPages[0]->customer->customer_id}}" class="" id="review">
                                    {{ __('未登録') }}
                                </x-review>
                            </td>
                        @endif
                        {{-- ▲▲▲口コミ登録▲▲▲ --}}

                        {{-- ▼▼▼アクティブコール登録▼▼▼ --}}
                        @if ($customerPages[0]->customer->active_call != null)
                            @if ($customerPages[0]->customer->active_call->active_call_flg == 1 && Carbon::now()->greaterThan($recent_activecall) )
                                <td class="px-4 py-2 text-center">
                                    <x-activecall :registered="true" class="border border-yellow-500 hover:text-white hover:bg-yellow-500" data-registered=1 id="activecall" data-customerid='{{$customerPages[0]->customer->customer_id}}'>
                                        {{ __( Carbon::parse($updated)->diffForHumans() ) }}
                                    </x-activecall>
                                </td> 
                            @elseif( $customerPages[0]->customer->active_call->active_call_flg == 1 )
                                <td class="px-4 py-2 text-center">
                                    <x-activecall :registered="true" class="bg-yellow-500 text-white hover:bg-green-500" data-registered=1 id="activecall" data-customerid='{{$customerPages[0]->customer->customer_id}}'>
                                        {{ __( Carbon::parse($updated)->diffForHumans() ) }}
                                    </x-activecall>
                                </td> 
                            @else
                                <td class="px-4 py-2">
                                    <x-activecall :registered="false" class="" data-registered=0 id="activecall" data-customerid='{{$customerPages[0]->customer->customer_id}}'>
                                        {{ __('未登録') }}
                                    </x-activecall>
                                </td> 
                            @endif
                        @else
                        <td class="px-4 py-2">
                            <x-activecall :registered="false" class="" data-registered=0 id="activecall" data-customerid='{{$customerPages[0]->customer->customer_id}}'>
                                {{ __('未登録') }}
                            </x-activecall>
                        </td>
                        @endif
                        {{-- ▲▲▲アクティブコール登録▲▲▲ --}}

                        {{-- ▼▼▼ライン登録▼▼▼ --}}
                        <td class="px-4 py-2">                
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
                            </td>
                        </tr>
                    </tbody>
                  </table>

              </div>
            </div>
        {{-- </section> --}}
                
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
                            <x-a-tag href="{{ $customerPage->page_url }}">    
                                {{ __($customerPage->page_url) }}
                            </x-a-tag>
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
                                    <a href="{{ $href_http }}/prehtml/{{ $htmlPreDirs['filename'][1] }}/{{ $customerPage->page_id }}" target="_blank" rel="noopener" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" >
                                        {{ __("開く") }}
                                    </a>
                                </x-table-td>
                            @else
                                <x-table-td :active="$loop->iteration % 2 == 1">

                                </x-table-td>
                            @endif
                            <x-table-td :active="$loop->iteration % 2 == 1">
                                <a href="{{ $href_http }}/prehtml/{{ $htmlPreDirs['filename'][0] }}/{{ $customerPage->page_id }}" target="_blank" rel="noopener" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" >
                                    {{ __("開く") }}
                                </a>
                            </x-table-td>
                        @elseif( Illuminate\Support\Facades\File::exists(app_path($pre1htmlPath)))
                            @if( Illuminate\Support\Facades\File::exists(app_path($pre2htmlPath)))
                                <x-table-td :active="$loop->iteration % 2 == 1">
                                    <a href="{{ $href_http }}/prehtml/{{ $htmlPreDirs['filename'][2] }}/{{ $customerPage->page_id }}" target="_blank" rel="noopener" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" >
                                        {{ __("開く") }}
                                    </a>
                                </x-table-td>
                            @else
                                <x-table-td :active="$loop->iteration % 2 == 1">

                                </x-table-td>
                            @endif
                            <x-table-td :active="$loop->iteration % 2 == 1">
                                <a href="{{ $href_http }}/prehtml/{{ $htmlPreDirs['filename'][1] }}/{{ $customerPage->page_id }}" target="_blank" rel="noopener" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" >
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
                            <x-sabun-a href="/different/short/{{$customerPage->page_id}}" target="_blank" rel="noopener" :haveDifference="true">
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
