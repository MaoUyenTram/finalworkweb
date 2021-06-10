<x-app-layout>
    <x-slot name="header">

    </x-slot>

    @if(Session::has('succes'))
        <div class="bg-green-100 border-t border-b border-green-500 text-green-700 px-4 py-3 text-center"
             role="alert">{{ Session::get('succes') }}</div>
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
                <div class="float-left">
                    <form class="w-full max-w-sm" method="post" action="{{route('friends-and-bans.store')}}">
                        @method('POST')
                        @csrf
                        <div class="flex items-center border-b border-teal-500 py-2">
                            <input
                                class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                                name="name" type="text" placeholder="JaneDoe#1" aria-label="name">
                            <input type="hidden" aria-label="friend" name="friend" value="1">
                            <button
                                class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                type="submit">
                                Add
                            </button>
                        </div>
                    </form>
                    <table class="w-full text-right table-auto">
                        <thead>
                        <tr class="px-4 py-2">Friends</tr>
                        <tr class="px-4 py-2"></tr>
                        </thead>
                        <tbody>
                        @foreach($friends as $friend)
                            <tr class=" px-4 py-2">
                                <td>{{$friend->name}}</td>
                                <td>
                                    <form class="w-full max-w-sm" method="post"
                                          action="{{route('friends-and-bans.update',$friend->name)}}">
                                        <input type="hidden" name="name" value={{$friend->name}}>
                                        <input type="hidden" name="state" value=0>
                                        @method('PUT')
                                        @csrf
                                        <button
                                            class="flex-shrink-0 bg-blue-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                            type="submit">move to banlist
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form class="w-full max-w-sm" method="post"
                                          action="{{route('friends-and-bans.destroy',$friend->name)}}">
                                        <input type="hidden" name="name" value={{$friend->name}}>
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
                <div class="float-right">
                    <form class="w-full max-w-sm" method="post" action="{{route('friends-and-bans.store')}}">
                        @method('POST')
                        @csrf
                        <div class="flex items-center border-b border-teal-500 py-2">
                            <input
                                class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                                name="name" type="text" placeholder="JaneDoe#1" aria-label="name">
                            <input type="hidden" aria-label="friend" name="friend" value="0">
                            <button
                                class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                type="submit">
                                Ban
                            </button>
                        </div>
                    </form>
                    <table class="w-full text-right table-auto">
                        <thead>
                        <tr class=" px-4 py-2">bans</tr>
                        <tr class=" px-4 py-2"></tr>
                        </thead>
                        <tbody>
                        @foreach($bans as $ban)
                            <tr class=" px-4 py-2">
                                <td>{{$ban->name}}</td>
                                <td>
                                    <form class="w-full max-w-lg" method="post"
                                          action="{{route('friends-and-bans.update',$ban->name)}}">
                                        <input type="hidden" name="name" value={{$ban->name}}>
                                        <input type="hidden" name="state" value=1>
                                        @method('PUT')
                                        @csrf
                                        <button
                                            class="flex-shrink-0 bg-blue-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                            type="submit">move to friendlist
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form class="w-full max-w-lg" method="post"
                                          action="{{route('friends-and-bans.destroy',$ban->name)}}">
                                        <input type="hidden" name="name" value={{$ban->name}}>
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
    </div>
</x-app-layout>
