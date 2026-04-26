@extends('admin.layouts.app')

@push('styles')
<style>
.uf-wrap { padding: 28px; background: #f1f5f9; min-height: 100vh; }
.uf-back { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #64748b; text-decoration: none; margin-bottom: 20px; }
.uf-back:hover { color: #6366f1; }
.uf-title { font-size: 20px; font-weight: 900; color: #1e293b; margin: 0 0 22px; }

.uf-card { background: #fff; border-radius: 18px; border: 1px solid #e8eaf0; overflow: hidden; max-width: 640px; }
.uf-card-head { padding: 20px 28px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
.uf-card-title { font-size: 15px; font-weight: 800; color: #1e293b; }
.uf-card-body { padding: 28px; display: flex; flex-direction: column; gap: 20px; }

.user-meta { display: flex; align-items: center; gap: 14px; background: #f8fafc; border-radius: 12px; padding: 14px 16px; }
.user-meta-av { width: 46px; height: 46px; border-radius: 50%; background: #eef2ff; color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 900; flex-shrink: 0; }
.user-meta-name { font-size: 15px; font-weight: 800; color: #1e293b; }
.user-meta-email { font-size: 12px; color: #94a3b8; }

.form-grp { display: flex; flex-direction: column; gap: 6px; }
.form-lbl { font-size: 13px; font-weight: 700; color: #374151; }
.form-lbl span { color: #f43f5e; margin-left: 2px; }
.form-inp {
    border: 1px solid #e2e8f0; border-radius: 10px;
    padding: 10px 14px; font-size: 14px; color: #1e293b;
    outline: none; transition: border .2s, box-shadow .2s; width: 100%;
}
.form-inp:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
.form-inp.is-invalid { border-color: #f43f5e; }
.form-err { font-size: 12px; color: #f43f5e; font-weight: 600; margin-top: 2px; }
.form-hint { font-size: 12px; color: #94a3b8; margin-top: 2px; }

.role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.role-opt { position: relative; }
.role-opt input[type=radio] { position: absolute; opacity: 0; }
.role-opt label {
    display: flex; align-items: center; gap: 12px;
    border: 2px solid #e2e8f0; border-radius: 12px; padding: 14px 16px;
    cursor: pointer; transition: border .2s, background .2s;
}
.role-opt input[type=radio]:checked + label { border-color: #6366f1; background: #eef2ff; }
.role-opt-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
.role-opt-name { font-size: 14px; font-weight: 800; color: #1e293b; }
.role-opt-sub  { font-size: 11px; color: #94a3b8; margin-top: 2px; }

.pw-wrap { position: relative; }
.pw-wrap .form-inp { padding-right: 42px; }
.pw-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 18px; }

.pw-section-toggle { display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none; }
.pw-section-toggle input { width: 16px; height: 16px; accent-color: #6366f1; cursor: pointer; }
.pw-section-toggle span { font-size: 13px; font-weight: 700; color: #374151; }
.pw-fields { display: none; flex-direction: column; gap: 16px; margin-top: 4px; }
.pw-fields.open { display: flex; }

.uf-actions { display: flex; align-items: center; gap: 12px; padding: 18px 28px; border-top: 1px solid #f1f5f9; }
.btn-save { background: #6366f1; color: #fff; border: none; border-radius: 10px; padding: 10px 24px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background .2s; }
.btn-save:hover { background: #4f46e5; }
.btn-cancel { color: #64748b; font-size: 13px; font-weight: 600; text-decoration: none; }
.btn-del { background: #fee2e2; color: #dc2626; border: none; border-radius: 10px; padding: 10px 18px; font-size: 13px; font-weight: 700; cursor: pointer; margin-left: auto; }
.btn-del:hover { background: #fecaca; }
</style>
@endpush

@section('content')
<div class="uf-wrap">

    <a href="{{ route('admin.users.index') }}" class="uf-back">
        <i class="mdi mdi-arrow-left"></i> Back to Users
    </a>

    <h1 class="uf-title">Edit User</h1>

    <div class="uf-card">
        <div class="uf-card-head">
            <div class="uf-card-title"><i class="mdi mdi-account-edit me-2" style="color:#6366f1;"></i>Edit Details</div>
            <span style="font-size:12px;color:#94a3b8;">ID #{{ $user->id }}</span>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="uf-card-body">

                {{-- User meta --}}
                <div class="user-meta">
                    <div class="user-meta-av">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div>
                        <div class="user-meta-name">{{ $user->name }}</div>
                        <div class="user-meta-email">Joined {{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                </div>

                {{-- Name --}}
                <div class="form-grp">
                    <label class="form-lbl"><i class="mdi mdi-account me-1" style="color:#6366f1;"></i>Full Name <span>*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="form-inp {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           placeholder="Full name">
                    @error('name') <div class="form-err">{{ $message }}</div> @enderror
                </div>

                {{-- Email --}}
                <div class="form-grp">
                    <label class="form-lbl"><i class="mdi mdi-email me-1" style="color:#6366f1;"></i>Email Address <span>*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="form-inp {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           placeholder="Email address">
                    @error('email') <div class="form-err">{{ $message }}</div> @enderror
                </div>

                {{-- Role --}}
                <div class="form-grp">
                    <label class="form-lbl"><i class="mdi mdi-shield-account me-1" style="color:#6366f1;"></i>Role <span>*</span></label>
                    <div class="role-grid">
                        <div class="role-opt">
                            <input type="radio" name="role" id="role_customer" value="user"
                                   {{ old('role', $user->role) === 'user' ? 'checked' : '' }}>
                            <label for="role_customer">
                                <div class="role-opt-icon" style="background:#eef2ff;color:#6366f1;"><i class="mdi mdi-account"></i></div>
                                <div>
                                    <div class="role-opt-name">Customer</div>
                                    <div class="role-opt-sub">Regular store user</div>
                                </div>
                            </label>
                        </div>
                        <div class="role-opt">
                            <input type="radio" name="role" id="role_admin" value="admin"
                                   {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}>
                            <label for="role_admin">
                                <div class="role-opt-icon" style="background:#fef3c7;color:#b45309;"><i class="mdi mdi-shield-account"></i></div>
                                <div>
                                    <div class="role-opt-name">Admin</div>
                                    <div class="role-opt-sub">Full dashboard access</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('role') <div class="form-err">{{ $message }}</div> @enderror
                </div>

                {{-- Change Password (optional) --}}
                <div class="form-grp">
                    <label class="pw-section-toggle">
                        <input type="checkbox" id="changePwCheck" onchange="togglePwSection()">
                        <span>Change Password</span>
                    </label>
                    <div class="pw-fields {{ $errors->hasAny(['password']) ? 'open' : '' }}" id="pwFields">
                        <div class="form-grp" style="margin-top:4px;">
                            <label class="form-lbl"><i class="mdi mdi-lock me-1" style="color:#6366f1;"></i>New Password</label>
                            <div class="pw-wrap">
                                <input type="password" name="password" id="pw"
                                       class="form-inp {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                       placeholder="Minimum 8 characters">
                                <button type="button" class="pw-toggle" onclick="togglePw('pw','eyePw')">
                                    <i class="mdi mdi-eye" id="eyePw"></i>
                                </button>
                            </div>
                            @error('password') <div class="form-err">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-grp">
                            <label class="form-lbl"><i class="mdi mdi-lock-check me-1" style="color:#6366f1;"></i>Confirm New Password</label>
                            <div class="pw-wrap">
                                <input type="password" name="password_confirmation" id="pw2"
                                       class="form-inp" placeholder="Repeat new password">
                                <button type="button" class="pw-toggle" onclick="togglePw('pw2','eyePw2')">
                                    <i class="mdi mdi-eye" id="eyePw2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-hint">Leave unchecked to keep the current password.</div>
                </div>

            </div>

            <div class="uf-actions">
                <button type="submit" class="btn-save"><i class="mdi mdi-content-save me-1"></i>Save Changes</button>
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">Cancel</a>

                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="margin-left:auto;"
                      onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-del"><i class="mdi mdi-delete me-1"></i>Delete</button>
                </form>
                @endif
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function togglePw(id, eyeId) {
    const inp = document.getElementById(id);
    const eye = document.getElementById(eyeId);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'mdi mdi-eye' : 'mdi mdi-eye-off';
}

function togglePwSection() {
    const checked = document.getElementById('changePwCheck').checked;
    const fields  = document.getElementById('pwFields');
    fields.classList.toggle('open', checked);
    if (!checked) {
        document.getElementById('pw').value  = '';
        document.getElementById('pw2').value = '';
    }
}

// Auto-open if there were validation errors on password
@if($errors->hasAny(['password']))
document.getElementById('changePwCheck').checked = true;
@endif
</script>
@endpush
