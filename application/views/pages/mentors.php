<!-- Mentors Tab for Super Admin -->
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end align-items-start mb-4 gap-2">
        <div>
            <h2 class="mb-1 fw-bold">Mentors</h2>
            <div class="text-gray-500">All mentors and admins for teachers in the system</div>
        </div>
        <div class="position-relative" style="max-width:320px;width:100%;">
            <input type="text" class="form-control ps-5 rounded-pill shadow-sm" id="mentorSearch" placeholder="Search mentors by name, username, or email...">
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-gray-400"><i class="ph ph-magnifying-glass"></i></span>
        </div>
    </div>
    <div class="row g-4" id="mentorsGrid">
        <?php if (!empty($mentors)) : ?>
            <?php foreach ($mentors as $mentor) : ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mentor-card-item">
                    <div class="card h-100 border-0 shadow-sm mentor-card-custom">
                        <div class="card-body d-flex flex-column align-items-center text-center p-4">
                            <div class="position-relative mb-3">
                                <img src="<?= !empty($mentor->profile_image) ? (strpos($mentor->profile_image, 'http') === 0 ? $mentor->profile_image : base_url('uploads/profiles/' . $mentor->profile_image)) : base_url('assets/images/thumbs/user-img.png') ?>" class="rounded-circle border border-3 border-main-100 shadow" width="96" height="96" alt="Mentor Avatar" style="object-fit:cover;">
                            </div>
                            <h5 class="card-title mb-1 fw-semibold text-main-700"><?= htmlspecialchars(trim($mentor->first_name . ' ' . $mentor->last_name)) ?: 'No Name' ?></h5>
                            <p class="text-gray-500 mb-1 small">@<?= htmlspecialchars($mentor->username ?: 'unknown') ?></p>
                            <p class="text-gray-600 mb-2 small"><i class="ph ph-envelope me-1"></i> <?= htmlspecialchars($mentor->email) ?></p>
                            <span class="badge d-inline-flex align-items-center gap-1 px-3 py-2 rounded-pill bg-<?= $mentor->role_id == 2 ? 'info' : 'success' ?>-subtle text-<?= $mentor->role_id == 2 ? 'info' : 'success' ?>-700 fs-6 mt-1">
                                <i class="ph ph-user-gear"></i>
                                <?= $mentor->role_id == 2 ? 'Admin for Teachers' : ($mentor->role_id == 3 ? 'Mentor' : 'Other') ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12 text-center text-muted">No mentors found.</div>
        <?php endif; ?>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('mentorSearch');
    const mentorsGrid = document.getElementById('mentorsGrid');
    const allCards = Array.from(mentorsGrid.getElementsByClassName('mentor-card-item'));
    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        allCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(term) ? '' : 'none';
        });
    });
});
</script>
<style>
.mentor-card-custom {
    border-radius: 18px;
    transition: box-shadow 0.2s, transform 0.2s;
    background: #fff;
}
.mentor-card-custom:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    transform: translateY(-4px) scale(1.02);
}
.mentor-card-custom .card-body {
    padding-top: 2.5rem;
    padding-bottom: 2.5rem;
}
#mentorSearch {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    font-size: 1rem;
}
@media (max-width: 575.98px) {
    .mentor-card-custom .card-body { padding: 1.5rem 0.5rem; }
    .mentor-card-item { margin-bottom: 1rem; }
}
</style> 