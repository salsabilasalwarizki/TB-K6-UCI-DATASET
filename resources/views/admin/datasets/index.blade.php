@extends('layouts.admin')
@section('title', 'Dataset Review')
@section('page-title', 'Dataset Review')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Dataset Review</h2>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" 
                   placeholder="Search datasets..." value="{{ request('search') }}">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('admin.datasets.bulk-approve') }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="40"><input type="checkbox" id="selectAll"></th>
                            <th>Dataset Name</th>
                            <th>Donor</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($datasets as $dataset)
                        <tr>
                            <td>
                                @if($dataset->status === 'pending')
                                <input type="checkbox" name="dataset_ids[]" value="{{ $dataset->dataset_id }}">
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.datasets.review', $dataset) }}" class="text-decoration-none">
                                    {{ $dataset->name }}
                                </a>
                            </td>
                            <td>
                                @foreach($dataset->creators->take(1) as $creator)
                                    {{ $creator->name }}
                                @endforeach
                            </td>
                            <td>{{ $dataset->donated_date?->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $dataset->status === 'approved' ? 'success' : ($dataset->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($dataset->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.datasets.review', $dataset) }}" class="btn btn-sm btn-outline-primary">
                                    Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                No datasets found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($datasets->filter(fn($d) => $d->status === 'pending')->isNotEmpty())
            <div class="card-footer bg-light">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-check-circle me-1"></i> Bulk Approve Selected
                </button>
                <div class="float-end">
                    {{ $datasets->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Select all checkbox
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('input[name="dataset_ids[]"]').forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>
@endpush