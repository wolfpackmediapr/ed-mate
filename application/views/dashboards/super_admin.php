<div class="dashboard-body">

    <div class="row gy-4">
        <div class="col-lg-9">
            <!-- Widgets Start -->
            <div class="row gy-4">
                <div class="col-xxl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2"><?= number_format($total_courses) ?></h4>
                            <span class="text-gray-600">Total Courses</span>
                            <div class="flex-between gap-8 mt-16">
                                <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-main-600 text-white text-2xl"><i class="ph-fill ph-book-open"></i></span>
                                <div id="total-courses" class="remove-tooltip-title rounded-tooltip-value"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2"><?= number_format($total_students) ?></h4>
                            <span class="text-gray-600">Total Students</span>
                            <div class="flex-between gap-8 mt-16">
                                <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-main-two-600 text-white text-2xl"><i class="ph-fill ph-users"></i></span>
                                <div id="total-students" class="remove-tooltip-title rounded-tooltip-value"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2"><?= number_format($total_teachers) ?></h4>
                            <span class="text-gray-600">Total Teachers</span>
                            <div class="flex-between gap-8 mt-16">
                                <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-purple-600 text-white text-2xl"><i class="ph-fill ph-graduation-cap"></i></span>
                                <div id="total-teachers" class="remove-tooltip-title rounded-tooltip-value"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2">$<?= number_format($total_revenue, 2) ?></h4>
                            <span class="text-gray-600">Total Revenue</span>
                            <div class="flex-between gap-8 mt-16">
                                <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-warning-600 text-white text-2xl"><i class="ph-fill ph-currency-dollar"></i></span>
                                <div id="total-revenue" class="remove-tooltip-title rounded-tooltip-value"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Widgets End -->

            <!-- Recent Courses Start -->
            <div class="card mt-24">
                <div class="card-header border-bottom border-gray-100">
                    <h5 class="mb-0">Recent Courses</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Category</th>
                                    <th>Creator</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_courses as $course): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($course->thumbnail_image)): ?>
                                            <img src="<?= $course->thumbnail_image ?>" class="rounded me-3" width="40" height="40" alt="Course Thumbnail">
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0"><?= $course->course_title ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= $course->category_name ?></td>
                                    <td><?= $course->creator_name ?></td>
                                    <td>
                                        <span class="badge bg-<?= $course->is_published ? 'success' : 'warning' ?>">
                                            <?= $course->is_published ? 'Published' : 'Draft' ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($course->created_at)) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Recent Courses End -->

            <!-- Recent Enrollments Start -->
            <div class="card mt-24">
                <div class="card-header border-bottom border-gray-100">
                    <h5 class="mb-0">Recent Enrollments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_enrollments as $enrollment): ?>
                                <tr>
                                    <td><?= $enrollment->username ?></td>
                                    <td><?= $enrollment->course_title ?></td>
                                    <td>$<?= number_format($enrollment->price, 2) ?></td>
                                    <td><?= date('M d, Y', strtotime($enrollment->created_at)) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Recent Enrollments End -->
        </div>

        <div class="col-lg-3">
            <!-- Quick Actions Start -->
            <div class="card">
                <div class="card-header border-bottom border-gray-100">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('create-course') ?>" class="btn btn-main">
                            <i class="ph-fill ph-plus me-2"></i>Create Course
                        </a>
                        <a href="<?= base_url('manage-users') ?>" class="btn btn-outline-main">
                            <i class="ph-fill ph-users me-2"></i>Manage Users
                        </a>
                        <a href="<?= base_url('manage-categories') ?>" class="btn btn-outline-main">
                            <i class="ph-fill ph-folders me-2"></i>Manage Categories
                        </a>
                        <a href="<?= base_url('reports') ?>" class="btn btn-outline-main">
                            <i class="ph-fill ph-chart-line me-2"></i>View Reports
                        </a>
                    </div>
                </div>
            </div>
            <!-- Quick Actions End -->
        </div>

    </div>
</div>

<script>
// Initialize charts for statistics
document.addEventListener('DOMContentLoaded', function() {
    // Chart options
    const chartOptions = {
        chart: {
            type: 'area',
            height: 100,
            sparkline: {
                enabled: true
            },
            toolbar: {
                show: false
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            marker: {
                show: false
            }
        }
    };

    // Generate random data for demo
    function generateData() {
        return Array.from({length: 12}, () => Math.floor(Math.random() * 100));
    }

    // Initialize charts
    ['total-courses', 'total-students', 'total-teachers', 'total-revenue'].forEach((id, index) => {
        const colors = ['#2FB2AB', '#27CFA7', '#6142FF', '#FFB800'];
        const chart = new ApexCharts(document.querySelector(`#${id}`), {
            ...chartOptions,
            series: [{
                name: 'Value',
                data: generateData()
            }],
            colors: [colors[index]]
        });
        chart.render();
    });
});
</script>