@extends('layouts.base')

@section('content')
<div class="container mt-5">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Daftar Lokasi</h4>
            <a href="{{ route('wilayah.index') }}" class="btn btn-light btn-sm">
                <i class="fa fa-map"></i> Lihat Peta
            </a>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle text-center" id="lokasiTable">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lokasi as $item)
                        <tr id="lokasi-{{ $item->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->deskripsi ?? '-' }}</td>
                            <td>{{ $item->latitude }}</td>
                            <td>{{ $item->longitude }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="hapusLokasi({{ $item->id }})">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    @if ($lokasi->isEmpty())
                        <tr>
                            <td colspan="6" class="text-muted">Belum ada data lokasi.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function hapusLokasi(id) {
    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ url('lokasi') }}/" + id, {
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire(
                    'Berhasil!',
                    data.message,
                    'success'
                )
                document.getElementById('lokasi-' + id).remove();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error!',
                    'Data gagal dihapus!',
                    'error'
                )
            });
        }
    })
}
</script>
@endsection
