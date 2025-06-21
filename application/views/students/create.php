<div class="dashboard-body">
    <div class="breadcrumb mb-24">
        <ul class="flex-align gap-4">
            <li><a href="<?= base_url('dashboard') ?>" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
            <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
            <li><a href="<?= base_url('students') ?>" class="text-main-600 fw-normal text-15">Students</a></li>
            <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
            <li><span class="text-main-600 fw-normal text-15">Add Student</span></li>
        </ul>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="mb-16">Add Student</h4>
            <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <form method="post" action="<?= base_url('students/create') ?>" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" required value="<?= set_value('username') ?>">
                        <div class="invalid-feedback">Please enter a username.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" required value="<?= set_value('email') ?>">
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required value="<?= set_value('first_name') ?>">
                        <div class="invalid-feedback">Please enter first name.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required value="<?= set_value('last_name') ?>">
                        <div class="invalid-feedback">Please enter last name.</div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="ph ph-eye"></i>
                        </button>
                        <div class="invalid-feedback">Please enter a password.</div>
                    </div>
                    <small class="text-muted">Password must be at least 6 characters long.</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Add Student</button>
                    <a href="<?= base_url('students') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('ph-eye');
        this.querySelector('i').classList.toggle('ph-eye-slash');
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script> 