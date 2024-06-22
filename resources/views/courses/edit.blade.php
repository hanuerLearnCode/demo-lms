@extends('layouts.app')

@section('content')

    <main class="">
        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-8 lg:px-8">
                @if(session('error'))
                    <div class="session mt-3 w-100">
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
                            <h2 class="w-full text-lg text-left px-6 py-3">Updating course</h2>
                        </div>
                    </div>
                    <div class="overflow-x-auto px-4 pt-1 pb-4">
                        <form class="form" method="post" action="{{ route('courses.update', $course->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control rounded-md" id="name"
                                       name="name" value="{{ $course->name }}" required>
                                @error('name')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="faculty_id" class="form-label">Faculty</label>
                                <select id="faculty_id" name="faculty_id" class="form-select rounded-md"
                                        aria-label="Default select example">
                                    <option selected value="">Select faculty</option>
                                    @foreach($faculties as $faculty)
                                        <option value="{{ $faculty->id }}"> {{ $faculty->name }} </option>
                                    @endforeach
                                </select>
                                @error('faculty_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mt-3 d-flex justify-content-center align-items-center row">
                                <div class="col-md-10">
                                </div>
                                <div class="col-md-2 d-flex justify-content-end align-items-center">
                                    <a href="{{ route('courses.index') }}" class="me-2 flex-grow-1 w-100">
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
