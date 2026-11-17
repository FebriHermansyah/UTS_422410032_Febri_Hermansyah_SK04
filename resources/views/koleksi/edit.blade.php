@extends('layouts.app')
@section('title', 'Edit Koleksi')

@section('content')
<div class="mb-4">
    <a href="{{ route('koleksi.show', $koleksi) }}" class="text-decoration-none text-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    <h3 class="fw-bold mt-1">Edit Koleksi <span class="code-pill">{{ $koleksi->code }}</span></h3>
</div>
<form action="{{ route('koleksi.update', $koleksi) }}" method="POST" data-tape-form novalidate>
    @csrf @method('PUT')
    @include('koleksi._form', ['koleksi' => $koleksi])
</form>
@endsection
