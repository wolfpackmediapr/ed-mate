<div class="container-fluid mt-4">
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalRevenue">$0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Users (30 days)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeUsers">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Course Completion Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="completionRate">0%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Enrollments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalEnrollments">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Growth Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Growth</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Performance Row -->
    <div class="row">
        <!-- Course Completion Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Course Completion Rates</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="completionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Distribution Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Course Enrollment Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="enrollmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Process data
    const revenueData = <?= json_encode($revenue) ?>;
    const userGrowthData = <?= json_encode($user_growth) ?>;
    const completionData = <?= json_encode($completion_rates) ?>;
    const enrollmentData = <?= json_encode($enrollments) ?>;
    const activeUsersData = <?= json_encode($active_users) ?>;

    // Calculate summary statistics
    const totalRevenue = revenueData.reduce((sum, payment) => sum + parseFloat(payment.amount), 0);
    const activeUsers = activeUsersData.length;
    const completionRate = completionData.length > 0 
        ? (completionData.reduce((sum, course) => sum + (course.completed_lessons / course.total_lessons), 0) / completionData.length * 100).toFixed(1)
        : 0;
    const totalEnrollments = enrollmentData.reduce((sum, course) => sum + course.count, 0);

    // Update summary cards
    document.getElementById('totalRevenue').textContent = `$${totalRevenue.toFixed(2)}`;
    document.getElementById('activeUsers').textContent = activeUsers;
    document.getElementById('completionRate').textContent = `${completionRate}%`;
    document.getElementById('totalEnrollments').textContent = totalEnrollments;

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(payment => new Date(payment.created_at).toLocaleDateString()),
            datasets: [{
                label: 'Revenue',
                data: revenueData.map(payment => payment.amount),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: userGrowthData.map(user => new Date(user.created_at).toLocaleDateString()),
            datasets: [{
                label: 'New Users',
                data: Array.from({length: userGrowthData.length}, (_, i) => i + 1),
                borderColor: 'rgb(153, 102, 255)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Course Completion Chart
    const completionCtx = document.getElementById('completionChart').getContext('2d');
    new Chart(completionCtx, {
        type: 'bar',
        data: {
            labels: completionData.map(course => `Course ${course.course_id}`),
            datasets: [{
                label: 'Completion Rate',
                data: completionData.map(course => (course.completed_lessons / course.total_lessons * 100).toFixed(1)),
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Completion Rate (%)'
                    }
                }
            }
        }
    });

    // Enrollment Distribution Chart
    const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
    new Chart(enrollmentCtx, {
        type: 'doughnut',
        data: {
            labels: enrollmentData.map(course => `Course ${course.course_id}`),
            datasets: [{
                data: enrollmentData.map(course => course.count),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>

<style>
.chart-area {
    position: relative;
    height: 300px;
    width: 100%;
}

.chart-pie {
    position: relative;
    height: 300px;
    width: 100%;
}

.chart-bar {
    position: relative;
    height: 300px;
    width: 100%;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style> 