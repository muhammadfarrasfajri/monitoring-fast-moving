<x-app-layout>
    <x-slot name="title">Kontrak</x-slot>

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
            <div class="col-12 col-md-6 col-lg-6">
                <label for="nokontrakFilter" class="form-label filter-label">No Kontrak:</label>
                <select id="nokontrakFilter" class="form-select form-select-lg mb-3"
                    aria-label=".form-select-lg example">
                    <option value="">All</option>
                    <option value="-">Tidak Ada Kontrak</option>
                    <option value="yes">Ada Kontrak</option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="kontrakTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Material</th>
                        <th scope="col">Description</th>
                        <th scope="col">UOM</th>
                        <th scope="col">ABC</th>
                        <th scope="col">MRP Type</th>
                        <th scope="col">MRP Control</th>
                        <th scope="col">PG</th>
                        <th scope="col">MG</th>
                        <th scope="col">ROP</th>
                        <th scope="col">MAX</th>
                        <th scope="col">QOH</th>
                        <th scope="col">No Kontrak</th>
                        <th scope="col">QOH Kontrak</th>
                        <th scope="col">Validitas END</th>
                        <th scope="col">Sisa Validity (Hari)</th>
                        <th scope="col">PR Kontrak</th>
                        <th scope="col">TGL PR</th>
                        <th scope="col">REMINDER</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse ($data as $row)
                        @php
                            $sisaValidity = $row['Sisa Validity (Hari)'] ?? null;
                            $reminder = $row['REMINDER'] ?? null;
                            // Set default warna jika kosong
                            $color = '';

                            if (!empty($reminder)) {
                                // Waktu Kontrak Habis (Merah)
                                if ($sisaValidity <= 0) {
                                    $color = 'background-color: red; color: white;';
                                }
                                // Waktu Kontrak Akan Habis (Kuning)
                                elseif ($sisaValidity > 0 && $sisaValidity <= 90) {
                                    $color = 'background-color: yellow;';
                                }
                                // Waktu Kontrak Berlaku (Hijau)
                                elseif ($sisaValidity > 30) {
                                    $color = 'background-color: green; color: white;';
                                }
                            }
                        @endphp
                        <tr data-no-kontrak="{{ $row['NO KONTRAK'] ?? '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row['Material'] ?? '-' }}</td>
                            <td>{{ $row['Material Description'] ?? '-' }}</td>
                            <td>{{ $row['UOM'] ?? '-' }}</td>
                            <td>{{ $row['ABC'] ?? '-' }}</td>
                            <td>{{ $row['MRP Type'] ?? '-' }}</td>
                            <td>{{ $row['MRP Contrl'] ?? '-' }}</td>
                            <td>{{ $row['PG'] ?? '-' }}</td>
                            <td>{{ $row['MG'] ?? '-' }}</td>
                            <td>{{ $row['QOH Update'] ?? '-' }}</td>
                            <td>{{ $row['ROP'] ?? '-' }}</td>
                            <td>{{ $row['MAX'] ?? '-' }}</td>
                            <td>{{ $row['NO KONTRAK'] ?? '-' }}</td>
                            <td>{{ $row['QOH Kontrak'] ?? '-' }}</td>
                            <td>{{ $row['VALIDITY END'] ?? '-' }}</td>
                            <td>{{ $row['Sisa Validity (Hari)'] ?? '-' }}</td>
                            <td>{{ $row['PR KONTRAK'] ?? '-' }}</td>
                            <td>{{ $row['TGL PR'] ?? '-' }}</td>
                            <!-- Kolom REMINDER dengan conditional color -->
                            <td style="{{ $color }}">{{ $reminder ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="19">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>
    </x-card>

    <link rel="stylesheet" href="{{ asset('css/srn.css') }}">
    <x-slot name="script">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="{{ asset('dist/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script>
            //untuk melakukan search
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

            $(document).ready(function() {
                // Menyembunyikan baris yang memiliki nilai '-' di kolom no_kontrak
                $('tbody tr').each(function() {
                    var noKontrakValue = $(this).find('td:nth-child(12)')
                        .text(); // Kolom ke-12 adalah kolom no_kontrak
                    if (noKontrakValue.trim() === '-') {
                        $(this).hide();
                    }
                });
                // Fungsi untuk menyaring baris berdasarkan nilai dropdown
                $(document).ready(function() {
                    // Fungsi untuk menyaring baris berdasarkan nilai dropdown
                    $('#nokontrakFilter').on('change', function() {
                        var selectedValue = $(this).val();

                        // Tampilkan semua baris jika opsi "All" dipilih
                        if (selectedValue === "") {
                            $('tbody tr').show();
                        }
                        // Sembunyikan baris yang memiliki tanda '-' (Tidak Ada Kontrak)
                        else if (selectedValue === "-") {
                            $('tbody tr').hide().filter(function() {
                                return $(this).find('td:nth-child(13)').text().trim() === "-";
                            }).show();
                        }
                        // Tampilkan hanya baris yang memiliki kontrak (Tidak ada tanda '-')
                        else if (selectedValue === "yes") {
                            $('tbody tr').hide().filter(function() {
                                return $(this).find('td:nth-child(13)').text().trim() !== "-";
                            }).show();
                        }
                    });
                });
            });
        </script>
    </x-slot>
</x-app-layout>
