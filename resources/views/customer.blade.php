<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('corsin') }}
        </h2>
    </x-slot>

    <section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="lg:w-11/12 w-full mx-auto overflow-auto">
      <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
          <tr>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">No.</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">サポートID</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">顧客名</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">担当者名</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">URL</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">〇〇</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">〇〇</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ファイル取得日時</th>
            <th class="w-10 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
          </tr>
        </thead>
        <!-- 顧客情報表示 -->
        
        @foreach($customers as $customer)
          <tbody>
            </tr>
            <tr>
              <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ $loop->iteration }}</td>
              <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3"><a href="{{route('customer.show', [$customer->customer_id])}}">{{ $customer->support_id }}</a></td>
              <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-lg text-gray-900"><a href="{{route('customer.show', [$customer->customer_id])}}">{{ $customer->customer_name }}</a></td>
              <!-- 担当者名 -->
              <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">担当者名</td>
              <!-- URL -->
              <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3"><a href="{{ $customer->customer_toppage_url }}">{{ $customer->customer_toppage_url }}</td>
              <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3"></td>
              <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3"></td>
              @php
                $page_htmls = $customer->page_html;
              @endphp
              @foreach($page_htmls as $page_html)
                <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ $page_html->time_stamp_htmlsrc }}</td>
                @break
              @endforeach
            </tr>
          </tbody>
        @endforeach
        <div class="container p-2">
          {{ $customers->links() }}
        </div>
      </table>
      <div class="container p-2">
        {{ $customers->links() }}
      </div>
    </div>

  </div>
</section>
</x-app-layout>
