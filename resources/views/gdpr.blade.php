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
                <p>gdpr blablabla</p>
            </div>
        </div>
    </div>
</x-app-layout>
