<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <td class="px-6 py-4">
        {{ $count }}
    </td>
    <td class="px-6 py-4">
        {{ $user->name }}
    </td>
    <td class="px-6 py-4">
        {{ $user->email }}
    </td>
    <td class="px-6 py-4">
        {{ $user->role->first() ? ucfirst($user->role->first()->title) : null }}
    </td>
    <td class="px-6 py-4">
        {{ $user->created_at }}
    </td>
</tr>
