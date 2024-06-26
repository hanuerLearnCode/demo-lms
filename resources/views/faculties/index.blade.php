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
                            <h2 class="w-full text-lg text-left px-6 py-3">Faculties List</h2>
                        </div>
                        <div class="col-md-4 h-75">
                            <form class="d-flex" method="GET" action="{{ route('faculties.search') }}">
                                @csrf  {{-- Include CSRF token if needed for search functionality --}}
                                <div class="input-group">
                                    <input type="text" class="form-control rounded-md py-2 border-gray-300"
                                           name="search" id="searchInput" placeholder="Search faculties..."
                                           aria-label="Search">
                                    <button class="btn btn-outline-primary rounded-md" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-primary" href="{{ route('faculties.create') }}">
                                <button>
                                    New faculty
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="table" class="w-full text-md text-center text-gray-500 dark:text-gray-400">
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
                                    Abbreviation
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
                            @foreach($faculties as $faculty)
                                @php
                                    $count++;
                                @endphp
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-3">
                                        {{ $count }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('faculties.show', $faculty->id) }}">
                                            {{ $faculty->name }}
                                        </a>
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
                        {{ $faculties->onEachSide(2)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script type="module">

        /**
         * Live search box
         * @param input
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
                        url: '{{ route("faculties.search") }}', // Create this route in your Laravel app
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

