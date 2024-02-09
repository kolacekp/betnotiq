<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('bets.add_cashout_title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <form method="post" action="{{ route('bets.createCashout') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('post')

                            <div>
                                <x-input-label for="cashout_ticket" :value="__('bets.cashout_ticket')" />
                                <x-text-input id="cashout_ticket" name="cashout_ticket" class="mt-1 block w-full" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('cashout_ticket')" />
                            </div>

                            <div class="flex gap-2">
                                <div class="grow">
                                    <x-input-label for="cashout_reason" :value="__('bets.cashout_reason')" />
                                    <x-text-input id="cashout_reason" name="cashout_reason" class="mt-1 block w-full" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('cashout_reason')" />
                                </div>
                                <div class="flex flex-col justify-end">
                                    <button id="payout_ticket" class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        {{ __('bets.payout_ticket') }}
                                    </button>
                                    <script type="module">
                                        $("#payout_ticket").click(function (e){
                                            e.preventDefault();
                                            $("#cashout_reason").val('vyplatit_ticket')
                                        });
                                    </script>
                                </div>
                            </div>

                            <div>
                                <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="{{ asset('files/cashout-manual.pdf') }}" target="_blank">{{ __('bets.cashout_help') }}</a>
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
