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
                            <h2 class="w-full text-lg text-left px-6 py-3">Students List</h2>
                        </div>
                        <div class="col-md-2 add-user">
                            <a class="btn btn-primary" href="{{ route('students.create') }}">
                                <button>
                                    New Student
                                </button>
                            </a>
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
                                    Class
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Faculty
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($students as $student)
                                @php
                                    $count++;
                                @endphp
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
                                    <td class="px-6 py-4 me-2 row">
                                        <div class="px-1 py-1 col-md-6">
                                            <a class="btn btn-outline-primary w-100"
                                               href="{{ route('students.edit', $student->id) }}">
                                                Edit
                                            </a>
                                        </div>
                                        <div class="px-1 py-1 col-md-6">
                                            <a class="btn btn-outline-danger w-100"
                                               href="{{ route('students.destroy', $student->id) }}">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="paginate m-3 px-4 d-flex align-items-center justify-content-center">
                        {{ $students->onEachSide(2)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection