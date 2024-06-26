@extends('layouts.app')
@php
    $count = 0;
@endphp
@section('content')
    <main class="">
        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-8 lg:px-8">
                @if(session('success'))
                    <div class="session mt-3 w-100">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                    aria-hidden="true"></button>
                        </div>
                    </div>
                @endif
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="overflow-x-auto flex items-center row">
                        <div class="col-md-6">
                            <h2 class="w-full text-lg text-left px-6 py-3">Teachers List</h2>
                        </div>
                        <div class="col-md-4 h-75">
                            <form class="d-flex" method="GET" action="{{ route('teachers.search') }}">
                                @csrf  {{-- Include CSRF token if needed for search functionality --}}
                                <div class="input-group">
                                    <input type="text" class="form-control rounded-md py-2 border-gray-300"
                                           name="search" id="searchInput" placeholder="Search teachers..."
                                           aria-label="Search">
                                    <button class="btn btn-outline-primary rounded-md" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2 add-user">
                            <a class="btn btn-primary" href="{{ route('teachers.create') }}">
                                <button>
                                    New Teacher
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
                            <thead
                                    class="text-md text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Position
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Faculty
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Created at
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody id="table_body" class="initial-tbody">
                            @foreach($teachers as $teacher)
                                @php
                                    $count++;
                                @endphp
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
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="paginate" class="paginate m-3 px-4 d-flex align-items-center justify-content-center">
                        {{ $teachers->onEachSide(2)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script type="module">

        /**
         * Live search box
         */
        $(document).ready(function () {

            // query caching
            let lastQuery = '';

            $('#searchInput').on('input', function () {
                let query = $(this).val();

                if (query !== lastQuery) {
                    if (query.length > 0) {
                        // Hide pagination when searching
                        $('#paginate').addClass('d-none');
                    } else {
                        // Show pagination when search input is empty
                        $('#paginate').removeClass('d-none');
                    }
                    $.ajax({
                        url: '{{ route("teachers.search") }}',
                        method: 'GET',
                        header: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data: {query: query},
                        success: function (response) {
                            $('#table_body').html(response);
                        },
                        error: function (xhr) {
                            console.log('Error:', xhr);
                        }
                    });

                    lastQuery = query;
                }
            });
        });

    </script>
@endsection
