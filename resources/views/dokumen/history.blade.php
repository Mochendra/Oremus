@extends('layouts.master')

@section('content')
    <div class="container">
        <h2>Seluruh History Dokumen</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Admin</th>
                    <th>No Registrasi</th>
                    <th>Nama Perusahaan</th>
                    <th>Jenis Dokumen</th> <!-- Kolom Jenis Dokumen ditambahkan -->
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                    @php
                        $dokumen = $activity->subject; // Akses dokumen yang terkait dengan aktivitas
                    @endphp
                    <tr>
                        <td>{{ $activity->causer ? $activity->causer->name : 'Tidak Diketahui' }}</td>
                        <td>{{ $dokumen->no_registrasi ?? 'Tidak Ada Data' }}</td>
                        <td>{{ $dokumen->nama_perusahaan ?? 'Tidak Ada Data' }}</td>
                        <td>{{ $dokumen->jenis_dokumen ?? 'Tidak Ada Data' }}</td> <!-- Menampilkan jenis dokumen -->
                        <td>{{ $activity->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
