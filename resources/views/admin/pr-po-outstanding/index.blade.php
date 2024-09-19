<x-app-layout>
    <x-slot name="title">PR / PO Outstanding</x-slot>
    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif
    <x-card>
        <x-slot name="title">Semua Data</x-slot>
        {{-- <x-slot name="option">
            <div>
                <button class="btn btn-danger delete-all"><i class="fas fa-trash"></i> Hapus Semua Data CSV</button>
                <button class="btn btn-success add-csv"><i class="fas fa-upload"></i> Upload CSV</button>
            </div>
        </x-slot> --}}
        <div class="search-container row g-1 align-items-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" id="tableSearch" placeholder="Search..." class="form-control-5">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Material No</th>
                        <th scope="col">Description</th>
                        <th scope="col">MRP Controller</th>
                        <th scope="col">PG</th>
                        <th scope="col">No PR</th>
                        <th scope="col">Nomor PO</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">STATUS</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse ($data as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td> <!-- Nomor urut dengan $loop->iteration -->
                            <td>{{ $row['Material No'] ?? 'N/A' }}</td>
                            <td>{{ $row['Description'] ?? 'N/A' }}</td>
                            <td>{{ $row['MRP Controller'] ?? 'N/A' }}</td>
                            <td>{{ $row['Purchasing Group'] ?? 'N/A' }}</td>
                            <td>{{ $row['No PR'] ?? 'N/A' }}</td>
                            <td>{{ $row['Nomor PO'] ?? 'N/A' }}</td>
                            <td>{{ $row['Tgl Create PR'] ?? 'N/A' }}</td>
                            <td>{{ $row['STATUS'] ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </x-card>
    <link rel="stylesheet" href="{{ asset('css/srn.css') }}">
    <script>
        // Fungsi Pencarian
        document.getElementById('tableSearch').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var tableRows = document.querySelectorAll('#tableBody tr');

            tableRows.forEach(function(row) {
                var rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
    <x-slot name="script">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="{{ asset('dist/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('table').DataTable();
            });
        </script>
    </x-slot>
</x-app-layout>
