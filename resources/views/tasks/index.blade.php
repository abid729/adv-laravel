<!DOCTYPE html>
<html>
<head>
    <title>Tasks</title>
     @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl mb-4">Tasks</h1>
        
        @can('create', App\Models\Task::class)
            <a href="{{ route('tasks.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Create Task</a>
        @endcan

        @can('view-admin-dashboard')
            <a href="{{ route('admin.dashboard') }}" class="bg-green-500 text-white px-4 py-2 rounded">Admin Dashboard</a>
        @endcan

        <div class="mt-4">
            @foreach ($tasks as $task)
                <div class="border p-4 mb-2">
                    <h3>{{ $task->title }}</h3>
                    <p>{{ $task->description }}</p>
                    <p>Status: {{ $task->status }}</p>
                    
                    @can('view', $task)
                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-500">View</a>
                    @endcan
                    @can('update', $task)
                        <a href="{{ route('tasks.edit', $task) }}" class="text-green-500">Edit</a>
                    @endcan
                    @can('delete', $task)
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Delete</button>
                        </form>
                    @endcan
                    @can('complete', $task)
                        <form action="{{ route('tasks.complete', $task) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-purple-500">Mark Complete</button>
                        </form>
                    @endcan
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>