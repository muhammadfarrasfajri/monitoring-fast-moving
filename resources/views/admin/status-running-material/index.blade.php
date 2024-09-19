<x-app-layout>
    <x-slot name="title">Status Running Material</x-slot>
    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif
    <x-card>
        <x-slot name="title">Semua Data</x-slot>
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
        <div class="search-container d-flex flex-wrap align-items-end justify-content-between">
            <div class="col-12 col-md-6 col-lg-2 mb-3">
                <label for="mrpControlFilter" class="form-label">MRP Control:</label>
                <select id="mrpControlFilter" class="form-select form-select-lg" aria-label=".form-select-lg example">
                    <option value="">All</option>
                    <option value="P03">P03</option>
                    <option value="P02">P02</option>
                    <option value="P04">P04</option>
                    <option value="P06">P06</option>
                    <option value="P05">P05</option>
                </select>
            </div>
            <div class="col-12 col-md-6 col-lg-2 mb-3">
                <label for="statusRunningMrpFilter" class="form-label">Status Running MRP:</label>
                <select id="statusRunningMrpFilter" class="form-select form-select-lg"
                    aria-label=".form-select-lg example">
                    <option value="">All</option>
                    <option value="CUKUP PO">CUKUP PO</option>
                    <option value="CREATE PR">CREATE PR</option>
                    <option value="CUKUP PR">CUKUP PR</option>
                    <option value="QOH > ROP">QOH > ROP</option>
                    <option value="CUKUP PR & PO">CUKUP PR & PO</option>
                </select>
            </div>
            <!-- Tombol Filter di sini -->
            <div class="col-12 col-md-6 col-lg-8 d-flex justify-content-end align-items-end mb-3">
                <button class="btn btn-primary">Filter</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="myTable">
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
        <a href="{{ route('admin.download.excel') }}" class="btn btn-primary">Download Excel</a>
    </x-card>
    <link rel="stylesheet" href="{{ asset('css/srn.css') }}">
    <script>
        // Fungsi Pencarian
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
        <link
            href="https://cdn.datatables.net/v/dt/dt-2.1.6/af-2.7.0/b-3.1.2/date-1.5.4/fh-4.0.1/r-3.0.3/sc-2.4.3/sl-2.1.0/datatables.min.css"
            rel="stylesheet">
        <script
            src="https://cdn.datatables.net/v/dt/dt-2.1.6/af-2.7.0/b-3.1.2/date-1.5.4/fh-4.0.1/r-3.0.3/sc-2.4.3/sl-2.1.0/datatables.min.js">
        </script>
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
