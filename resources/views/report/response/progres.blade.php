@extends('layouts.app')
@section('content')
    {{-- Create input for response --}}
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white/10 border border-orange-500 shadow-md rounded-lg p-6">
                <div class="text-xl font-semibold mb-4">Create Response</div>

                @if (session('success'))
                    <div class="bg-green-500 text-white p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-500 text-white p-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif


                {{-- Tampilkan progres sebelumnya --}}
                @if ($response && $response->response_content)
                    <div class="mb-4">
                        <div class="font-semibold text-gray-700 mb-2">Progress Responses:</div>
                        <ul class="list-disc list-inside text-gray-600">
                            @foreach (json_decode($response->response_content, true) as $index => $progres)
                                <li class="flex justify-between items-center">
                                    <span>{{ $progres }}</span>
                                    <form
                                        action="{{ route('response.content.destroy', ['id' => $response->id, 'index' => $index]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus progres ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            Hapus
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form untuk menambahkan progres baru --}}
                <form method="POST" action="{{ route('response.progres.store', $report->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="response_content" class="block text-sm font-medium text-gray-700">New Progress</label>
                        <textarea
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-black"
                            id="response_content" name="response_content" rows="3" required></textarea>
                    </div>
                    <button type="submit"
                        class="mt-4 w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
