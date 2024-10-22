@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Edit Expired Date</h2>
    <form action="{{ route('dokumen.update', $dokumen->id) }}" method="POST">
        @csrf
        @method('POST')

        <label for="expired">Expired:</label>
        <input type="date" name="expired" id="expired" required value="{{ old('expired', $dokumen->expired) }}">

        <!-- Hapus input field untuk PIC -->
        <p>PIC saat ini: {{ $dokumen->pic }}</p>
        <p>PIC akan diupdate ke: {{ auth()->user()->name }}</p>
        <button type="submit">Update</button>
    </form>
</div>
@endsection