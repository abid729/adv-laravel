<!DOCTYPE html>
<html>
<head>
    <title>View Task</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl mb-4">Task Details</h1>
        <a href="{{ route('tasks.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Back to Tasks</a>
        <div class="border p-4">
            <h3>{{ $task->title }}</h3>
            <p>{{ $task->description ?? 'No description' }}</p>
            <p>Status: {{ $task->status }}</p>
            <p>Team: {{ $task->team ? $task->team->name : 'No team' }}</p>
            <p>Assigned to: {{ $task->user->name }}</p>
        </div>
    </div>
</body>
</html>