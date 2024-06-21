@extends('layouts.app')

@section('content')

    <main class="container">
        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-8 lg:px-8">
                @if(session('error'))
                    <div class="session mt-3">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                    aria-hidden="true"></button>
                        </div>
                    </div>
                @endif
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="overflow-x-auto flex items-center row">
                        <div class="col-md-10">
                            <h2 class="w-full text-lg text-left px-6 py-3">New Student</h2>
                        </div>
                    </div>
                    <div class="overflow-x-auto px-4 pt-1 pb-4">
                        <form class="form" method="post" action="{{ route('users.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control rounded-md" id="name"
                                       name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control rounded-md" id="email" name="email"
                                       aria-describedby="emailHelp" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="office_classs_id" class="form-label">Class</label>
                                <input type="text" class="form-control rounded-md" id="office_classs_id"
                                       name="office_classs_id"
                                       aria-describedby="emailHelp" value="{{ old('office_classs_id') }}" required>
                                @error('office_classs')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="faculty_id" class="form-label">Faculty</label>
                                <input type="text" class="form-control rounded-md" id="faculty_id" name="faculty_id"
                                       aria-describedby="emailHelp" value="{{ old('faculty_id') }}" required>
                                @error('faculty_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control rounded-md" id="password" name="password"
                                       required>
                                @error('password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm password</label>
                                <input type="password" class="form-control rounded-md" id="password_confirmation"
                                       name="password_confirmation" required>
                                @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mt-3 d-flex justify-content-center align-items-center row">
                                <div class="col-md-10">
                                </div>
                                <div class="col-md-2 d-flex justify-content-end align-items-center">
                                    <a href="{{ route('students.index')}}" class="me-2 flex-grow-1 w-100">
                                        <button type="button" class="btn btn-outline-danger">Cancel</button>
                                    </a>
                                    <button type="submit" class="btn btn-primary flex-grow-1 fw-bold w-100">Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
