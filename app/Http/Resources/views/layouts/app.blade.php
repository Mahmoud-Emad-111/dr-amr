
{{--  --}}
<a href="{{ route('pdf.create') }}">test</a>
<a href="{{ route('pdf.download', $pdfDocument->id) }}"><img src="{{ asset('95.jpg') }}"></a>

@yield('content')

