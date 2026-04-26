@extends('admin.layouts.app')

@push('styles')
<style>
.uf-wrap { padding: 28px; background: #f1f5f9; min-height: 100vh; }
.uf-back { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #64748b; text-decoration: none; margin-bottom: 20px; }
.uf-back:hover { color: #6366f1; }
.uf-title { font-size: 20px; font-weight: 900; color: #1e293b; margin: 0 0 22px; }

.uf-card { background: #fff; border-radius: 18px; border: 1px solid #e8eaf0; overflow: hidden; max-width: 640px; }
.uf-card-head { padding: 20px 28px 16px; border-bottom: 1px solid #f1f5f9; }
.uf-card-title { font-size: 15px; font-weight: 800; color: #1e293b; }
.uf-card-body { padding: 28px; display: flex; flex-direction: column; gap: 20px; }

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

.uf-actions { display: flex; align-items: center; gap: 12px; padding: 18px 28px; border-top: 1px solid #f1f5f9; }
.btn-save { background: #6366f1; color: #fff; border: none; border-radius: 10px; padding: 10px 24px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background .2s; }
.btn-save:hover { background: #4f46e5; }
.btn-cancel { color: #64748b; font-size: 13px; font-weight: 600; text-decoration: none; }
</style>
@endpush

@section('content')
<div class="uf-wrap">

    <a href="{{ route('admin.users.index') }}" class="uf-back">
        <i class="mdi mdi-arrow-left"></i> Back to Users
    </a>

    <h1 class="uf-title">Add New User</h1>

    <div class="uf-card">
        <div class="uf-card-head">
            <div class="uf-card-title"><i class="mdi mdi-account-plus me-2" style="color:#6366f1;"></i>User Details</div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="uf-card-body">

                {{-- Name --}}
                <div class="form-grp">
                    <label class="form-lbl"><i class="mdi mdi-account me-1" style="color:#6366f1;"></i>Full Name <span>*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-inp {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           placeholder="e.g. John Doe">
                    @error('name') <div class="form-err">{{ $message }}</div> @enderror
                </div>

                {{-- Email --}}
                <div class="form-grp">
                    <label class="form-lbl"><i class="mdi mdi-email me-1" style="color:#6366f1;"></i>Email Address <span>*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-inp {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           placeholder="e.g. john@example.com">
                    @error('email') <div class="form-err">{{ $message }}</div> @enderror
                </div>

                {{-- Role --}}
                <div class="form-grp">
                    <label class="form-lbl"><i class="mdi mdi-shield-account me-1" style="color:#6366f1;"></i>Role <span>*</span></label>
                    <div class="role-grid">
                        <div class="role-opt">
                            <input type="radio" name="role" id="role_customer" value="user" {{ old('role','user') === 'user' ? 'checked' : '' }}>
                            <label for="role_customer">
                                <div class="role-opt-icon" style="background:#eef2ff;color:#6366f1;"><i class="mdi mdi-account"></i></div>
                                <div>
                                    <div class="role-opt-name">Customer</div>
                                    <div class="role-opt-sub">Regular store user</div>
                                </div>
                            </label>
                        </div>
                        <div class="role-opt">
                            <input type="radio" name="role" id="role_admin" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}>
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

                {{-- Password --}}
                <div class="form-grp">
                    <label class="form-lbl"><i class="mdi mdi-lock me-1" style="color:#6366f1;"></i>Password <span>*</span></label>
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

                {{-- Confirm Password --}}
                <div class="form-grp">
                    <label class="form-lbl"><i class="mdi mdi-lock-check me-1" style="color:#6366f1;"></i>Confirm Password <span>*</span></label>
                    <div class="pw-wrap">
                        <input type="password" name="password_confirmation" id="pw2"
                               class="form-inp"
                               placeholder="Repeat password">
                        <button type="button" class="pw-toggle" onclick="togglePw('pw2','eyePw2')">
                            <i class="mdi mdi-eye" id="eyePw2"></i>
                        </button>
                    </div>
                </div>

            </div>

            <div class="uf-actions">
                <button type="submit" class="btn-save"><i class="mdi mdi-check me-1"></i>Create User</button>
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">Cancel</a>
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
    if (inp.type === 'password') { inp.type = 'text'; eye.className = 'mdi mdi-eye-off'; }
    else { inp.type = 'password'; eye.className = 'mdi mdi-eye'; }
}
</script>
@endpush
