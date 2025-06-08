
// filepath: [welcome.blade.php](http://_vscodecontentref_/1)
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel + React</title>
    @viteReactRefresh
    @vite('resources/js/main.jsx')
</head>
<body class="antialiased">
    <div id="root"></div>
</body>
</html>