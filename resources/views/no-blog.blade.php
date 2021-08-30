<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ブログ無し一覧') }}
        </h2>
    </x-slot>

    <section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="lg:w-11/12 w-full mx-auto overflow-auto">
      <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
          <tr>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">No.</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ID</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">名前</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">担当者名</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">URL</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">has_blog?</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">has_eccube?</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">del_flag</th>
            <th class="w-10 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
          </tr>
        </thead>
        <tbody>
          </tr>
          <!-- 顧客情報表示 -->
          @foreach($customers as $customer)
          <tr>
            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ $customer->customer_id }}</td>
            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ $customer->support_id }}</td>
            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-lg text-gray-900">{{ $customer->customer_name }}</td>
            <!-- 担当者名 -->
            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">担当者名</td>
            <!-- URL -->
            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3"><a href="{{ $customer->customer_toppage_url }}">{{ $customer->customer_toppage_url }}</td>
            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ $customer->blog_flg }}</td>
            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ $customer->eccube_flg }}</td>
            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ $customer->del_flg }}</td>
          </tr>
          @endforeach
        </tbody>
        {{ $customers->links() }}
      </table>
    </div>

    <div class="flex pl-4 mt-4 lg:w-2/3 w-full mx-auto">
      <a class="text-indigo-500 inline-flex items-center md:mb-2 lg:mb-0">Learn More
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"></path>
        </svg>
      </a>
      <button class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">Button</button>
    </div>
  </div>
</section>
</x-app-layout>
