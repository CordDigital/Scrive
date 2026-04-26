@extends('admin.layouts.app')

@push('styles')
<style>
.page-wrap { padding: 28px; background: #f6f7fb; min-height: 100vh; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
.page-title { font-size: 20px; font-weight: 800; color: #1e1e2d; margin: 0; }

.stat-mini { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 22px; }
.stat-mini-card {
    background: #fff; border-radius: 14px; border: 1px solid #f0f0f5;
    padding: 16px 22px; display: flex; align-items: center; gap: 14px; flex: 1; min-width: 150px;
}
.stat-mini-icon { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; }
.stat-mini-val { font-size: 22px; font-weight: 800; color: #1e1e2d; line-height: 1; }
.stat-mini-lbl { font-size: 12px; color: #9ca3af; margin-top: 3px; }

.filter-bar { background: #fff; border-radius: 14px; border: 1px solid #f0f0f5; padding: 14px 20px; margin-bottom: 18px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.filter-bar input { flex: 1; min-width: 200px; border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 14px; font-size: 13px; outline: none; }
.filter-bar input:focus { border-color: #6366f1; }
.filter-bar select { border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 14px; font-size: 13px; outline: none; background: #fff; }
.btn-search { background: #6366f1; color: #fff; border: none; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; }

.users-table-wrap { background: #fff; border-radius: 16px; border: 1px solid #f0f0f5; overflow: hidden; }
.users-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.users-table th { padding: 12px 18px; text-align: left; font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid #f3f4f6; background: #fafafa; }
.users-table td { padding: 14px 18px; border-top: 1px solid #f3f4f6; color: #374151; vertical-align: middle; }
.users-table tr:hover td { background: #fafafa; }

.user-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; flex-shrink: 0; }
.avatar-admin    { background: #fef3c7; color: #b45309; }
.avatar-customer { background: #eef2ff; color: #6366f1; }

.role-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
.role-admin    { background: #fef3c7; color: #92400e; }
.role-customer { background: #eef2ff; color: #4338ca; }

.btn-del { background: #fee2e2; color: #dc2626; border: none; border-radius: 7px; padding: 5px 12px; font-size: 12px; font-weight: 600; cursor: pointer; }
.btn-del:hover { background: #fecaca; }
.btn-del:disabled { opacity: 0.4; cursor: not-allowed; }

.pagination-wrap { padding: 14px 20px; border-top: 1px solid #f3f4f6; }
</style>
@endpush

@section('content')
<div class="page-wrap">

    <div class="page-header">
        <h1 class="page-title"><i class="mdi mdi-account-group me-2" style="color:#a855f7;"></i>Users</h1>
        <a href="{{ route('admin.users.create') }}"
           style="display:inline-flex;align-items:center;gap:6px;background:#6366f1;color:#fff;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:700;text-decoration:none;">
            <i class="mdi mdi-account-plus"></i> Add User
        </a>
    </div>

    {{-- Stats --}}
    <div class="stat-mini">
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background:#fdf2f8; color:#a855f7;"><i class="mdi mdi-account-multiple"></i></div>
            <div>
                <div class="stat-mini-val">{{ number_format($totalUsers) }}</div>
                <div class="stat-mini-lbl">All Users</div>
            </div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background:#eef2ff; color:#6366f1;"><i class="mdi mdi-account"></i></div>
            <div>
                <div class="stat-mini-val">{{ number_format($totalCustomers) }}</div>
                <div class="stat-mini-lbl">Customers</div>
            </div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background:#fef3c7; color:#b45309;"><i class="mdi mdi-shield-account"></i></div>
            <div>
                <div class="stat-mini-val">{{ number_format($totalAdmins) }}</div>
                <div class="stat-mini-lbl">Admins</div>
            </div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background:#dcfce7; color:#16a34a;"><i class="mdi mdi-account-plus"></i></div>
            <div>
                <div class="stat-mini-val">{{ $thisMonthUsers }}</div>
                <div class="stat-mini-lbl">New This Month</div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#dcfce7; color:#166534; border-radius:10px; padding:12px 18px; margin-bottom:16px; font-size:13px; font-weight:600;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email…">
        <select name="role">
            <option value="">All Roles</option>
            <option value="user"  {{ request('role') == 'user'  ? 'selected' : '' }}>Customer</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        <button type="submit" class="btn-search"><i class="mdi mdi-magnify me-1"></i>Search</button>
        @if(request('search') || request('role'))
        <a href="{{ route('admin.users.index') }}" style="font-size:13px; color:#6b7280; text-decoration:none;">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="users-table-wrap">
        <table class="users-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Orders</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php $isAdmin = $user->role === 'admin'; @endphp
                <tr>
                    <td style="color:#9ca3af;">{{ $user->id }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div class="user-avatar {{ $isAdmin ? 'avatar-admin' : 'avatar-customer' }}">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600; color:#1e1e2d;">{{ $user->name }}</div>
                                <div style="font-size:12px; color:#9ca3af;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge {{ $isAdmin ? 'role-admin' : 'role-customer' }}">
                            <i class="mdi {{ $isAdmin ? 'mdi-shield-account' : 'mdi-account' }}"></i>
                            {{ $isAdmin ? 'Admin' : 'Customer' }}
                        </span>
                    </td>
                    <td style="color:#6b7280;">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        @php $ordersCount = \App\Models\Order::where('email', $user->email)->count(); @endphp
                        @if($ordersCount > 0)
                            <span style="font-weight:700; color:#1e1e2d;">{{ $ordersCount }}</span>
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               style="background:#eef2ff;color:#6366f1;border-radius:7px;padding:5px 12px;font-size:12px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                <i class="mdi mdi-pencil"></i> Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del"><i class="mdi mdi-delete"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#9ca3af; padding:40px;">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
        <div class="pagination-wrap">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
