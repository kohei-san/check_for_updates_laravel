<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight align-middle">
          {{ __('') }}
      </h2>
      <form action="{{ route('search.result') }}" method="POST" >
        <div class="flex">
          @csrf
          <div>
            <input id="searchForm" type="text" name="searchword" value="{{ old('searchword') }}" class="relative rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

          </div>
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">検索</button>
        </div>
      </form>
    </div>  
  </x-slot>

  {{-- 検索結果一覧表示 --}}
  @if(Request::is('search/result'))
  
    @if(count($customers) > 0)
      <section class="text-gray-600 body-font">
        <div class="xl:container px-5 py-24 mx-auto">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight align-middle">検索結果</h2>
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
                  </tr>
                </tbody>
                <?php $count += 1; ?>
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
    @endif

    @if(count($customers) == 0)
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    一致する内容は見つかりませんでした。
                </div>
            </div>
        </div>
      </div>
    @endif
  @endif

  <script>
    const allCustomers = @json($allCustomers);

    // search-list
    function makeSearchList(lists){
      console.log(lists);
    }
    
    // 10件まで、配列で顧客と検索ワードの一致を見る
    function searchCustomer(input, allCustomers) {
      var searchResults = [];

      var count = 0
      for(const customer of allCustomers){
        // ▼サポートID、顧客名、URLの部分一致確認
        var supportId = String(customer.support_id);
        var customerName = String(customer.customer_name);
        var customerUrl = String(customer.customer_toppage_url);
                
        if(supportId.indexOf(String(input)) > -1){
          searchResults.push(supportId);
          count++;
        }
        else if(customerName.indexOf(String(input)) > -1) {
          searchResults.push(customerName);
          count++;
        }
        else if(customerUrl.indexOf(String(input)) > -1) {
          searchResults.push(customerUrl);
          count++;
        }
        // ▲サポートID、顧客名、URLの部分一致確認

        if(count > 9){
          break;
        }
      }
      makeSearchList(searchResults);
    }

    var searchForm = document.getElementById('searchForm');

    searchForm.addEventListener('keyup', function(){
      var input = searchForm.value;
      searchCustomer(input, allCustomers);
    });
  </script>
</x-app-layout>
