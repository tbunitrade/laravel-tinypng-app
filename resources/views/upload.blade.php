<!DOCTYPE html>
<html>
<head>
    <title>Upload Image</title>
</head>
<body>
<h1>Upload Image</h1>
<form action="/upload-image" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" required>
    <button type="submit">Upload</button>
</form>
</body>
</html>
