<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="float-left">
                    <table>
                        <thead>Friends</thead>
                        <tbody>
                        <tr>
                            <td>
                                <form>
                                    <input type="text">
                                </form>
                            </td>
                            <td>
                                <button type="submit" class="button-succes">add friend</button>
                            </td>
                        </tr>
                        <tr><td><br></td></tr>
                        @foreach($friends as $friend)
                            <tr>
                                <td>{{$friend->name}}</td>
                                <td>
                                    <button class="color-red">remove</button>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="float-right">
                    <table>
                        <thead>bans</thead>
                        <tbody>
                        <tr>
                            <td>
                                <form>
                                    <input type="text">
                                </form>
                            </td>
                            <td>
                                <button type="submit" class="button-succes">ban person</button>
                            </td>
                        </tr>
                        <tr><td><br></td></tr>
                        @foreach($bans as $ban)
                            <tr>
                                <td>{{$ban->name}}</td>
                                <td>
                                    <button class="button-fail">remove</button>
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
