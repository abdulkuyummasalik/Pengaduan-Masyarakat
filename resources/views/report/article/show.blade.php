@extends('layouts.app')
@section('content')


    <div class="max-w-2xl mx-auto mt-10 p-5 bg-white/10 rounded-lg shadow-lg border border-orange-500">
        @if (session('success'))
            <p class="text-green-600 text-sm">{{ session('success') }}</p>
        @endif
        <div class="mb-4">
            <img src="{{ asset('storage/images/' . $report->image) }}" alt="Kesenjangan Sosial"
                class="w-full h-64 object-cover rounded-lg">
        </div>
        <div>
            <p class="font-semibold text-orange-500 text-lg">{{ $report->user->email }}</p>
            <p class="text-gray-500 text-sm">{{ $report->created_at->translatedFormat('j F Y') }}</p>
            <p class="text-green-500">{{ $report->description }}</p>
        </div>
        <button class="mt-4 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">{{ $report->type }}</button>
    </div>

    @if ($report->comments->isNotEmpty())
        <div class="max-w-2xl mx-auto mt-6 border rounded-lg bg-gray-300 border-orange-500 p-4">
            <h2 class="text-lg font-semibold text-orange-500  mb-4">Komentar:</h2>
            @foreach ($report->comments as $comment)
                <div class="p-4 mb-4 bg-white rounded-lg shadow-lg">
                    <p class="font-semibold text-gray-800">{{ $comment->user->email }}</p>
                    <p class="text-gray-600 text-sm">{{ $comment->created_at->translatedFormat('j F Y') }}</p>
                    <p class="text-black mt-2">{{ $comment->comment }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('report.article.comment.store', $report->id) }}" method="POST"
        class="max-w-2xl mx-auto mt-6 p-5 bg-white/10 rounded-lg shadow-lg border border-green-600">
        @csrf
        <textarea name="comment"
            class="w-full p-3 text-black border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-green-600"
            rows="3" placeholder="Tulis komentar..."></textarea>
        <button type="submit" class="mt-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Buat
            Komentar</button>
    </form>
@endsection
