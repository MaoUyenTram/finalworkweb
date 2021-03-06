<x-app-layout>
    <x-slot name="header">

    </x-slot>
    @if(Session::has('succes'))
        <div class="alert alert-info">{{ Session::get('succes') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border-t border-b border-red-500 text-red-700 px-4 py-3 text-center" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(Session::has('error'))
        <div class="bg-red-100 border-t border-b border-red-500 text-red-700 px-4 py-3 text-center"
             role="alert">{{ Session::get('error') }}</div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h1 class=" text-center">Games</h1>
                <div class="flex flex-inline ">
                    <form class="w-full max-w-sm" method="post" action="{{route('games.store')}}">
                        @method('POST')
                        @csrf
                        <div class="flex items-center border-b border-teal-500 py-2">
                            <input
                                class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                                name="name" type="text" placeholder="JaneDoe#1" aria-label="name">
                            <button
                                class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                type="submit">
                                create game
                            </button>
                        </div>
                    </form>
                    <form class="w-full float-right max-w-sm" method="post" action="{{route('join')}}">
                        @method('POST')
                        @csrf
                        <div class="flex float-right items-center border-b border-teal-500 py-2">
                            <input
                                class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none float-right"
                                name="id" type="text" placeholder="id" aria-label="name">
                            <button
                                class="float-right flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                type="submit">
                                join
                            </button>
                        </div>
                    </form>
                </div>

                <table class="w-full text-right table-auto">
                    <tbody>
                    @foreach($games as $game)
                        <tr class=" px-4 py-2">
                            <td>{{$game->name}}</td>
                            <td class="inline-flex w-full max-w-sm">
                                <form class="w-full max-w-lg" method="post" action="{{route('start',$game->id)}}">
                                    @method('POST')
                                    @csrf
                                    <button
                                        class="flex-shrink-0 bg-green-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                        type="submit">start game
                                    </button>
                                </form>
                                <form class="w-full max-w-sm" method="post" action="{{route('createpiles')}}">
                                    @method('POST')
                                    @csrf
                                    <input type="hidden" value="{{$game->id}}" name="id"/>
                                    <button
                                        class="flex-shrink-0 bg-blue-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                        type="submit">edit
                                    </button>
                                </form>
                                <form class="w-full max-w-sm" method="post"
                                      action="{{route('games.destroy',$game->id)}}">
                                    @method('DELETE')
                                    @csrf
                                    <button
                                        class="flex-shrink-0 bg-red-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                        type="submit">remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
