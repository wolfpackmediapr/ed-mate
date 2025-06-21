<div class="dashboard-body">
    <div class="breadcrumb mb-24">
        <ul class="flex-align gap-4">
            <li><a href="<?= base_url('dashboard') ?>" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
            <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
            <li><span class="text-main-600 fw-normal text-15">Students</span></li>
        </ul>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="mb-16">Students List</h4>
            <?php if (customMiddleware() == 'Super Admin') { ?>
                <a href="<?= base_url('students/create') ?>" class="btn btn-main mb-3">Add Student</a>
            <?php } ?>
            <div id="loading-spinner" class="text-center py-4 d-none">
                <div class="spinner-border text-main-600" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div id="error-message" class="alert alert-danger d-none" role="alert">
                Failed to load students. Please try again.
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody">
                        <!-- Students will be loaded here by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Student Details -->
<div class="modal fade" id="studentDetailsModal" tabindex="-1" aria-labelledby="studentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentDetailsModalLabel">Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="student-details-content">
                <!-- Student details will be loaded here by JS -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentsTbody = document.getElementById('students-tbody');
    const loadingSpinner = document.getElementById('loading-spinner');
    const errorMessage = document.getElementById('error-message');
    const studentDetailsModal = new bootstrap.Modal(document.getElementById('studentDetailsModal'));
    const studentDetailsContent = document.getElementById('student-details-content');

    // Function to load students list
    function loadStudents() {
        loadingSpinner.classList.remove('d-none');
        errorMessage.classList.add('d-none');
        studentsTbody.innerHTML = '';

        fetch('<?= base_url('api/students') ?>')
            .then(response => response.json())
            .then(data => {
                loadingSpinner.classList.add('d-none');
                if (data.error) {
                    errorMessage.textContent = data.error;
                    errorMessage.classList.remove('d-none');
                } else {
                    data.forEach(student => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${student.id}</td>
                            <td>${student.name}</td>
                            <td>${student.email}</td>
                            <td>
                                <button class="btn btn-sm btn-primary view-details" data-id="${student.id}">View Details</button>
                            </td>
                        `;
                        studentsTbody.appendChild(row);
                    });
                }
            })
            .catch(error => {
                loadingSpinner.classList.add('d-none');
                errorMessage.textContent = 'Failed to load students. Please try again.';
                errorMessage.classList.remove('d-none');
            });
    }

    // Function to load student details
    function loadStudentDetails(id) {
        studentDetailsContent.innerHTML = 'Loading...';
        studentDetailsModal.show();

        fetch(`<?= base_url('api/students/') ?>${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    studentDetailsContent.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                } else {
                    studentDetailsContent.innerHTML = `
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Name:</strong> ${data.name}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <!-- Add more details as needed -->
                    `;
                }
            })
            .catch(error => {
                studentDetailsContent.innerHTML = '<div class="alert alert-danger">Failed to load student details.</div>';
            });
    }

    // Event delegation for 'View Details' buttons
    studentsTbody.addEventListener('click', function(e) {
        if (e.target.classList.contains('view-details')) {
            const studentId = e.target.getAttribute('data-id');
            loadStudentDetails(studentId);
        }
    });

    // Load students on page load
    loadStudents();
});
</script> 