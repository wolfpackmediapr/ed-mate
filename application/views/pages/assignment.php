<!-- Assignments Tab -->
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end align-items-start mb-4 gap-2">
        <div>
            <h2 class="mb-1 fw-bold">Assignments</h2>
            <div class="text-gray-500">All assignments for your courses</div>
        </div>
        <div class="position-relative" style="max-width:320px;width:100%;">
            <input type="text" class="form-control ps-5 rounded-pill shadow-sm" id="assignmentSearch" placeholder="Search assignments...">
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-gray-400"><i class="ph ph-magnifying-glass"></i></span>
        </div>
    </div>
    <div class="row g-4" id="assignmentsGrid">
        <!-- Assignment cards will be rendered here by JS -->
    </div>
</div>
<script>
// Dummy assignments data
const assignments = [
    {
        id: 1,
        icon: 'ph-fill ph-graduation-cap',
        title: 'Do The Research',
        due: 'Due in 9 days',
        description: 'Write a 2-page research summary on blockchain technology.',
        status: 'Open',
        statusColor: 'success',
        action: 'View'
    },
    {
        id: 2,
        icon: 'ph-fill ph-code',
        title: 'PHP Development',
        due: 'Due in 2 days',
        description: 'Build a simple CRUD app using PHP and MySQL.',
        status: 'Submitted',
        statusColor: 'info',
        action: 'Edit Submission'
    },
    {
        id: 3,
        icon: 'ph-fill ph-bezier-curve',
        title: 'Graphic Design',
        due: 'Due in 5 days',
        description: 'Design a logo and brand guide for a startup.',
        status: 'Overdue',
        statusColor: 'danger',
        action: 'Submit Now'
    }
];

function renderAssignmentCard(a) {
    return `
        <div class="col-12 col-md-6 col-lg-4 assignment-card-item">
            <div class="card h-100 border-0 shadow-sm assignment-card-custom">
                <div class="card-body d-flex flex-column gap-2 p-4">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="flex-shrink-0 d-inline-flex align-items-center justify-content-center rounded-circle bg-main-50 text-main-600 fs-2" style="width:56px;height:56px;"><i class="${a.icon}"></i></span>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fw-semibold text-main-700">${a.title}</h5>
                            <span class="text-13 text-gray-400">${a.due}</span>
                        </div>
                    </div>
                    <div class="mb-2 text-gray-600 small">${a.description}</div>
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                        <span class="badge bg-${a.statusColor}-subtle text-${a.statusColor}-700 px-3 py-2 rounded-pill">${a.status}</span>
                        <button class="btn btn-outline-main btn-sm rounded-pill px-3">${a.action}</button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('assignmentsGrid');
    const searchInput = document.getElementById('assignmentSearch');
    function renderGrid(filter = '') {
        grid.innerHTML = assignments
            .filter(a => a.title.toLowerCase().includes(filter.toLowerCase()) || a.description.toLowerCase().includes(filter.toLowerCase()))
            .map(renderAssignmentCard).join('');
    }
    renderGrid();
    searchInput.addEventListener('input', function() {
        renderGrid(this.value);
    });
});
</script>
<style>
.assignment-card-custom {
    border-radius: 18px;
    transition: box-shadow 0.2s, transform 0.2s;
    background: #fff;
}
.assignment-card-custom:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    transform: translateY(-4px) scale(1.02);
}
.assignment-card-custom .card-body {
    padding-top: 2.5rem;
    padding-bottom: 2.5rem;
}
#assignmentSearch {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    font-size: 1rem;
}
@media (max-width: 575.98px) {
    .assignment-card-custom .card-body { padding: 1.5rem 0.5rem; }
    .assignment-card-item { margin-bottom: 1rem; }
}
</style> 