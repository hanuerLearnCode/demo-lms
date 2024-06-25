<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <td class="px-6 py-4">
        {{ $count }}
    </td>
    <td class="px-6 py-4">
        {{ $student->user->name }}
    </td>
    <td class="px-6 py-4">
        {{ $student->user->email }}
    </td>
    <td class="px-6 py-4">
        {{ $student->officeClass->name }}
    </td>
    <td class="px-6 py-4">
        {{ $student->faculty->name }}
    </td>
    <td class="px-6 py-4">
        {{ $student->created_at }}
    </td>
    <td class="px-6 py-4 me-2 row">
        <div class="px-1 py-1 col-md-6">
            <a class="btn btn-outline-primary w-100"
               href="{{ route('students.edit', $student->id) }}">
                Edit
            </a>
        </div>
        <div class="px-1 py-1 col-md-6">
            <form method="POST"
                  action="{{ route('students.destroy', $student->id) }}"
                  onsubmit="return confirm('Are you sure deleting this student?')">
                @csrf  {{-- Include CSRF token --}}
                @method('DELETE')  {{-- Specify DELETE method --}}
                <button type="submit" class="btn btn-outline-danger w-100">Delete
                </button>
            </form>
        </div>
    </td>
</tr>
