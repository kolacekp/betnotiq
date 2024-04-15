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
                        <form method="post" action="{{ route('bets.create') }}" class="mt-6 space-y-6"
                          x-data="{
                          rateControl:false,
                          fixedValue:false,
                          combinators: false,
                          combinatorsArray: new Array(20).fill(false),
                          combinatorsTypesArray: new Array(20).fill(1),
                          groupsNamesArray: ['Robovo členství', 'Členství All in', 'Cesta k milionu', 'Mistrovství Světa', 'Live aréna', 'Nevyplněno', 'Nevyplněno', 'Nevyplněno'],
                          groupsArray: new Array(8).fill(false),
                          groupsAll: false,
                        }">
                            @csrf
                            @method('post')

                             <div>
                                 <x-input-label for="url" :value="__('bets.group')" />
                                 <div class="flex flex-col gap-1 mt-2">
                                     @for ($i = 0; $i < 8; $i++)
                                         <div>
                                             <x-checkbox-input id="group_{{$i}}" name="groups[{{$i}}]" x-model="groupsArray[{{$i}}]"/>
                                            <label class="font-medium text-sm text-gray-700 ml-2" x-text="groupsNamesArray[{{$i}}]">
                                            </label>
                                         </div>
                                     @endfor
                                     <div>
                                         <x-checkbox-input id="groupsAll" name="groupsAll" x-model="groupsAll" @click="groupsArray = groupsArray.map(() => !groupsAll)"/>
                                         <label class="font-medium text-sm text-gray-700 ml-2">
                                             Vše
                                         </label>
                                     </div>
                                 </div>
                            </div>

                            <div>
                                <x-input-label for="url" :value="__('bets.url_form')" />
                                <x-text-input id="url" name="url" type="url" class="mt-1 block w-full" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('url')" />
                            </div>

                            <div>
                                <x-input-label for="value" :value="__('bets.value')" />
                                <x-text-input id="value" name="value" type="number" step="0.01" :max="$isAdmin? null : 5" class="mt-1 block w-full" x-bind:disabled="(fixedValue || combinators) ? true : false"  />
                                <x-input-error class="mt-2" :messages="$errors->get('value')" />
                            </div>

                            <div>
                                <x-input-label for="rate_control" :value="__('bets.rate_control')" />
                                <x-checkbox-input id="rate_control" name="rate_control" class="mt-1" x-model="rateControl"/>
                                <x-input-error class="mt-2" :messages="$errors->get('rate_control')" />
                            </div>

                            <div x-show="rateControl">
                                <x-input-label for="rate_control_value" :value="__('bets.rate_control_value')" />
                                <x-text-input id="rate_control_value" name="rate_control_value" type="number" value="0" step="0.01" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('rate_control_value')" />
                            </div>

                            <div>
                                <x-input-label for="fixed_value" :value="__('bets.fixed_value')" />
                                <x-checkbox-input id="fixed_value" name="fixed_value" class="mt-1" x-model="fixedValue"/>
                                <x-input-error class="mt-2" :messages="$errors->get('fixed_value')" />
                            </div>

                            <div x-show="fixedValue">
                                <x-input-label for="fixed_value_value" :value="__('bets.fixed_value_value')" />
                                <x-text-input id="fixed_value_value" name="fixed_value_value" type="number" value="0" :max="$isAdmin? null : 5000"  class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('fixed_value_value')" />
                            </div>

                            <div>
                                <x-input-label for="combinators" :value="__('bets.combinators')" />
                                <x-checkbox-input id="combinators" name="combinators" class="mt-1" x-model="combinators"/>
                                <x-input-error class="mt-2" :messages="$errors->get('combinators')" />
                            </div>

                            <div class="flex flex-col gap-2" x-show="combinators">
                                @for ($i = 0; $i < 20; $i++)
                                    <div class="flex flex-row gap-4 items-center">
                                        <div class="w-24 py-3 flex items-center">
                                            <x-checkbox-input id="aku_{{$i}}" name="aku_indexes[{{$i}}]" :value="$i+1" x-model="combinatorsArray[{{$i}}]" />
                                            <label class="font-medium text-sm text-gray-700 ml-2">
                                                AKU {{$i + 1}}
                                            </label>
                                        </div>
                                        <div class="w-64 flex items-center" x-show="combinatorsArray[{{$i}}]">
                                            <div class="flex flex-row gap-4">
                                                <div>
                                                    <x-radio-input id="aku_{{$i}}_type_percent" name="aku_types[{{$i}}]" value="1" x-model="combinatorsTypesArray[{{$i}}]" x-bind:disabled="!combinatorsArray[{{$i}}]" />
                                                    <label class="font-medium text-sm text-gray-700 ml-1">{{__('bets.combi_percent')}}</label>
                                                </div>
                                                <div>
                                                    <x-radio-input id="aku_{{$i}}_type_value" name="aku_types[{{$i}}]" value="0" x-model="combinatorsTypesArray[{{$i}}]" x-bind:disabled="!combinatorsArray[{{$i}}]" />
                                                    <label class="font-medium text-sm text-gray-700 ml-1">{{__('bets.combi_bet')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1" x-show="combinatorsArray[{{$i}}] && parseInt(combinatorsTypesArray[{{$i}}]) === 0">
                                            <x-text-input
                                                class="w-full disabled:opacity-50"
                                                id="aku_value_{{$i}}"
                                                name="aku_values[{{$i}}]"
                                                type="number"
                                                :max="$isAdmin? null : 500"
                                                x-bind:disabled="!combinatorsArray[{{$i}}]"
                                            />
                                        </div>
                                        <div class="flex-1" x-show="combinatorsArray[{{$i}}] && parseInt(combinatorsTypesArray[{{$i}}]) === 1">
                                            <x-text-input
                                                class="w-full disabled:opacity-50"
                                                id="aku_percent_{{$i}}"
                                                name="aku_percents[{{$i}}]"
                                                type="number"
                                                step="0.01"
                                                min="0.01"
                                                :max="$isAdmin? null : 0.5"
                                                x-bind:disabled="!combinatorsArray[{{$i}}]"
                                            />
                                        </div>
                                    </div>
                                @endfor

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
