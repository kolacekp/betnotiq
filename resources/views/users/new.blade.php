<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('users.add_title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <form method="post" action="{{ route('users.create') }}" class="mt-6 space-y-6" autocomplete="off">
                            @csrf
                            @method('post')

                            <div>
                                <x-input-label for="name" :value="__('users.name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('users.email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('url')" />
                            </div>

                            <div>
                                <x-input-label for="role" :value="__('users.role')" />
                                <select
                                    name="role"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option selected value="{{\App\Models\Enums\Roles::User}}">{{\App\Models\Enums\RoleMapper::map(\App\Models\Enums\Roles::User)}}</option>
                                    <option value="{{\App\Models\Enums\Roles::Manager}}">{{\App\Models\Enums\RoleMapper::map(\App\Models\Enums\Roles::Manager)}}</option>
                                    <option value="{{\App\Models\Enums\Roles::Admin}}">{{\App\Models\Enums\RoleMapper::map(\App\Models\Enums\Roles::Admin)}}</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('users.password')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-2">
                                <x-primary-button>{{ __('users.save') }}</x-primary-button>
                                <a href="{{ url()->previous() }}" type="button" class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                                    {{__('users.back')}}
                                </a>
                            </div>
                        </form>
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
