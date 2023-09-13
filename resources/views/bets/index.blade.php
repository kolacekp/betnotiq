<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('bets.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <div class="flex flex-col gap-4">

                @if (session('status') !== null)
                    <div>
                        <div
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 5000)"
                            class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 gap-2"
                            role="alert"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('bets.'.session('status')) }}
                        </div>
                    </div>
                @endif

                <div>
                    <a href="{{ route('bets.new') }}" type="button" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('bets.add_bet') }}
                    </a>
                </div>

                <div>
                    {{$bets->links()}}
                </div>

                <div>
                    <div class="my-4 relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {!! __('bets.url') !!}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {!! __('bets.value') !!}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {!! __('bets.user_added') !!}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {!! __('bets.date_added') !!}
                                </th>
                                @if(Auth::user()->isAdmin())
                                    <th scope="col" class="px-6 py-3">
                                        {!! __('bets.actions') !!}
                                    </th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($bets as $bet)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="{{$bet->url}}" target="_blank">
                                            {{\Illuminate\Support\Str::limit($bet->url, 50, $end='...')}}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold">{{$bet->value}}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-medium">{{$bet->user->name}}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$bet->created_at->format('d.m.Y')}}<br/>
                                        {{$bet->created_at->format('H:i:s')}}
                                    </td>
                                    @if(Auth::user()->isAdmin())
                                        <td class="py-4">
                                            <form method="post" action="{{ route('bets.destroy') }}" class="p-6">
                                                @csrf
                                                @method('delete')

                                                <input type="hidden" name="id" value="{{$bet->id}}"/>
                                                <div class="inline-flex rounded-md shadow-sm" role="group">
                                                    <a href="{{ route('bets.edit', ['id' => $bet->id]) }}" data-tooltip-target="tooltip-edit" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                        </svg>
                                                    </a>
                                                    <div id="tooltip-edit" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                        {!! __('bets.edit_bet') !!}
                                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                                    </div>
                                                    <button data-tooltip-target="tooltip-delete" type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-md hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                    <div id="tooltip-delete" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                        {!! __('bets.delete_bet') !!}
                                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{$bets->links()}}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
