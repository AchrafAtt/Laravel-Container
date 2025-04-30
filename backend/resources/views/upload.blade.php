<x-main-layout>
    <x-slot:title>
        Upload a File to Backend
    </x-slot>

    <h1 class="text-3xl">Upload a File</h1>

    @if (session('success'))
        <p class="text-green-500">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <p class="text-red-500">{{ $errors->first() }}</p>
    @endif

    <form action="/upload" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">Choose a file:</label>
        <input type="file" name="file" id="file" required>
        <button type="submit">Upload</button>
    </form>
</x-main-layout>
