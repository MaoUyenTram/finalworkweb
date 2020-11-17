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
        <div class="bg-red-100 border-t border-b border-red-500 text-red-700 px-4 py-3 text-center" role="alert">{{ Session::get('error') }}</div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h1 class=" text-center">Games</h1>
                <form class="w-full max-w-sm" method="post" action="{{'games.store'}}">
                    @method('POST')
                    @csrf
                    <div class="flex items-center border-b border-teal-500 py-2">
                        <input class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" placeholder="JaneDoe#1" aria-label="name">
                        <button class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded" type="button">
                            create game
                        </button>
                    </div>
                </form>

                <table class="w-full text-right table-auto">
                    <tbody>
                    @foreach($games as $game)
                        <tr class=" px-4 py-2">
                            <td>{{$game->name}}</td>
                            <td>
                                <form class="w-full max-w-sm" method="delete" action="{{'games.destroy',$game->id}}">
                                    @method('DELETE')
                                    @csrf
                                    <button class="flex-shrink-0 bg-red-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded" type="submit">remove</button>
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
