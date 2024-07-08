
@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<form action="/upload" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="json_file" required>
    <button type="submit">Upload</button>
</form>
