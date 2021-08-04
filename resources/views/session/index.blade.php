<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{asset('js/ingame.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>


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
    <div id="hiddendata" hidden>
        <p id="gameid">{{$game->id}}</p>
    </div>
    <div class="bg-white h-screen w-full flex">
        <div class="w-2/12 py-1 px-1 ">
            @if(Auth::id() == $game->UserId)
                who are the pile owners?<br>
                <table>
                    @foreach($owners as $owner)
                        <tr class="flex w-full">
                            <td>
                                <p>{{$owner->owner}}</p>
                            </td>
                            <td>
                                <select name="owners" class="owners">
                                    <option value="nobody#0" selected>nobody </option>
                                    <option value="{{Auth::user()->name}}">{{Auth::user()->name}}</option>
                                    @foreach($friends as $friend)
                                        @if($friend->state == 1)
                                            <option value="{{$friend->name}}">
                                                {{$friend->name}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <button
                    class="flex-shrink-0 bg-green-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                    id="changO"
                    type="button">save
                </button>
                <hr>
            @endif
            @foreach($piles as $pile)
                <div class="allobj ">
                    <button class="py-1 px-1 w-full button " id="{{$pile->owner}}"
                            name="{{$pile->name}}">{{$pile->name}}</button>
                    <p class="pileid" hidden>{{$pile->id}}</p>
                    <p class="piletype" hidden>{{$pile->type}}</p>
                    <p class="pilevis" hidden>{{$pile->visibility}}</p>
                    <p class="private" hidden>{{$pile->private}}</p>
                    <div id="{{$pile->name}}box" class="itembox">
                    </div>
                </div>
            @endforeach
            <button
                class="flex-shrink-0 bg-green-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                id="rolldice">roll dice
            </button>
        </div>
        <div id="board"
             class=" py-1 px-1 bg-local h-full bg-no-repeat bg-contain w-8/12 relative"
             style="background-image:url({{asset("uploads/".$game->board.'.jpg')}});min-width: 1000px;min-height: 1000px">
        </div>
        <div class="py-3 w-2/12 h-full relative ">
            <div id="chat" class="h-10/12 overflow-auto"  >
                <h3>Chat</h3>
                <hr>
            </div>
            <div class="absolute h-1/12 bottom-15 w-full">
                <textarea id="msg" type="text" placeholder="send a message"></textarea>
                <br>
                <button
                    class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                    id="sendmsg">send
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
