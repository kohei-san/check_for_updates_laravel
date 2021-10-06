<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        登録情報編集

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('user.update', Auth::user()) }}">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="block mt-1 w-full mb-3" type="text" name="name" :value="Auth::user()->name? : old('name')" required />
            </div>

            <!-- is_admin -->
            <x-label for="name" :value="__('管理者の場合は下記をチェック')" />

            <div>
                <x-input id="is_admin" class="appearance-none checked:bg-blue-600 checked:border-transparent" type="radio" name="is_admin" :value="1" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('user.index') }}">
                    {{ __('戻る') }}
                </a>

                <x-button class="ml-4">
                    {{ __('更新') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
