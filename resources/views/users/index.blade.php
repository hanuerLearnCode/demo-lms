@extends('layouts.app')
@php
    $count = 0;
@endphp
@section('content')
    <main class="">
        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-8 lg:px-8">
                @if(session('success'))
                    <div class="session app-content ml-3 mr-2">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                    aria-hidden="true"></button>
                        </div>
                    </div>
                @endif
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="overflow-x-auto flex items-center row">
                        <div class="col-md-10">
                            <h2 class="w-full text-lg text-left px-6 py-3">Users List</h2>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-md text-left text-gray-500 dark:text-gray-400">
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
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Role
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Created at
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                @php
                                    $count++;
                                @endphp
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
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="paginate m-3 px-4 d-flex align-items-center justify-content-center">
                        {{ $users->onEachSide(2)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
