<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <td class="px-6 py-3">
        {{ $count }}
    </td>
    <td class="px-6 py-3">
        {{ $course->name }}
    </td>
    <td class="px-6 py-3">
        {{ $course->abbreviation }}
    </td>
    <td class="px-6 py-3">
        {{ $course->faculty->name }}
    </td>
    <td class="px-6 py-3">
        {{ $course->credit }}
    </td>
    <td class="px-6 py-3">
        {{ $course->created_at }}
    </td>
    <td class="px-6 py-3 me-1 row">
        <div class="px-1 py-1 col-md-6">
            <a class="btn btn-outline-primary w-100"
               href="{{ route('courses.edit', $course->id) }}">
                Edit
            </a>
        </div>
        <div
            class="px-1 py-1 col-md-6 d-flex align-items-center justify-content-center">
            <form method="POST"
                  action="{{ route('courses.destroy', $course->id) }}"
                  onsubmit="return confirm('Are you sure deleting this course?')">
                @csrf  {{-- Include CSRF token --}}
                @method('DELETE')  {{-- Specify DELETE method --}}
                <button type="submit" class="btn btn-outline-danger w-100">Delete
                </button>
            </form>
        </div>
    </td>
</tr>
