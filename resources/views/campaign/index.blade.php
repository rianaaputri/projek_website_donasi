@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-blue: #3b82f6;
        --light-blue: #dbeafe;
        --soft-blue: #eff6ff;
        --blue-50: #f8fafc;
        --blue-100: #e2e8f0;
        --blue-200: #cbd5e1;
        --blue-600: #2563eb;
        --blue-700: #1d4ed8;
        --success-green: #22c55e;
        --danger-red: #ef4444;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --shadow-soft: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow-medium: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-large: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }

    body {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--blue-50) 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--text-primary);
    }

    .campaign-wrapper {
        min-height: 100vh;
        padding: 2rem 0;
    }

    .main-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-large);
        border: 1px solid var(--blue-100);
        overflow: hidden;
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--light-blue) 100%);
        padding: 2rem;
        border-bottom: 1px solid var(--blue-100);
    }

    .page-title {
        color: var(--primary-blue);
        font-weight: 700;
        font-size: 1.875rem;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 0;
        font-weight: 400;
    }

    .card-body-custom {
        padding: 2rem;
    }

    /* Buttons */
    .btn-primary-soft {
        background: var(--primary-blue);
        border: 1px solid var(--primary-blue);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-soft);
    }

    .btn-primary-soft:hover {
        background: var(--blue-700);
        border-color: var(--blue-700);
        color: white;
        transform: translateY(-1px);
        box-shadow: var(--shadow-medium);
    }

    .btn-success-soft {
        background: var(--success-green);
        border: 1px solid var(--success-green);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.8125rem;
        transition: all 0.2s ease;
    }

    .btn-success-soft:hover {
        background: #16a34a;
        border-color: #16a34a;
        color: white;
        transform: translateY(-1px);
    }

    .btn-danger-soft {
        background: var(--danger-red);
        border: 1px solid var(--danger-red);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.8125rem;
        transition: all 0.2s ease;
    }

    .btn-danger-soft:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: white;
        transform: translateY(-1px);
    }

    /* Alert */
    .alert-success-soft {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        font-weight: 500;
    }

    /* Stats Card */
    .stats-card {
        background: var(--soft-blue);
        border: 1px solid var(--light-blue);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        text-align: center;
    }

    .stats-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 0.25rem;
    }

    .stats-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* Table Styles */
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--blue-100);
    }

    .table-custom {
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: var(--soft-blue);
        color: var(--primary-blue);
        font-weight: 600;
        font-size: 0.875rem;
        padding: 1.25rem 1rem;
        border: none;
        letter-spacing: 0.025em;
    }

    .table-custom tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        color: var(--text-primary);
        font-size: 0.875rem;
        border-top: 1px solid var(--blue-100);
    }

    .table-custom tbody tr {
        transition: background-color 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background: var(--blue-50);
    }

    /* Campaign Title */
    .campaign-title {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9375rem;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    /* Progress Bar */
    .progress-soft {
        height: 6px;
        background: var(--blue-100);
        border-radius: 3px;
        margin-bottom: 0.5rem;
    }

    .progress-bar-soft {
        background: linear-gradient(90deg, var(--primary-blue), var(--blue-600));
        border-radius: 3px;
        transition: width 0.4s ease;
    }

    .progress-text {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* Badges */
    .badge-category {
        background: var(--light-blue);
        color: var(--primary-blue);
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
    }

    .status-active {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .status-inactive {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .status-completed {
        background: #faf5ff;
        color: #9333ea;
        border: 1px solid #e9d5ff;
    }

    /* Amount Text */
    .amount-text {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: var(--blue-50);
        border: 2px dashed var(--blue-200);
        border-radius: 12px;
        margin: 1rem 0;
    }

    .empty-icon {
        width: 60px;
        height: 60px;
        background: var(--light-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.5rem;
    }

    .empty-title {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 1.125rem;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        font-size: 0.9375rem;
    }

    /* Search Section - FIXED SIZE */
    .search-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--blue-100);
        margin-bottom: 2rem;
    }

    .search-filters-row {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 300px;
    }

    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 0.875rem;
        z-index: 10;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 0.75rem 0.75rem 2.5rem;
        border: 1px solid var(--blue-200);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex: 1;
        justify-content: flex-end;
    }

    .filter-select {
        min-width: 130px;
        max-width: 150px;
        padding: 0.75rem;
        border: 1px solid var(--blue-200);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .filter-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .btn-outline-primary {
        border: 1px solid var(--primary-blue);
        color: var(--primary-blue);
        background: transparent;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-outline-primary:hover {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: white;
        transform: translateY(-1px);
    }

    /* Action Bar - IMPROVED */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    /* Button Groups */
    .btn-group-custom {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    /* Improved Button Transitions */
    .btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    /* No Data Found State */
    .no-data-state {
        text-align: center;
        padding: 3rem 2rem;
        background: #fff3cd;
        border: 2px dashed #ffc107;
        border-radius: 12px;
        margin: 1rem 0;
    }

    .no-data-icon {
        width: 60px;
        height: 60px;
        background: #fff3cd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.5rem;
        color: #856404;
    }

    .no-data-title {
        color: #856404;
        font-weight: 600;
        font-size: 1.125rem;
        margin-bottom: 0.5rem;
    }

    .no-data-text {
        color: #856404;
        margin-bottom: 1.5rem;
        font-size: 0.9375rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .campaign-wrapper {
            padding: 1rem 0;
        }

        .card-header-custom,
        .card-body-custom {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .table-custom thead th,
        .table-custom tbody td {
            padding: 1rem 0.75rem;
        }

        .btn-group-custom {
            flex-direction: column;
        }

        .btn-group-custom .btn {
            width: 100%;
        }

        .search-filters-row {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-wrapper {
            min-width: auto;
            max-width: none;
            flex: none;
        }

        .filter-group {
            flex-direction: column;
            align-items: stretch;
            justify-content: stretch;
        }

        .filter-select {
            min-width: auto;
            max-width: none;
        }

        .action-bar {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @media (max-width: 576px) {
        .card-header-custom,
        .card-body-custom {
            padding: 1.25rem;
        }

        .stats-card {
            margin-bottom: 1rem;
        }
    }
</style>

<div class="campaign-wrapper">
    <div class="container">
        <div class="main-card">
            <!-- Header -->
            <div class="card-header-custom">
                <div class="text-center">
                    <h1 class="page-title">Manajemen Campaign</h1>
                    <p class="page-subtitle">Kelola dan pantau semua campaign fundraising Anda dengan mudah</p>
                </div>
            </div>

            <!-- Body -->
            <div class="card-body-custom">
                <!-- Success Alert -->
                @if (session('success'))
                    <div class="alert alert-success-soft d-flex align-items-center mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Action Bar -->
                <div class="action-bar">
                    <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary-soft">
                        <i class="fas fa-plus me-2"></i>
                        Buat Campaign Baru
                    </a>
                    <div class="stats-card">
                        <div class="stats-number">{{ $campaigns->count() }}</div>
                        <div class="stats-label">Total Campaign</div>
                    </div>
                </div>

                <!-- Search Filters -->
                <div class="search-section">
                    <div class="search-filters-row">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" 
                                   class="search-input" 
                                   id="searchInput"
                                   placeholder="Cari campaign...">
                        </div>
                        
                        <div class="filter-group">
                            <select class="filter-select" id="categoryFilter">
                                <option value="">Semua Kategori</option>
                                <option value="bencana">Bencana</option>
                                <option value="tempat ibadah">Tempat Ibadah</option>
                                <option value="pendidikan">Pendidikan</option>
                                <option value="kesehatan">Kesehatan</option>
                                <option value="sosial">Sosial</option>
                            </select>
                            
                            <select class="filter-select" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="completed">Completed</option>
                            </select>
                            
                            <button class="btn btn-outline-primary" id="resetFilters">
                                <i class="fas fa-refresh me-1"></i>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Campaign</th>
                                    <th>Kategori</th>
                                    <th>Target Dana</th>
                                    <th>Terkumpul</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($campaigns as $c)
                                    <tr class="campaign-row" 
                                        data-title="{{ strtolower($c->title) }}"
                                        data-category="{{ strtolower($c->category) }}"
                                        data-status="{{ strtolower($c->status) }}">
                                        <td style="min-width: 200px;">
                                            <div class="campaign-title">{{ $c->title }}</div>
                                            <div class="progress-soft">
                                                <div class="progress-bar-soft" 
                                                     style="width: {{ $c->target_amount > 0 ? min(($c->collected_amount / $c->target_amount) * 100, 100) : 0 }}%">
                                                </div>
                                            </div>
                                            <div class="progress-text">
                                                {{ $c->target_amount > 0 ? number_format(min(($c->collected_amount / $c->target_amount) * 100, 100), 1) : 0 }}% tercapai
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-category">{{ ucfirst($c->category) }}</span>
                                        </td>
                                        <td>
                                            <div class="amount-text">Rp {{ number_format($c->target_amount, 0, ',', '.') }}</div>
                                        </td>
                                        <td>
                                            <div class="amount-text">Rp {{ number_format($c->collected_amount, 0, ',', '.') }}</div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $c->status }}">
                                                {{ ucfirst($c->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group-custom">
                                                <a href="{{ route('admin.campaigns.edit', $c->id) }}" 
                                                   class="btn btn-success-soft btn-sm">
                                                    <i class="fas fa-edit me-1"></i>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.campaigns.destroy', $c->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            onclick="return confirm('Yakin ingin menghapus campaign ini?')"
                                                            class="btn btn-danger-soft btn-sm">
                                                        <i class="fas fa-trash me-1"></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyState">
                                        <td colspan="6" class="p-0">
                                            <div class="empty-state">
                                                <div class="empty-icon">
                                                    <i class="fas fa-inbox" style="color: var(--primary-blue);"></i>
                                                </div>
                                                <h5 class="empty-title">Belum Ada Campaign</h5>
                                                <p class="empty-text">
                                                    Mulai buat campaign pertama Anda untuk menggalang dana dan mencapai tujuan yang bermakna.
                                                </p>
                                                <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary-soft">
                                                    <i class="fas fa-plus me-2"></i>
                                                    Buat Campaign Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                
                                <!-- No Data Found State (Hidden by default) -->
                                <tr id="noDataState" style="display: none;">
                                    <td colspan="6" class="p-0">
                                        <div class="no-data-state">
                                            <div class="no-data-icon">
                                                <i class="fas fa-search"></i>
                                            </div>
                                            <h5 class="no-data-title">Data yang dicari tidak ada dalam daftar</h5>
                                            <p class="no-data-text">
                                                Coba ubah kata kunci pencarian atau filter yang Anda gunakan, atau periksa kembali ejaan kata kunci.
                                            </p>
                                            <button class="btn btn-outline-primary" onclick="resetAllFilters()">
                                                <i class="fas fa-refresh me-1"></i>
                                                Reset Pencarian
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const resetFilters = document.getElementById('resetFilters');
    const campaignRows = document.querySelectorAll('.campaign-row');
    const emptyState = document.getElementById('emptyState');
    const noDataState = document.getElementById('noDataState');

    // Search and filter function
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedCategory = categoryFilter.value.toLowerCase();
        const selectedStatus = statusFilter.value.toLowerCase();

        let visibleCount = 0;
        const hasData = campaignRows.length > 0;

        campaignRows.forEach(row => {
            const title = row.dataset.title || '';
            const category = row.dataset.category || '';
            const status = row.dataset.status || '';

            const matchesSearch = searchTerm === '' || title.includes(searchTerm);
            const matchesCategory = selectedCategory === '' || category === selectedCategory;
            const matchesStatus = selectedStatus === '' || status === selectedStatus;

            if (matchesSearch && matchesCategory && matchesStatus) {
                row.style.display = '';
                visibleCount++;
                // Add animation
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, 50);
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide appropriate states
        if (hasData) {
            if (visibleCount === 0) {
                // Show no data found state
                emptyState.style.display = 'none';
                noDataState.style.display = '';
            } else {
                // Hide both states
                emptyState.style.display = 'none';
                noDataState.style.display = 'none';
            }
        } else {
            // Show empty state (no campaigns at all)
            emptyState.style.display = '';
            noDataState.style.display = 'none';
        }
    }

    // Event listeners
    searchInput.addEventListener('input', filterTable);
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            filterTable();
            // Add search button animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    }
    
    // Allow Enter key to trigger search
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            filterTable();
        }
    });
    
    categoryFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Reset filters function
    function resetAllFilters() {
        searchInput.value = '';
        categoryFilter.value = '';
        statusFilter.value = '';
        filterTable();
    }

    // Make resetAllFilters globally accessible
    window.resetAllFilters = resetAllFilters;

    // Reset filters button
    resetFilters.addEventListener('click', function() {
        resetAllFilters();
        
        // Add animation to reset button
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });

    // Add hover effects to table rows
    campaignRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Button ripple effect
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Initial filter to set up proper state
    filterTable();
});
</script>

<style>
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}
</style>

@endsection