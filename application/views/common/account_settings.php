<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="dashboard-body">
    <div class="breadcrumb-with-buttons mb-24 flex-between flex-wrap gap-8">
        <!-- Breadcrumb Start -->
        <div class="breadcrumb mb-24">
            <ul class="flex-align gap-4">
                <li><a href="<?= base_url() ?>" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
                <li><span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span></li>
                <li><span class="text-main-600 fw-normal text-15">Account Settings</span></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <div class="card mb-24">
                <div class="card-header border-bottom border-gray-100">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form id="profileForm" action="<?= base_url('account/update-profile') ?>" method="post">
                        <div class="row g-20">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="<?= $user->first_name ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="<?= $user->last_name ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= $user->email ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone" value="<?= $user->phone ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Bio</label>
                                    <textarea class="form-control" name="bio" rows="4"><?= $user->bio ?? '' ?></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-main rounded-pill py-9">Update Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card mb-24">
                <div class="card-header border-bottom border-gray-100">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form id="passwordForm" action="<?= base_url('account/change-password') ?>" method="post">
                        <div class="row g-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="new_password" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-main rounded-pill py-9">Change Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Profile Picture -->
            <div class="card mb-24">
                <div class="card-header border-bottom border-gray-100">
                    <h5 class="mb-0">Profile Picture</h5>
                </div>
                <div class="card-body">
                    <form id="avatarForm" action="<?= base_url('account/update-avatar') ?>" method="post" enctype="multipart/form-data">
                        <div class="text-center mb-20">
                            <div class="avatar-upload">
                                <img src="<?= $user->avatar_url ?? base_url('assets/images/default-avatar.png') ?>" 
                                     alt="Profile Picture" 
                                     class="rounded-circle mb-16"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <div class="upload-btn-wrapper">
                                    <button class="btn btn-outline-main rounded-pill py-9">Change Picture</button>
                                    <input type="file" name="avatar" accept="image/*" onchange="previewImage(this)">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-main rounded-pill py-9 w-100">Update Picture</button>
                    </form>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="card">
                <div class="card-header border-bottom border-gray-100">
                    <h5 class="mb-0">Notification Preferences</h5>
                </div>
                <div class="card-body">
                    <form id="notificationForm" action="<?= base_url('account/update-notifications') ?>" method="post">
                        <div class="form-check form-switch mb-16">
                            <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotifications" 
                                   <?= ($user->email_notifications ?? true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                        </div>
                        <div class="form-check form-switch mb-16">
                            <input class="form-check-input" type="checkbox" name="course_updates" id="courseUpdates"
                                   <?= ($user->course_updates ?? true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="courseUpdates">Course Updates</label>
                        </div>
                        <div class="form-check form-switch mb-16">
                            <input class="form-check-input" type="checkbox" name="announcements" id="announcements"
                                   <?= ($user->announcements ?? true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="announcements">Announcements</label>
                        </div>
                        <button type="submit" class="btn btn-main rounded-pill py-9 w-100">Save Preferences</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            input.parentElement.previousElementSibling.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Form submissions with AJAX
$(document).ready(function() {
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    toastr.success('Profile updated successfully');
                } else {
                    toastr.error(response.message || 'Error updating profile');
                }
            }
        });
    });

    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();
        if($('input[name="new_password"]').val() !== $('input[name="confirm_password"]').val()) {
            toastr.error('Passwords do not match');
            return;
        }
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    toastr.success('Password changed successfully');
                    $('#passwordForm')[0].reset();
                } else {
                    toastr.error(response.message || 'Error changing password');
                }
            }
        });
    });

    $('#avatarForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    toastr.success('Profile picture updated successfully');
                } else {
                    toastr.error(response.message || 'Error updating profile picture');
                }
            }
        });
    });

    $('#notificationForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    toastr.success('Notification preferences updated successfully');
                } else {
                    toastr.error(response.message || 'Error updating notification preferences');
                }
            }
        });
    });
});
</script>

<style>
.avatar-upload {
    position: relative;
    display: inline-block;
}

.upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
}

.upload-btn-wrapper input[type=file] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: var(--main-color);
    border-color: var(--main-color);
}
</style> 