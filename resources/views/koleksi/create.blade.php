@extends('layouts.app')
@section('title', 'Tambah Koleksi')

@section('content')
<div class="mb-4">
    <a href="{{ route('koleksi.index') }}" class="text-decoration-none text-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    <h3 class="fw-bold mt-1">Tambah Koleksi Baru</h3>
</div>
<form action="{{ route('koleksi.store') }}" method="POST" data-tape-form novalidate>
    @csrf
    @include('koleksi._form', ['koleksi' => null])
</form>
@endsection
