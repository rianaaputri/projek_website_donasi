<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h3 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-header p {
            color: rgba(255,255,255,0.8);
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu .menu-item {
            display: block;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        
        .sidebar-menu .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            padding-left: 35px;
        }
        
        .sidebar-menu .menu-item.active {
            background: rgba(255,255,255,0.2);
            border-right: 4px solid white;
            color: white;
        }
        
        .sidebar-menu .menu-item i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }
        
        .sidebar-logout {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            padding: 0 20px;
        }
        
        .logout-btn {
            width: 100%;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
        }
        
        .stats-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        
        .content-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }
        
        .progress {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .progress-bar {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 20px;
        }
        
        .recent-donation-item {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .recent-donation-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-heart me-2"></i>Kindify.id</h3>
            <p>Admin Panel</p>
            <!-- Display admin name -->
            @auth('admin')
                <small class="text-white-50">Welcome, {{ auth('admin')->user()->name }}</small>
            @endauth
        </div>
        
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item active">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-bullhorn"></i>
                Campaign Management
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-users"></i>
                Registered Users
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-hand-holding-heart"></i>
                Donations
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-user-shield"></i>
                Add Admin
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                Reports
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                Settings
            </a>
        </nav>
        
        <div class="sidebar-logout">
            <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('Yakin ingin logout?')">
    @csrf
    <button type="submit" class="dropdown-item text-danger">Logout</button>
</form>

        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
            <div class="text-muted">{{ date('d F Y') }}</div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stats-card text-white" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Campaigns</h5>
                                <h2>{{ $stats['total_campaigns'] ?? 0 }}</h2>
                            </div>
                            <i class="fas fa-bullhorn fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stats-card text-white" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Donations</h5>
                                <h2>{{ $stats['total_donations'] ?? 0 }}</h2>
                            </div>
                            <i class="fas fa-hand-holding-heart fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stats-card text-white" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Collected</h5>
                                <h2>Rp {{ number_format($stats['total_collected'] ?? 0, 0, ',', '.') }}</h2>
                            </div>
                            <i class="fas fa-money-bill fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stats-card text-white" style="background: linear-gradient(45deg, #43e97b, #38f9d7);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Users</h5>
                                <h2>{{ $stats['total_users'] ?? 0 }}</h2>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card content-card">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Recent Campaigns</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Campaign</th>
                                        <th>Target</th>
                                        <th>Collected</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recent_campaigns as $campaign)
                                        @php
                                            $progress = $campaign->target_amount > 0 ? ($campaign->current_amount / $campaign->target_amount) * 100 : 0;
                                            $progress = min($progress, 100);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($campaign->image)
                                                        <img src="{{ asset('storage/' . $campaign->image) }}" 
                                                             class="rounded me-2" 
                                                             style="width:40px;height:40px;object-fit:cover;" 
                                                             alt="{{ $campaign->title }}">
                                                    @else
                                                        <div class="bg-primary rounded me-2" style="width:40px;height:40px; background: linear-gradient(45deg, #667eea, #764ba2) !important;"></div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ Str::limit($campaign->title, 20) }}</strong>
                                                        <br><small class="text-muted">{{ $campaign->category ?? 'General' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</td>
                                            <td>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar" style="width: {{ $progress }}%"></div>
                                                </div>
                                                <small>{{ number_format($progress, 1) }}%</small>
                                            </td>
                                            <td>
                                                @if($campaign->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($campaign->status === 'completed')
                                                    <span class="badge bg-primary">Completed</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <br>Belum ada campaign
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card content-card">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Donations</h5>
                    </div>
                    <div class="card-body">
                        @forelse($recent_donations as $donation)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 recent-donation-item">
                                <div>
                                    <strong>{{ $donation->donor_name ?? 'Anonymous' }}</strong>
                                    <br><small class="text-muted">{{ Str::limit($donation->campaign->title ?? 'Unknown Campaign', 25) }}</small>
                                </div>
                                <div class="text-end">
                                    <strong class="text-success">Rp {{ number_format($donation->amount, 0, ',', '.') }}</strong>
                                    <br><small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-heart fa-2x mb-2"></i>
                                <br>Belum ada donasi
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add interactivity
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if(!this.href || this.href === '#') {
                    e.preventDefault();
                }
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // FIXED: Proper logout confirmation and form submission
        function confirmLogout() {
            if(confirm('Yakin mau logout?')) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>
</body>
</html>