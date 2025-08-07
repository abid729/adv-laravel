<!DOCTYPE html>
<html>
<head>
    <title>Create Task</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl mb-4">Create New Task</h1>

        <a href="{{ route('tasks.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Back to Tasks</a>

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Title</label>
                <input type="text" name="title" id="title" class="border rounded px-3 py-2 w-full" value="{{ old('title') }}" required>
                @error('title')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700">Description</label>
                <textarea name="description" id="description" class="border rounded px-3 py-2 w-full">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="team_id" class="block text-gray-700">Team</label>
                <select name="team_id" id="team_id" class="border rounded px-3 py-2 w-full">
                    <option value="">No Team</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                    @endforeach
                </select>
                @error('team_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Task</button>
        </form>
    </div>
</body>
</html>
