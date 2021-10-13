<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('corsin') }}
        </h2>
    </x-slot>

<section class="text-gray-600 body-font overflow-x-scroll">
  <div class="xl:container px-5 py-24 mx-auto">
    <div class="w-full mx-auto min-w-min">
      <table class="table-auto w-full text-left whitespace-no-wrap">
        {{-- テーブルヘッダー --}}
        <thead>
          <tr>
            <x-table-th>{{ __('No.') }}</x-table-th>
            <x-table-th>
              @sortablelink('customer.support_id', 'サポートID')
              {{ __('') }}
            </x-table-th>
            <x-table-th>
              @sortablelink('customer.customer_name', '顧客名')
              {{ __('') }}
            </x-table-th>
            <x-table-th>{{ __('URL') }}</x-table-th>
            <x-table-th>
              @sortablelink('line_register.line_flg', 'LINE登録')
              {{ __('') }}
            </x-table-th>
            <x-table-th>
              @sortablelink('long_diff.difference_flg', '差分')
              {{ __('') }}</x-table-th>
            <x-table-th>
              @sortablelink('long_diff.time_stamp_dif_long', '最終更新日')
              {{ __('') }}
            </x-table-th>
          </tr>
        </thead>

        {{-- 配色が交互になるようカウント --}}
        <?php $count = 1; ?>
        <!-- 顧客情報表示 -->
        @foreach($customerPages as $customerPage)
          <tbody>
            <tr>
              <x-table-td :active="$count % 2 == 1">{{ __($count) }}</x-table-td>
              <x-table-td :active="$count % 2 == 1" class="py-2 px-4 rounded hover:bg-gray-500 hover:text-white hover:opacity-50">
                <x-a-tag href="{{route('customer.show', [$customerPage->customer->customer_id])}}">
                  {{ __($customerPage->customer->support_id) }}
                </x-a-tag>
              </x-table-td>
              <x-table-td :active="$count % 2 == 1" class="font-bold py-2 px-4 rounded hover:bg-gray-500 hover:text-white hover:opacity-50">
                <x-a-tag href="{{route('customer.show', [$customerPage->customer->customer_id])}}">
                  {{ __($customerPage->customer->customer_name) }}
                </x-a-tag>
              </x-table-td>
              <x-table-td :active="$count % 2 == 1" class="">
                <x-link :url="$customerPage->customer->customer_toppage_url" class="" >
                  {{ __("開く") }}
                </x-link>
              </x-table-td>

              {{-- ▼JS編集用customer_id --}}
              <div class="hidden">{{ $customerPage->customer->customer_id }}</div>
              {{-- LINE登録有無 --}}
              {{-- ラインフラッグ代入（JSの判定用） --}}
              @if($customerPage->line_register != null)
                @if($lineFlg = $customerPage->line_register->line_flg == 1)
                  <x-table-td :active="$count % 2 == 1">
                    <x-span :registered="true" class="" id="{{$customerPage->customer->support_id}}">
                      {{ __('登録済み') }}
                    </x-span>
                  </x-table-td>
                @else
                  <x-table-td :active="$count % 2 == 1">
                    <x-span :registered="false" class="" id="{{$customerPage->customer->support_id}}">
                      {{ __('未登録') }}
                    </x-span>
                  </x-table-td>
                @endif
              @else
                <x-table-td :active="$count % 2 == 1">
                  <x-span :registered="false" class="" id="{{$customerPage->customer->support_id}}">
                    {{ __('未登録') }}
                  </x-span>
                </x-table-td>
              @endif
              {{-- ▲JS編集用customer_id --}}
              {{-- ▼差分の表示 --}}
              @if($customerPage->long_diff->difference_flg == 1)
                <x-table-td :active="$count % 2 == 1">
                  <x-sabun-a href="{{route('customer.show', [$customerPage->customer->customer_id])}}" :haveDifference="true">
                    {{ __('差分あり') }}
                  </x-sabun-a>
                </x-table-td>
              @else
                <x-table-td :active="$count % 2 == 1">
                  <x-sabun-a  :haveDifference="false" class="">
                    {{ __('差分なし') }}
                  </x-sabun-a>
                </x-table-td>
              @endif
              {{-- ▲ --}}

              {{-- 差分がある場合は時間表示 --}}
              @if($customerPage->long_diff->time_stamp_dif_long != '0000-00-00 00:00:00' || $customerPage->long_diff->time_stamp_dif_long != '0000-00-00 00:00:00')
                <x-table-td :active="$count % 2 == 1">{{ __(\Carbon\Carbon::parse($customerPage->long_diff->time_stamp_dif_long)->diffForHumans()) }}</x-table-td>
              @else
                <x-table-td :active="$count % 2 == 1">{{ __('-') }}</x-table-td>
              @endif
              {{-- ▲ --}}
            </tr>
          </tbody>
          <?php $count += 1; ?>
        @endforeach
        <div class="container p-2">
          {!! $customerPages->appends(\Request::except('page'))->render() !!}
        </div>
      </table>
      <div class="container p-2">
        {!! $customerPages->appends(\Request::except('page'))->render() !!}
      </div>
    </div>

  </div>
</section>
</x-app-layout>
