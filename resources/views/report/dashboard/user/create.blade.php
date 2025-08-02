@extends('layouts.app')
@section('content')
    <div class="container mx-auto mt-10 max-w-md">
        <div class="bg-white shadow-lg rounded-t-lg border border-gray-200">
            <div class="px-6 py-4 bg-orange-500 text-white rounded-t-lg">
                <h1 class="text-xl font-bold">Create Akun Staff</h1>
            </div>
            <div class="px-6 py-4">
                <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-black">Nama</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 text-black"
                            required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-black">Email</label>
                        <input type="email" name="email" id="email"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 text-black"
                            required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-black">Password</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 text-black"
                            required>
                    </div>
                    <div class="flex justify-between items-center pt-4">
                        <a href="{{ route('user.index') }}"
                            class="bg-black hover:bg-gray-800 text-white py-2 px-4 rounded">Batal</a>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white py-2 px-6 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
