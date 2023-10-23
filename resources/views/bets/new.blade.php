<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('bets.add_title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <form method="post" action="{{ route('bets.create') }}" class="mt-6 space-y-6" x-data="{rateControl:false}">
                            @csrf
                            @method('post')

                            <div>
                                <x-input-label for="url" :value="__('bets.url')" />
                                <x-text-input id="url" name="url" type="text" class="mt-1 block w-full" required autofocus autocomplete="url" />
                                <x-input-error class="mt-2" :messages="$errors->get('url')" />
                            </div>

                            <div>
                                <x-input-label for="value" :value="__('bets.value')" />
                                <x-text-input id="value" name="value" type="number" step="0.01" max="5" class="mt-1 block w-full" required autocomplete="value" />
                                <x-input-error class="mt-2" :messages="$errors->get('value')" />
                            </div>

                            <div>
                                <x-input-label for="rate_control" :value="__('bets.rate_control')" />
                                <x-checkbox-input id="rate_control" name="rate_control" class="mt-1" x-model="rateControl"/>
                                <x-input-error class="mt-2" :messages="$errors->get('rate_control')" />
                            </div>

                            <div x-show="rateControl">
                                <x-input-label for="rate_control_value" :value="__('bets.rate_control_value')" />
                                <x-text-input id="rate_control_value" name="rate_control_value" type="number" value="0" class="mt-1 block w-full" required autocomplete="rate_control_value" />
                                <x-input-error class="mt-2" :messages="$errors->get('rate_control_value')" />
                            </div>

                            <div class="flex items-center gap-2">
                                <x-primary-button>{{ __('bets.save') }}</x-primary-button>
                                <a href="{{ url()->previous() }}" type="button" class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                                    {{__('bets.back')}}
                                </a>
                            </div>
                        </form>
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
