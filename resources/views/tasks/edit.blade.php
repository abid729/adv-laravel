<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl mb-4">Edit Task</h1>
        <a href="{{ route('tasks.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Back to Tasks</a>

        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Title</label>
                <input type="text" name="title" id="title" class="border rounded px-3 py-2 w-full" value="{{ old('title', $task->title) }}" required>
                @error('title')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700">Description</label>
                <textarea name="description" id="description" class="border rounded px-3 py-2 w-full">{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="team_id" class="block text-gray-700">Team</label>
                <select name="team_id" id="team_id" class="border rounded px-3 py-2 w-full">
                    <option value="">No Team</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team_id', $task->team_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                    @endforeach
                </select>
                @error('team_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700">Status</label>
                <select name="status" id="status" class="border rounded px-3 py-2 w-full" required>
                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Task</button>
        </form>
    </div>
</body>
</html>