<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DAFTAR HADIR</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Cari kehadiran..." />
                </div>
                <div class="col-12 col-md-8">
                    <div class="d-flex justify-content-between flex-wrap">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            <i class="fas fa-user-plus"></i> ABSEN
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Photo</th>
                            <th>Lokasi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $index => $attendance)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $attendance->user->fullname }}</td>
                                <td>{{ $attendance->status }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $attendance->photo) }}" alt="Foto Absensi" width="90" height="90" class="rounded" style="object-fit: cover;">
                                </td>                                
                                <td>
                                    {{ $attendance->location }}
                                    @if($attendance->latitude && $attendance->longitude)
                                        <div wire:ignore class="rounded-5" id="map-{{ $attendance->id }}" style="width: 100%; height: 200px;"></div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                var map = L.map('map-{{ $attendance->id }}').setView([{{ $attendance->latitude }}, {{ $attendance->longitude }}], 15);
                                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                    attribution: '&copy; OpenStreetMap contributors'
                                                }).addTo(map);

                                                L.marker([{{ $attendance->latitude }}, {{ $attendance->longitude }}]).addTo(map)
                                                    .bindPopup('Lokasi: {{ addslashes($attendance->location) }}')
                                                    .openPopup();
                                            });
                                        </script>
                                    @else
                                        <p>Koordinat lokasi tidak tersedia.</p>
                                    @endif
                                </td>                                                  
                                <td>{{ $attendance->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $attendances->links() }} <!-- Pagination -->
            </div>
        </div>
    </div>

    <!-- Modal Form Absensi -->

    <!-- Modal Form Absensi -->
    <div wire:ignore class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Form Absensi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" wire:poll.5s>
                    <form wire:submit.prevent="recordAttendance" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Absensi</label>
                            <select wire:model="status" class="form-select" required>
                                <option value="" disabled>Pilih Status</option>
                                <option value="Masuk">Masuk</option>
                                <option value="Keluar">Keluar</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto</label>
                            <input type="file" wire:model="photo" id="photo" class="form-control" required>
                            @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi</label>
                            <input type="text" wire:model.live="location" id="location" class="form-control" required readonly>
                            @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Container untuk peta -->
                        <div wire:ignore id="map-container" style="width: 100%; height: 200px;" class="rounded mb-3"></div>

                        <div class="my-3">
                            <button type="submit" class="btn btn-primary">SUBMIT</button>
                        </div>
                    </form>
                    <div class="modal-footer my-2">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-close"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi peta setelah modal ditampilkan
        document.getElementById('staticBackdrop').addEventListener('shown.bs.modal', function () {
            // Inisialisasi peta di dalam modal
            const mapContainer = document.getElementById('map-container');
            const map = L.map(mapContainer).setView([0, 0], 13); // Koordinat default dan zoom level

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Cek izin geolokasi dan tampilkan lokasi pengguna
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLatitude = position.coords.latitude;
                    const userLongitude = position.coords.longitude;

                    // Perbarui peta dengan lokasi pengguna
                    map.setView([userLatitude, userLongitude], 13);
                    L.marker([userLatitude, userLongitude]).addTo(map)
                        .bindPopup('Lokasi Anda')
                        .openPopup();
                }, function(error) {
                    alert('Gagal mendapatkan lokasi: ' + error.message);
                });
            } else {
                alert('Geolokasi tidak didukung di browser Anda.');
            }
        });
    </script>



    <script>
        document.getElementById('staticBackdrop').addEventListener('shown.bs.modal', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        const location = `${latitude},${longitude}`;

                        // Update Livewire component
                        @this.set('location', location);

                        // Update input field directly (for backup)
                        document.getElementById('location').value = location;

                        // Initialize map
                        const mapContainer = document.getElementById('map-container');
                        mapContainer.innerHTML = ''; // Clear existing map
                        const map = L.map(mapContainer).setView([latitude, longitude], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        L.marker([latitude, longitude]).addTo(map)
                            .bindPopup('Lokasi Anda Sekarang')
                            .openPopup();
                    },
                    (error) => {
                        console.error('Error mendapatkan lokasi:', error.message);
                        alert('Gagal mendapatkan lokasi: ' + error.message);
                    }
                );
            } else {
                alert('Fitur geolokasi tidak tersedia di browser ini.');
            }
        });
    </script>
</div>