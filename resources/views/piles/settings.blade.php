<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('js/createsettings.js')}}"></script>
    @if(Session::has('succes'))
        <div class="alert alert-info">{{ Session::get('succes') }}</div>
    @endif
    @if(Session::has('error'))
        <div class="bg-red-100 border-t border-b border-red-500 text-red-700 px-4 py-3 text-center"
             role="alert">{{ Session::get('error') }}</div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-1 py-1 flex bg-white overflow-hidden h-full">
                <form class="flex w-full" method="post" action="{{route('setndice')}}">
                    @method("POST")
                    @csrf
                    <input type="hidden" name="id" value= {{$id}}>
                    <p class="flex-inline mr-3 py-1 px-2 w-full">add a normal </p>
                    <input
                        class="appearance-none bg-transparent border-none text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                        name="dxnormal" type="number" aria-label="dx" required>
                    <p class="flex-inline mr-3 py-1 px-2 w-full">sided dice/roulette </p>
                    <button
                        class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                        type="submit" id="normalclick">
                        add
                    </button>
                </form>
            </div>
            <div class="px-1 py-1 flex bg-white overflow-hidden h-full">
                <form class="flex w-full">
                    @csrf
                    <input type="hidden" name="id" value= {{$id}}>
                    <p class="flex-inline mr-3 py-1 px-2 w-full">add a custom </p>
                    <input
                        class="appearance-none bg-transparent border-none text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                        name="dxcustom" id="dxcustom" type="number" aria-label="dx" required>
                    <p class="flex-inline mr-3 py-1 px-2 w-full">sided dice/roulette </p>
                    <button
                        class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                        type="button" id="customclick">
                        add
                    </button>
                </form>
            </div>
            <div>
                <form class="bg-white overflow-hidden h-full">
                    @csrf
                    <div id="weightsform">
                    </div>
                    <input hidden type="number" id="gameIdtest" value="{{$id}}">
                    <input hidden type="text" id="cdicex"
                           value="@if(!@empty($cdice)) {{$cdice->diceId}} @else 0 @endif ">
                    <button
                        class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                        type="button" id="weightsbutton" hidden>
                        add
                    </button>
                    <button
                        class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                        type="button" id="weightscancel" hidden>
                        cancel
                    </button>
                </form>
            </div>

            <div class="px-1 py-1 overflow-hidden h-full bg-white ">
                <p class="float-left"> you have currently <b id="normalx">{{$ndice}}</b> normal dice and <b
                        id="customx"> @if(!@empty($cdice)) {{$cdice->diceId}} @else 0 @endif </b> custom dice</p>
                <button
                    class="float-right flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                    type="button" id="dalldice"> destroy all dice
                </button>
            </div>
            <hr>
            <div class="bg-white overflow-hidden h-full">
                <div class=" px-1 py-1 flex max-w-lg ">
                    <input
                        class="appearance-none bg-transparent border-b border-teal-500 w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                        id="owner" type="text" aria-label="images">
                    <button
                        class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                        type="button" id="addoption">
                        add owner
                    </button>
                </div>
                <br>
                <table class="px-1 py-1 overflow-hidden h-full bg-white w-full" >
                    @foreach($piles as $pile)
                        <tr>
                            <td class="float-left px-1 py-1 pilename" >{{$pile->name}} <p hidden>{{$pile->id}}</p></td>
                            <td class="float-right px-1 py-1">
                                <select class="form-select mt-2 block w-full ownerselect" name="ownerselect">
                                    <option value="public">public</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="px-1 py-1 overflow-hidden h-full bg-white ">
                <form class="float-left max-w-sm" method="post" action="{{route('createpiles')}}">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="id" value= {{$id}}>
                    <button
                        class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                        type="submit">
                        go back
                    </button>
                </form>

                <button
                    class="float-right flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                    type="button" id="save"> save and finish
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
