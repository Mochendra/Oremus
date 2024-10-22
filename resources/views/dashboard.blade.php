    @extends('layouts.master')

    @section('header')
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Sistem Informasi Manajemen File PT. Oremus Bahari Mandiri') }}
                </h2>
            </div>
        </header>
    @endsection

    @section('content')
        <div class="flex">
            {{-- Main Content --}}
            <div class="flex-1 py-12 px-6">
                <div class="bg-white overflow-hidden shadow-lg lg:rounded-lg" style="padding: 10px 15px; margin: -10px;">
                    <div class="p-6 text-gray-900">
                        <h2 class="font-semibold text-base text-gray-800 leading-tight"
                            style="margin-top:10px;margin-bottom:20px;">
                            {{ __('Daftar Dokumen') }}
                        </h2>
                        <div class="d-flex justify-content-between" style="margin-bottom:10px;">
                            <div>
                                @if (auth()->user()->role == 'admin')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#dokumenModal">
                                        Tambah Dokumen
                                    </button>
                                    <a href="{{ route('dokumen.export.excel') }}" class="btn btn-success">Download Excel</a>
                                @endif
                            </div>
                            <div>
                                @if (auth()->user()->role == 'admin')
                                    <a href="{{ route('dokumen.history') }}"
                                        class="btn btn-primary">{{ __('Seluruh History') }}</a>
                                @endif
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="dokumenModal" tabindex="-1" aria-labelledby="dokumenModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
                                <!-- Set custom max width -->
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="dokumenModalLabel">Tambah Dokumen</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="dokumenForm" action="{{ route('dokumen.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="pic_id" value="{{ auth()->id() }}">
                                            <div class="row g-3">
                                                {{-- Pilih User --}}
                                                <div class="mb-2">
                                                    <label for="user_id" class="form-label">Pilih User</label>
                                                    <select name="user_id" id="user_id" class="form-select">
                                                        <option value="">Pilih User</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <!-- No Registrasi Manual -->
                                            <div class="mb-2">
                                                <label for="no_registrasi" class="form-label">No. Registrasi
                                                    Manual</label>
                                                <input type="text" name="no_registrasi" id="no_registrasi"
                                                    class="form-control" required>
                                            </div>

                                            <!-- Nama Perusahaan -->
                                            <div class="mb-2">
                                                <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                                                <input type="text" name="nama_perusahaan" id="nama_perusahaan"
                                                    class="form-control">
                                            </div>

                                            <!-- Jenis Dokumen -->
                                            <div class="mb-2">
                                                <label for="jenis_dokumen" class="form-label">Jenis Dokumen</label>
                                                <input type="text" name="jenis_dokumen" id="jenis_dokumen"
                                                    class="form-control">
                                            </div>

                                            <!-- No Dokumen -->
                                            <div class="mb-2">
                                                <label for="no_dokumen" class="form-label">No. Dokumen</label>
                                                <input type="text" name="no_dokumen" id="no_dokumen" class="form-control"
                                                    required>
                                            </div>

                                            <!-- Wilayah Kerja -->
                                            <div class="mb-2">
                                                <label for="wilayah_kerja" class="form-label">Wilayah Kerja</label>
                                                <select name="wilayah_kerja" id="wilayah_kerja" class="form-select">
                                                    <option value="Gresik">Gresik</option>
                                                    <option value="Surabaya">Surabaya</option>
                                                </select>
                                            </div>

                                            <!-- Status Dokumen -->
                                            <div class="mb-2">
                                                <label for="status_dokumen" class="form-label">Status Dokumen</label>
                                                <select name="status_dokumen" id="status_dokumen" class="form-select">
                                                    <option value="Perpanjangan">Perpanjangan</option>
                                                    <option value="Tetap">Tetap</option>
                                                </select>
                                            </div>

                                            <!-- Tanggal Terbit -->
                                            <div class="mb-2">
                                                <label for="tanggal_terbit" class="form-label">Tanggal Terbit</label>
                                                <input type="date" name="tanggal_terbit" id="tanggal_terbit"
                                                    class="form-control" required>
                                            </div>

                                            <!-- Expired -->
                                            <div class="mb-2">
                                                <label for="expired" class="form-label">Tanggal Expired</label>
                                                <input type="date" name="expired" id="expired" class="form-control">
                                            </div>

                                            <!-- Upload PDF -->
                                            <div class="mb-2">
                                                <label for="formFile" class="form-label">Upload PDF</label>
                                                <input type="file" name="pdf_upload" id="formFile"
                                                    class="form-control" accept="application/pdf" required>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- resources/views/dashboard.blade.php --}}
                    <div class="table-responsive" style="margin-bottom:10px;">
                        <table class="table table-striped table-bordered rounded-table sm" id="table-dokumen"
                            style="width: 100%;">
                            <thead class="table-dark">
                                <tr>
                                    <th style="font-size:11px;">No. Registrasi</th>
                                    <th style="font-size:11px;">No. Manual</th>
                                    <th style="font-size:11px;">Nama Perusahaan</th>
                                    <th style="font-size:11px;">No. Dokumen</th>
                                    <th style="font-size:11px;">Jenis Dokumen</th>
                                    <th style="font-size:11px;">Wilayah Kerja</th>
                                    <th style="font-size:11px;">Tanggal Terbit</th>
                                    <th style="font-size:11px;">Expired</th>
                                    <th style="font-size:11px;">Status</th>
                                    <th style="font-size:11px;">PIC</th>
                                    <th style="font-size:11px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dokumens as $dokumen)
                                    @php
                                        // Memeriksa apakah dokumen sudah expired
                                        $isExpired = \Carbon\Carbon::parse($dokumen->expired)->isPast();
                                        // Memeriksa apakah dokumen akan kedaluwarsa dalam 30 hari
                                        $isExpiringSoon =
                                            \Carbon\Carbon::parse($dokumen->expired)->isFuture() &&
                                            \Carbon\Carbon::parse($dokumen->expired)->diffInDays(now()) <= 30;
                                    @endphp
                                    <tr
                                        class="{{ $isExpired ? 'expired-warning' : ($isExpiringSoon ? 'expiring-soon' : '') }}">
                                        <td style="font-size:11px;">
                                            {{ $dokumen->no_registrasi_otomatis ?? 'Tidak Ada Data' }}</td>
                                        <td style="font-size:11px;">{{ $dokumen->no_registrasi }}</td>
                                        <td style="font-size:11px;">{{ $dokumen->nama_perusahaan }}</td>
                                        <td style="font-size:11px;">{{ $dokumen->no_dokumen }}</td>
                                        <td style="font-size:11px;">{{ $dokumen->jenis_dokumen }}</td>
                                        <td style="font-size:11px;">{{ $dokumen->wilayah_kerja }}</td>
                                        <td style="font-size:11px;">{{ $dokumen->tanggal_terbit }}</td>
                                        <td style="font-size:11px;">
                                            {{ \Carbon\Carbon::parse($dokumen->expired)->format('d-m-Y') }}</td>
                                        <td style="font-size:11px;">{{ $dokumen->status_dokumen }}</td>
                                        <td style="font-size:11px;">
                                            {{ $dokumen->pic ?? ($dokumen->created_by ?? 'Tidak Diketahui') }}
                                        </td>
                                        <td style="font-size:11px;">
                                            <a href="{{ route('dokumen.view-pdf', $dokumen->id) }}"
                                                class="btn btn-primary btn-sm" target="_blank">Lihat</a>
                                            @if (auth()->user()->role == 'admin')
                                                <a href="{{ route('dokumen.download', $dokumen->id) }}"
                                                    class="btn btn-success btn-sm">Unduh</a>
                                                <a href="{{ route('dokumen.edit-expired', $dokumen->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <script>
                            document.getElementById('status_dokumen').addEventListener('change', function() {
                                const expiredInput = document.getElementById('expired');

                                if (this.value === 'Tetap') {
                                    expiredInput.disabled = true;
                                    expiredInput.value = ''; // Kosongkan tanggal expired jika tidak berlaku
                                } else {
                                    expiredInput.disabled = false;
                                }
                            });

                            // Inisialisasi: jalankan saat halaman dimuat untuk set kondisi awal
                            document.addEventListener('DOMContentLoaded', function() {
                                const statusDokumen = document.getElementById('status_dokumen');
                                const expiredInput = document.getElementById('expired');

                                if (statusDokumen.value === 'Tetap') {
                                    expiredInput.disabled = true;
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('plugin-scripts')
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    @endsection

    @section('custom-scripts')
        <script>
            $(document).ready(function() {
                // Setup CSRF token for all AJAX requests
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#dokumenForm').submit(function(event) {
                    event.preventDefault(); // Prevent default form submission
                    console.log("Save button clicked"); // Debugging line

                    var formData = new FormData(this);
                    console.log([...formData]); // Log the form data

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('dokumen.store') }}', // Ensure this route is correct
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            console.log(data); // Log the response
                            $('#dokumenModal').modal('hide'); // Hide the modal
                            location.reload(); // Reload the page after success
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText); // Log error response
                        }
                    });
                });
            });
        </script>
    @endsection
