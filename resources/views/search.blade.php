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

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div id="div1" class="p-6 bg-white border-b border-gray-200 hidden">
                ここに検索候補を表示
            </div>
            <div id="div2" class="p-6 bg-white border-b border-gray-200 hidden">
                ここに検索候補を表示
            </div>
            <div id="div3" class="p-6 bg-white border-b border-gray-200 hidden">
                ここに検索候補を表示
            </div>
            <div id="div4" class="p-6 bg-white border-b border-gray-200 hidden">
                ここに検索候補を表示
            </div>
            <div id="div5" class="p-6 bg-white border-b border-gray-200 hidden">
                ここに検索候補を表示
            </div>
            <div id="div6" class="p-6 bg-white border-b border-gray-200 hidden">
                ここに検索候補を表示
            </div>
            <div id="div7" class="p-6 bg-white border-b border-gray-200 hidden">
                ここに検索候補を表示
            </div>
            <div id="div8" class="p-6 bg-white border-b border-gray-200 hidden">
                ここに検索候補を表示
            </div>
            <div id="div9" class="p-6 bg-white border-b border-gray-200 hidden">
                ここに検索候補を表示
            </div>
            <div id="div10" class="p-6 bg-white border-b border-gray-200 hidden">
                ここに検索候補を表示
            </div>
        </div>
    </div>
  </div>

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
                    <x-table-td :active="$count % 2 == 1">
                      <x-a-tag href="{{route('customer.show', [$customer->customer_id])}}">
                        {{ __($customer->support_id) }}
                      </x-a-tag>
                    </x-table-td>
                    <x-table-td :active="$count % 2 == 1">
                      <x-a-tag href="{{route('customer.show', [$customer->customer_id])}}">
                        {{ __($customer->customer_name) }}
                      </x-a-tag>
                    </x-table-td>
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
    // 
    function doSearch(list){
      console.log(list);
    }


    // ▽makeSearchList関数
    function makeSearchList(lists, allCustomers){
      var i = 1;
      for(const customer of allCustomers){
        for(const list of lists){
          if(customer.customer_id === list[0]){
            // クラスhiddenを除去、aタグ内に要素追加
            var div = document.getElementById("div" + i);
            div.classList.remove("hidden");
            div.innerHTML = "<a href='customer/" + list[0] + "'>" + "<span class='font-bold text-blue-500'" + ">" + list[1] + "</span><br>" + customer.support_id + " " + customer.customer_name + " " + customer.customer_toppage_url + "</a>";

            i++;
          }
        }
        if(i > 10){
          break;
        }
      }
    }
    // △makeSearchList
    
    // ▼searchCustomer関数
    // 10件まで、配列でcustomerテーブルからサポートID、顧客名、URL顧客と検索ワードの一致を見る
    function searchCustomer(input, allCustomers) {
      // 空欄時は検索しない
      if(input == "" | input == " " | input == "　"){
          return
        }

      var searchResults = [];

      var count = 0
      for(const customer of allCustomers){
        var supportId = [customer.customer_id, String(customer.support_id)];
        var customerName = [customer.customer_id, String(customer.customer_name)];
        var customerUrl = [customer.customer_id, String(customer.customer_toppage_url)];
                
        if(supportId[1].indexOf(String(input)) > -1){
          searchResults.push(supportId);
          count++;
        }
        else if(customerName[1].indexOf(String(input)) > -1) {
          searchResults.push(customerName);
          count++;
        }
        else if(customerUrl[1].indexOf(String(input)) > -1) {
          searchResults.push(customerUrl);
          count++;
        }

        if(count > 9){
          break;
        }
      }
      makeSearchList(searchResults, allCustomers);
    }
    // ▲searchCustomer関数

    var searchForm = document.getElementById('searchForm');

    searchForm.addEventListener('keyup', function(){
      var input = searchForm.value;

      for(let i = 1; i < 11; i++){
        var div = document.getElementById("div" + i);
        div.classList.add("hidden");
      }

      searchCustomer(input, allCustomers);
    });
  </script>
</x-app-layout>
