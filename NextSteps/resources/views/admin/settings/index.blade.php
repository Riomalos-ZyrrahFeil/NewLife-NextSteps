@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="page-header-top mb-4">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #111827;">
            System Settings
        </h3>
        <span class="text-secondary small">Configuration & Maintenance</span>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4">
        <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- TABS NAVIGATION --}}
<div class="user-card p-0 overflow-hidden">
    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
        <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">

            {{-- Tab 1: General Configuration (NOW ACTIVE) --}}
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                    <i class="fas fa-sliders-h mr-2"></i> General Configuration
                </a>
            </li>

            {{-- Tab 2: Backup & Restore --}}
            <li class="nav-item">
                <a class="nav-link" id="backup-tab" data-toggle="tab" href="#backup" role="tab" aria-controls="backup" aria-selected="false">
                    <i class="fas fa-database mr-2"></i> Backup & Restore
                </a>
            </li>

        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="settingsTabsContent">
            
            {{-- TAB CONTENT 1: GENERAL CONFIGURATION (NOW ACTIVE) --}}
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                
                {{-- Profile Section --}}
                <div class="mb-5">
                    <h5 class="font-bold mb-3 border-bottom pb-2">Profile Information</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('admin.settings.updateProfile') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="small font-weight-bold">First Name</label>
                                        <input type="text" name="first_name" class="form-control" value="{{ auth()->user()->first_name }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="small font-weight-bold">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" value="{{ auth()->user()->last_name }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="small font-weight-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save mr-1"></i> Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Account Actions Section --}}
                <h5 class="font-bold mb-3 border-bottom pb-2">Account Security</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between align-items-center p-3 border rounded bg-light">
                            <div>
                                <h6 class="m-0 font-weight-bold">Change Password</h6>
                                <small class="text-muted">Update your login password regularly.</small>
                            </div>
                            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#changePasswordModal">
                                Change
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between align-items-center p-3 border rounded bg-light" style="border-color: #fecaca !important; background-color: #fef2f2 !important;">
                            <div>
                                <h6 class="m-0 font-weight-bold text-danger">Delete Account</h6>
                                <small class="text-danger">Permanently remove your account.</small>
                            </div>
                            <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteAccountModal">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            {{-- TAB CONTENT 2: BACKUP & RESTORE --}}
            <div class="tab-pane fade" id="backup" role="tabpanel" aria-labelledby="backup-tab">
                <div class="row">
                    {{-- Backup Card --}}
                    <div class="col-md-6 mb-4">
                        <div style="background: #f9fafb; border-radius: 12px; padding: 25px; height: 100%; border: 1px solid #e5e7eb;">
                            <div class="d-flex align-items-center mb-3">
                                <div style="background: #e0f2fe; padding: 12px; border-radius: 10px; margin-right: 15px;">
                                    <i class="fas fa-download fa-lg text-primary"></i>
                                </div>
                                <h5 class="font-bold m-0">Backup Database</h5>
                            </div>
                            <p class="text-secondary mb-4 small">
                                Generate a full SQL dump of your system including all users, guests, and tracking logs.
                            </p>
                            <a href="{{ route('admin.settings.backup') }}" class="btn-add btn-block text-center">
                                <i class="fas fa-cloud-download-alt mr-2"></i> Download Backup
                            </a>
                        </div>
                    </div>

                    {{-- Restore Card --}}
                    <div class="col-md-6 mb-4">
                        <div style="background: #fef2f2; border-radius: 12px; padding: 25px; height: 100%; border: 1px solid #fee2e2;">
                            <div class="d-flex align-items-center mb-3">
                                <div style="background: #fee2e2; padding: 12px; border-radius: 10px; margin-right: 15px;">
                                    <i class="fas fa-upload fa-lg text-danger"></i>
                                </div>
                                <h5 class="font-bold m-0 text-danger">Restore Database</h5>
                            </div>
                            <p class="text-secondary mb-3 small">
                                <strong>Warning:</strong> Restoring will <u>overwrite</u> all current data.
                            </p>
                            <form action="{{ route('admin.settings.restore') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-2">
                                    <input type="file" name="backup_file" class="form-control form-control-sm" required>
                                </div>
                                <button type="submit" class="btn btn-danger btn-block btn-sm" onclick="return confirm('CRITICAL WARNING: This will REPLACE all data. Continue?')">
                                    <i class="fas fa-history mr-2"></i> Restore Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL: CHANGE PASSWORD --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.settings.changePassword') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: DELETE ACCOUNT --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold">Delete Account</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.settings.deleteAccount') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="text-danger font-weight-bold">Warning: This action cannot be undone.</p>
                    <p>Please enter your password to confirm you want to permanently delete your account.</p>
                    <div class="form-group">
                        <label>Password Confirmation</label>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Enter your password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Deletion</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection