<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
     @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl mb-4">Admin Dashboard</h1>
        <a href="{{ route('tasks.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Back to Tasks</a>
        <p>Welcome to the Admin Dashboard!</p>
    </div>
</body>
</html>
