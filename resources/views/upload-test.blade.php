<form
    class="myForm"
    action="{{ route('upload') }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf
    <input class="myInput" type="file" name="json_file" accept="json" >
    <button class="myBtn" type="submit">Upload JSON file</button>
</form>
