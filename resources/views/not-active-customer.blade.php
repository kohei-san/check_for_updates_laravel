<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('corsin') }}
        </h2>
    </x-slot>

<section class="text-gray-600 body-font">
  <div class="xl:container px-5 py-24 mx-auto">
    <div class="w-full mx-auto overflow-auto">
      <table class="table-auto w-full text-left whitespace-no-wrap">
        {{-- テーブルヘッダー --}}
        <thead>
          <tr>
            <x-table-th>{{ __('No.') }}</x-table-th>
            <x-table-th>
              @sortablelink('support_id', 'サポートID')
              {{ __('') }}
            </x-table-th>
            <x-table-th>
              @sortablelink('customer_name', '顧客名')
              {{ __('') }}
            </x-table-th>
            <x-table-th>{{ __('担当者名') }}</x-table-th>
            <x-table-th>{{ __('URL') }}</x-table-th>
            <x-table-th>{{ __('〇〇') }}</x-table-th>
            <x-table-th>{{ __('〇〇') }}</x-table-th>
            <x-table-th>{{ __('ファイル取得日') }}</x-table-th>
          </tr>
        </thead>

        {{-- 配色が交互になるようカウント --}}
        <?php $count = 1; ?>
        <!-- 顧客情報表示 -->
        @foreach($customers as $customer)
          <tbody>
            <tr>
              <x-table-td :active="$count % 2 == 1">{{ __($count) }}</x-table-td>
              <x-table-td :active="$count % 2 == 1"><a href="{{route('customer.show', [$customer->customer_id])}}">{{ __($customer->support_id) }}</a></x-table-td>
              <x-table-td :active="$count % 2 == 1"><a href="{{route('customer.show', [$customer->customer_id])}}">{{ __($customer->customer_name) }}</a></x-table-td>
              <x-table-td :active="$count % 2 == 1">{{ __('担当者名') }}</x-table-td>
              <x-table-td :active="$count % 2 == 1">
                <a href="{{ $customer->customer_toppage_url }}"class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" >
                  {{ __("開く") }}
                </a>
              </x-table-td>
              <x-table-td :active="$count % 2 == 1">{{ __('') }}</x-table-td>
              <x-table-td :active="$count % 2 == 1">{{ __('') }}</x-table-td>
              @php
                // page_htmlsの内容は配列のため、->では取得できない
                $page_htmls = $customer->page_html;
                // 下記配列で$loop->iterationが狂うので変数に格納
                $odd_even = $loop->iteration;
              @endphp
              @foreach($page_htmls as $page_html)
                <x-table-td :active="$count % 2 == 1">{{ __($page_html->time_stamp_htmlsrc) }}</x-table-td>
                @break
              @endforeach
            </tr>
          </tbody>
          <?php $count += 1; ?>
        @endforeach
      </table>
    </div>

  </div>
</section>
</x-app-layout>
