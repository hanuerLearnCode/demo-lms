<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <td class="px-6 py-3">
        {{ $count }}
    </td>
    <td class="px-6 py-3">
        {{ $class->name }}
    </td>
    <td class="px-6 py-3">
        {{ $class->faculty->name }}
    </td>
    <td class="px-6 py-3">
        {{ count($class->students) }}
    </td>
    <td class="px-6 py-3">
        {{ $class->created_at }}
    </td>
    <td class="px-6 py-3 me-2 row">
        <div class="px-1 py-1 col-md-6">
            <a class="btn btn-outline-primary w-100"
               href="{{ route('office_classes.edit', $class->id) }}">
                Edit
            </a>
        </div>
        <div class="px-1 py-1 col-md-6">
            <form method="POST" action="{{ route('office_classes.destroy', $class->id) }}"
                  onsubmit="return confirm('Are you sure deleting this class?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100">Delete
                </button>
            </form>
        </div>
    </td>
</tr>
