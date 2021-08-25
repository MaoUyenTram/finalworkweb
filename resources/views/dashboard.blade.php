<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-12 h-screen bg-white ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

                    Welcome to boardgamepics.be
                    <br>
                    <video width="1200" height="900" controls>
                        <source src="{{asset("videos/tutorial.mp4")}}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
