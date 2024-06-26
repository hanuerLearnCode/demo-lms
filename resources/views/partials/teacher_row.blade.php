<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <td class="px-6 py-4">
        {{ $count }}
    </td>
    <td class="px-6 py-4">
        {{ $teacher->user->name }}
    </td>
    <td class="px-6 py-4">
        {{ ucwords($teacher->position) }}
    </td>
    <td class="px-6 py-4">
        {{ $teacher->user->email }}
    </td>
    <td class="px-6 py-4">
        {{ $teacher->faculty->name }}
    </td>
    <td class="px-6 py-4">
        {{ $teacher->created_at }}
    </td>
    <td class="px-6 py-4 me-2 ">
        <div class="px-1 py-1">
            <a class="btn btn-outline-primary w-100"
               href="{{ route('teachers.edit', $teacher->id) }}">
                Edit
            </a>
        </div>
        <div class="px-1 py-1 ">
            <form method="POST"
                  action="{{ route('teachers.destroy', $teacher->id) }}"
                  onsubmit="return confirm('Are you sure deleting this teacher?')">
                @csrf  {{-- Include CSRF token --}}
                @method('DELETE')  {{-- Specify DELETE method --}}
                <button type="submit" class="btn btn-outline-danger w-100">Delete
                </button>
            </form>
        </div>
    </td>
</tr>
