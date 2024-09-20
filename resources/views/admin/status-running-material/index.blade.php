<x-app-layout>
    <x-slot name="title">Status Running Material</x-slot>
    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif
    <x-card>
        <x-slot name="title">Semua Data</x-slot>
        @role('Admin')
            <div class="container mt-5">
                <h2>Send Email</h2>

                <!-- Display Success or Error Message -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Form to Send Email -->
                <form action="{{ route('admin.status-running-material.send-email') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Recipient Emails (separated by commas):</label>
                        <input type="text" class="form-control" id="email" name="emails" placeholder="email"
                            required>
                    </div>
                    <button type="submit" class="btn btn-success">Approve</button>
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </form>
            </div>
        @endrole
        <div class="search-container row g-1 align-items-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" id="tableSearch" placeholder="Search..." class="form-control-5">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <label for="mrpControlFilter" class="form-label">MRP Control:</label>
                <select id="mrpControlFilter" class="form-select form-select-lg mb-3"
                    aria-label=".form-select-lg example">
                    <option value="">All</option>
                    <option value="P03">P03</option>
                    <option value="P02">P02</option>
                    <option value="P04">P04</option>
                    <option value="P06">P06</option>
                    <option value="P05">P05</option>
                </select>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <label for="statusRunningMrpFilter" class="form-label">Status Running MRP:</label>
                <select id="statusRunningMrpFilter" class="form-select form-select-lg mb-3"
                    aria-label=".form-select-lg example">
                    <option value="">All</option>
                    <option value="CUKUP PO">CUKUP PO</option>
                    <option value="CREATE PR">CREATE PR</option>
                    <option value="CUKUP PR">CUKUP PR</option>
                    <option value="QOH > ROP">QOH > ROP</option>
                    <option value="CUKUP PR & PO">CUKUP PR & PO</option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Material</th>
                        <th scope="col">Material Description</th>
                        <th scope="col">MRP Control</th>
                        <th scope="col">UOM</th>
                        <th scope="col">ROP</th>
                        <th scope="col">MAX</th>
                        <th scope="col">QOH</th>
                        <th scope="col">QOR TO MAX</th>
                        <th scope="col">STATUS RUNNING MRP</th>
                        <th scope="col">REKOMENDASI</th>
                        <th scope="col">HARGA LAST PO (IDR)</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @foreach ($data as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td> <!-- Menggunakan $loop->iteration untuk nomor urut -->
                            <td>{{ $row['Material'] ?? 'N/A' }}</td>
                            <td>{{ $row['Material Description'] ?? 'N/A' }}</td>
                            <td>{{ $row['MRP Contrl'] ?? 'N/A' }}</td>
                            <td>{{ $row['UOM'] ?? 'N/A' }}</td>
                            <td>{{ $row['ROP'] ?? 'N/A' }}</td>
                            <td>{{ $row['MAX'] ?? 'N/A' }}</td>
                            <td>{{ $row['QOH'] ?? 'N/A' }}</td>
                            <td>{{ $row['QOR TO MAX'] ?? 'N/A' }}</td>
                            <td>{{ $row['STATUS RUNNING MRP'] ?? 'N/A' }}</td>
                            <td>{{ $row['REKOMENDASI'] ?? 'N/A' }}</td>
                            <td>{{ $row['HARGA LAST PO (IDR)'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <a href="{{ route('admin.download.excel') }}" class="btn btn-primary mt-3 mb-3">Download Excel</a>
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

        function filterTable() {
            var mrpControlValue = document.getElementById('mrpControlFilter').value;
            var statusRunningMrpValue = document.getElementById('statusRunningMrpFilter').value;
            var tableRows = document.querySelectorAll('#tableBody tr');

            tableRows.forEach(function(row) {
                var mrpControlText = row.children[3].textContent.trim();
                var statusRunningMrpText = row.children[9].textContent.trim();

                var showRow = true;

                if (mrpControlValue && mrpControlText !== mrpControlValue) {
                    showRow = false;
                }

                if (statusRunningMrpValue && statusRunningMrpText !== statusRunningMrpValue) {
                    showRow = false;
                }

                row.style.display = showRow ? '' : 'none';
            });
        }
        document.getElementById('mrpControlFilter').addEventListener('change', filterTable);
        document.getElementById('statusRunningMrpFilter').addEventListener('change', filterTable);
    </script>
    <x-slot name="script">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="{{ asset('dist/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('table').DataTable();
            });
            document.getElementById('statusRunningMrpFilter').addEventListener('change', function() {
                let selectedValue = this.value;
            });
        </script>
    </x-slot>
</x-app-layout>
