<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{asset('js/ingame.js')}}"></script>


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
    @foreach($friends as $friend)
        @if(Auth::user()->name.'#'.Auth::id() == $friend->name && $friend->state == 1)
            <script>
                window.location.href = "{{ route('dashboard')}}"
            </script>
        @endif
    @endforeach
    <div id="hiddendata" hidden>
        <p id="username">{{Auth::user()->name}}#{{Auth::id()}}</p>
        <p id="userid">{{Auth::id()}}</p>
        <p id="gameid">{{$game->id}}</p>
        @foreach($ndice as $n)
            <p class="ndice">{{$n->n}}</p>
        @endforeach
        @foreach($cdice as $c)
            <div class="cdice">
                <p class="cdiceN">{{$c->name}}</p>
                <p class="cdiceW">{{$c->weight}}</p>
                <p class="cdiceId">{{$c->diceId}}</p>
            </div>
        @endforeach
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
                                    <option value="nobody#0">nobody</option>
                                    <option value="{{Auth::user()->name}}#{{Auth::id()}}">{{Auth::user()->name}}
                                        #{{Auth::id()}}</option>
                                    @foreach($friends as $friend)
                                        @if($friend->state == 0)
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
                <div class="allobj">
                    <button class="py-1 px-1 w-full button" id="{{$pile->owner}}"
                            name="{{$pile->name}}">{{$pile->name}}</button>
                    <div id="{{$pile->name}}box" class="itembox">
                        @foreach($pileItems as $item)
                            @if($item->PileId == $pile->id)
                                <button hidden class="items w-full {{$pile->name}}" name="{{$pile->name}}">
                                    <p class="iname">{{$item->name}}</p>
                                    <p class="iamount">{{$item->amount}}</p>
                                </button>
                            @endif
                        @endforeach
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
             style="background-image:url({{asset("uploads/".$game->board.'.jpg')}})">
        </div>
        <div class="py-3 w-2/12 h-full relative ">
            <div id="chat" class="h-10/12 overflow-auto">
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
    <div id="menunotowner" hidden class="absolute bg-white z-1 border-teal-500 border py-2 px-2">
        <button id="draw" class="border-teal-500 border py-2 px-2 w-full">draw an item(randomly)</button>
        <br>
    </div>
    <div id="menuowner" hidden class="absolute">
        <button id="take">take <input type="number" value="1"/> object</button>
    </div>
</x-app-layout>
