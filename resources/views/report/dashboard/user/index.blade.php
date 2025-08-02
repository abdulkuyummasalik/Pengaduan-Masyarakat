@extends('layouts.app')
@section('content')
    <div class="container mx-auto mt-10 max-w-5xl">
        <div class="bg-gray-900 shadow-md rounded-lg">
            @if (Session::get('success'))
                <div class="p-4 mb-4 text-green-800 bg-green-100 border border-green-300 rounded-lg" role="alert">
                    <span class="font-medium">Berhasil!,</span> {{ Session::get('success') }}.
                </div>
            @endif

            @if (Session::get('failed'))
                <div class="p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded-lg" role="alert">
                    <span class="font-medium">Kesalahan!</span> {{ Session::get('failed') }}
                </div>
            @endif

            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                <h1 class="text-xl font-bold text-white">Akun Staff</h1>
                <a href="{{ route('user.create') }}"
                    class="bg-orange-500 hover:bg-orange-600 text-white text-sm py-2 px-4 rounded transition">
                    Create Akun
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left border-collapse border bg-orange-900/100 border-gray-700">
                    <thead class="bg-green-900/100">
                        <tr>
                            <th class="px-6 py-3 text-white font-medium text-sm border border-gray-700">#</th>
                            <th class="px-6 py-3 text-white font-medium text-sm border border-gray-700">Email</th>
                            <th class="px-6 py-3 text-white font-medium text-sm border border-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="hover:bg-white/10">
                                <td class="px-6 py-4 text-sm text-white border border-gray-700">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 text-sm text-white border border-gray-700">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 text-sm text-white border border-gray-700">
                                    <form action="{{ route('user.reset', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded transition">
                                            Reset
                                        </button>
                                    </form>
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white text-sm py-2 px-4 rounded transition"
                                            @if ($user->hasResponded) disabled @endif>
                                            Hapus
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
@endsection
