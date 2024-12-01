<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="mt-5">
        <h2 class="mb-4">Log Aktivitas Toko</h2>
        @if($activityLogs->count() > 0)
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Riwayat Aktivitas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">Aktivitas</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Pengguna</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activityLogs as $log)
                                    <tr>
                                        <td>{{ $log->activity }}</td>
                                        <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                                        <td>{{ $log->user->fullname }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $activityLogs->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info" role="alert">
                Tidak ada log aktivitas yang tersedia.
            </div>
        @endif
    </div>    
    
</div>
