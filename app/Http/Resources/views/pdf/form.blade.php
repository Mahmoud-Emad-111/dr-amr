
@extends('layouts.app')

@section('content')
    <form action="{{ route('pdf.create') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label for="title">Title:</label>
        <input type="text" name="title" required>

        <label for="file">PDF File:</label>
        <input type="file" name="file" accept=".pdf" required>

        <button type="submit">Submit</button>
    </form>
@endsection
