<!-- resources/views/pdf/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>PDF Documents</h1>

    @foreach ($pdfDocuments as $pdfDocument)
        <div>
            <h2>{{ $pdfDocument->title }}</h2>
            <h2>{{ $pdfDocument->id }}</h2>
            <h2>{{ $pdfDocument->user_id }}</h2>
            <a href="{{ route('pdf.download', $pdfDocument->id) }}">Download PDF</a>
        </div>
    @endforeach
@endsection
