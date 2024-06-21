<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <td class="px-6 py-3">
        {{ $count }}
    </td>
    <td class="px-6 py-3">
        {{ $faculty->name }}
    </td>
    <td class="px-6 py-3">
        {{ $faculty->abbreviation }}
    </td>
    <td class="px-6 py-3">
        {{ $faculty->created_at }}
    </td>
    <td class="px-6 py-3 me-2 row">
        <div class="px-1 py-1 col-md-6">
            <a class="btn btn-outline-primary w-100"
               href="{{ route('faculties.edit', $faculty->id) }}">
                Edit
            </a>
        </div>
        <div class="px-1 py-1 col-md-6">
            <form method="POST" action="{{ route('faculties.destroy', $faculty->id) }}"
                  onsubmit="return confirm('Are you sure deleting this faculty?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100">Delete
                </button>
            </form>
        </div>
    </td>
</tr>
