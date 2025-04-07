<div class="container mx-auto mt-10">
    <h2>Upload a File to Google Cloud Storage</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-2 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-2 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('file.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="file">Choose a file:</label>
            <input type="file" name="file" id="file" class="border p-2 mb-4" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white p-2">Upload</button>
    </form>
</div>
