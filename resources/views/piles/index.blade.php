<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <script>
        function changeI(action) {
            let form = document.getElementsByName('cId');
            for (let i = 0; i < form.length; i++) {
                form[i].value = action;
            }
        }
    </script>
    @if(Session::has('succes'))
        <div class="alert alert-info">{{ Session::get('succes') }}</div>
    @endif
    @if(Session::has('error'))
        <div class="bg-red-100 border-t border-b border-red-500 text-red-700 px-4 py-3 text-center"
             role="alert">{{ Session::get('error') }}</div>
    @endif
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <table class="mx-auto sm:px-6 lg:px-8 p-10 table-auto px-4 py-2 content-start ">
            <tr class="px-4 py-2">
                <th><h1 class="w-3/8 text-center">create pile</h1></th>
                <th><h1 class="w-5/8 text-center">add items to piles</h1></th>
            </tr>
            <tr class="px-4 py-2 bg-white border-b border-teal-500">
                <td>
                    <div class=" bg-white overflow-hidden">
                        <form class="w-full " method="post" action="{{route('piles.store')}}">
                            @method('POST')
                            @csrf
                            <div class=" w-full py-2">
                                <input type="hidden" name="id" value={{$id}}>
                                <input class="appearance-none bg-transparent border-none w-full text-gray-700
                                mr-3 py-1 px-2 leading-tight focus:outline-none" name="name" type="text"
                                       placeholder="pilename" aria-label="name">
                                <div class="mt-2">
                                    <span class="text-gray-700">access</span><br>
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio" name="type" value="0">
                                        <span class="ml-2">owner only</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" class="form-radio" name="type" value="1">
                                        <span class="ml-2">public</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="block mt-3">
                                        <span class="text-gray-700">visibility</span>
                                        <select class="form-select mt-2 block w-full" name="vis">
                                            <option value="0">nobody can see the content or amount</option>
                                            <option value="1">content and amount only visible to owner</option>
                                            <option value="2">amount is visible to all but not the content</option>
                                            <option value="3">all is visible to all</option>
                                        </select>
                                    </label>
                                </div>
                                <button class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500
                                hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded" type="submit">
                                    Add pile
                                </button>
                            </div>
                        </form>
                    </div>
                </td>
                <td>
                    <div class=" bg-white overflow-hidden h-full">
                        <form class="w-full max-w-lg" method="get" action="{{route('settings')}}">
                            <input type="hidden" name="id" value= {{$id}}>
                            <button
                                class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                type="submit">
                                set owners and dice/roulettes
                            </button>
                        </form>
                        <br>
                        <form class=" w-full max-w-lg " method="POST" enctype="multipart/form-data"
                              action="{{route('createpiles')}}">
                            @csrf
                            <div class="flex py-2">
                                <input type="hidden" name="id" value= {{$id}}>
                                <input
                                    class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                                    name="images[]" type="file" aria-label="images" multiple required>
                                <button
                                    class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                    type="submit">
                                    upload images
                                </button>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
            <tr class="px-4 py-2 h-full bg-white">

                <td class="w-1/4 m-1 p-1 h-full flex flex-col w-full">
                    @if(!@empty($piles))
                        <div class=" bg-white overflow-hidden shadow-xl">
                            @foreach($piles as $pile)
                                <div class="rounded overflow-hidden shadow-lg w-full">
                                    <form class="px-6 py-4 flex" method="post"
                                          action="{{route('piles.destroy',$pile->id)}}">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" name="pileId" value="{{$pile->id}}"/>
                                        <input type="hidden" name="id" value="{{$id}}"/>
                                        <button onclick="changeI({{$pile->id}})" type="button"
                                                class="w-full">{{$pile->name}}</button>
                                        <button
                                            class=" flex-shrink-0 bg-red-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                            type="submit">
                                            delete
                                        </button>
                                    </form>
                                    @if(!@empty($pileItems))
                                        @foreach($pileItems as $pItem)
                                            @if($pItem->id == $pile->id)
                                                <form class="px-1 py-1 flex" method="post"
                                                      action="{{route('items.destroy',$pItem->itemId)}}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <input type="hidden" name="itemId" value="{{$pItem->itemId}}"/>
                                                    <input type="hidden" name="id" value="{{$id}}"/>
                                                    <p class="text-center w-full">{{$pItem->name}}
                                                        : {{$pItem->amount}}</p>
                                                    <button
                                                        class="flex-shrink-0 bg-red-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                                                        type="submit">
                                                        x
                                                    </button>
                                                </form>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </td>
                <td class="w-3/4 m-1 p-1 h-full">
                    @if(!@empty($imgs))
                        <div
                            class="bg-white overflow-hidden shadow-xl sm:rounded-lg grid grid-cols-3 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
                            @foreach($imgs as $img)
                                <div class="rounded overflow-hidden shadow-lg float-right">
                                    <img class="object-scale-down max-h-48 max-w-16" src="{{asset("uploads/".$img)}}">
                                    <form class="px-6 py-4" name="add" method="post" action="{{route('items.store')}}">
                                        @method('POST')
                                        @csrf
                                        <input type="hidden" name="cId" class="changeable" value="@if(Session::has('cVal')){{Session::get('cVal')}}@else 999 @endif">
                                        <input type="hidden" name="id" value="{{$id}}"/>
                                        <input type="hidden" name="originalname" value="{{$img}}"/>
                                        <label class="inline-flex items-center">name:
                                            <input
                                                class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                                                name="name" type="text" value="{{$img}}"/>
                                        </label>
                                        <label class="inline-flex items-center">amount:
                                            <input
                                                class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                                                name="amount" type="text" value="1"/>
                                        </label>
                                        <button
                                            class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded addpile"
                                            type="submit">
                                            add to current pile
                                        </button>
                                    </form>
                                    <form class="px-6 py-4" method="post" action="{{route('piles.update',$id)}}">
                                        @method('PUT')
                                        @csrf
                                        <input type="hidden" name="cId" class="changeable" value="@if(Session::has('cVal')){{Session::get('cVal')}}@else 999 @endif">
                                        <input type="hidden" name="id" value="{{$id}}"/>
                                        <input type="hidden" name="name" value="{{$img}}"/>
                                        <button
                                            class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded pileimg"
                                            type="submit">
                                            set this image as image for current pile
                                        </button>
                                    </form>
                                    <form class="px-6 py-4" method="post" action="{{route('uploadboard')}}">
                                        @method('POST')
                                        @csrf
                                        <input type="hidden" name="id" value="{{$id}}"/>
                                        <input type="hidden" name="name" value="{{$img}}"/>
                                        <button
                                            class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded boardimg"
                                            type="submit">
                                            set this image as image for the board
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </td>

            </tr>
        </table>
    </div>
</x-app-layout>
