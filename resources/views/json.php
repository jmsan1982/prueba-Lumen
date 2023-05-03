<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Enviar archivo JSON</title>
</head>
<body>

<h1>Enviar archivo JSON</h1>
<form method="POST" action="/api/corregirTest" enctype="multipart/form-data">
    @csrf
    <input type="file" name="archivo_json" required><br/><br/>
    <button type="submit">Enviar archivo</button>
</form>
</body>
</html>

