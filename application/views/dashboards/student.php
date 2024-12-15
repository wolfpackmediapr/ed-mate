<div class="dashboard-body">

    <div class="row gy-4">
        <div class="col-lg-9">
            <!-- Widgets Start -->
            <div class="row gy-4">
                <div class="col-xxl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2">155+</h4>
                            <span class="text-gray-600">Completed Courses (Student Dashboard)</span>
                            <div class="flex-between gap-8 mt-16">
                                <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-main-600 text-white text-2xl"><i class="ph-fill ph-book-open"></i></span>
                                <div id="complete-course" class="remove-tooltip-title rounded-tooltip-value"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2">39+</h4>
                            <span class="text-gray-600">Earned Certificate</span>
                            <div class="flex-between gap-8 mt-16">
                                <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-main-two-600 text-white text-2xl"><i class="ph-fill ph-certificate"></i></span>
                                <div id="earned-certificate" class="remove-tooltip-title rounded-tooltip-value"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2">25+</h4>
                            <span class="text-gray-600">Course in Progress</span>
                            <div class="flex-between gap-8 mt-16">
                                <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-purple-600 text-white text-2xl"> <i class="ph-fill ph-graduation-cap"></i></span>
                                <div id="course-progress" class="remove-tooltip-title rounded-tooltip-value"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2">18k+</h4>
                            <span class="text-gray-600">Community Support</span>
                            <div class="flex-between gap-8 mt-16">
                                <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-warning-600 text-white text-2xl"><i class="ph-fill ph-users-three"></i></span>
                                <div id="community-support" class="remove-tooltip-title rounded-tooltip-value"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Widgets End -->

            <!-- Top Course Start -->
            <div class="card mt-24">
                <div class="card-body">
                    <div class="mb-20 flex-between flex-wrap gap-8">
                        <h4 class="mb-0">Study Statistics</h4>
                        <div class="flex-align gap-16 flex-wrap">
                            <div class="flex-align flex-wrap gap-16">
                                <div class="flex-align flex-wrap gap-8">
                                    <span class="w-8 h-8 rounded-circle bg-main-600"></span>
                                    <span class="text-13 text-gray-600">Study</span>
                                </div>
                                <div class="flex-align flex-wrap gap-8">
                                    <span class="w-8 h-8 rounded-circle bg-main-two-600"></span>
                                    <span class="text-13 text-gray-600">Test</span>
                                </div>
                            </div>
                            <select class="form-select form-control text-13 px-8 pe-24 py-8 rounded-8 w-auto">
                                <option value="1">Yearly</option>
                                <option value="1">Monthly</option>
                                <option value="1">Weekly</option>
                                <option value="1">Today</option>
                            </select>
                        </div>
                    </div>

                    <div id="doubleLineChart" class="tooltip-style y-value-left"></div>

                </div>
            </div>
            <!-- Top Course End -->

            <!-- Top Course Start -->
            <div class="card mt-24">
                <div class="card-body">
                    <div class="mb-20 flex-between flex-wrap gap-8">
                        <h4 class="mb-0">Top Courses Pick for You</h4>
                        <a href="student-courses.html" class="text-13 fw-medium text-main-600 hover-text-decoration-underline">See All</a>
                    </div>

                    <div class="row g-20">
                        <div class="col-lg-4 col-sm-6">
                            <div class="card border border-gray-100">
                                <div class="card-body p-8">
                                    <a href="course-details.html" class="bg-main-100 rounded-8 overflow-hidden text-center mb-8 h-164 flex-center p-8">
                                        <img src="<?= asset_url() ?>images/thumbs/course-img1.png" alt="Course Image">
                                    </a>
                                    <div class="p-8">
                                        <span class="text-13 py-2 px-10 rounded-pill bg-success-50 text-success-600 mb-16">Development</span>
                                        <h5 class="mb-0"><a href="course-details.html" class="hover-text-main-600">Full Stack Web Development</a></h5>

                                        <div class="flex-align gap-8 flex-wrap mt-16">
                                            <img src="<?= asset_url() ?>images/thumbs/user-img1.png" class="w-28 h-28 rounded-circle object-fit-cover" alt="User Image">
                                            <div>
                                                <span class="text-gray-600 text-13">Created by <a href="profile.html" class="fw-semibold text-gray-700 hover-text-main-600 hover-text-decoration-underline">Albert James</a> </span>
                                            </div>
                                        </div>

                                        <div class="flex-align gap-8 mt-12 pt-12 border-top border-gray-100">
                                            <div class="flex-align gap-4">
                                                <span class="text-sm text-main-600 d-flex"><i class="ph ph-video-camera"></i></span>
                                                <span class="text-13 text-gray-600">24 Lesson</span>
                                            </div>
                                            <div class="flex-align gap-4">
                                                <span class="text-sm text-main-600 d-flex"><i class="ph ph-clock"></i></span>
                                                <span class="text-13 text-gray-600">40 Hours</span>
                                            </div>
                                        </div>

                                        <div class="flex-between gap-4 flex-wrap mt-24">
                                            <div class="flex-align gap-4">
                                                <span class="text-15 fw-bold text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                                <span class="text-13 fw-bold text-gray-600">4.9</span>
                                                <span class="text-13 fw-bold text-gray-600">(12k)</span>
                                            </div>
                                            <a href="course-details.html" class="btn btn-outline-main rounded-pill py-9">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="card border border-gray-100">
                                <div class="card-body p-8">
                                    <a href="course-details.html" class="bg-main-100 rounded-8 overflow-hidden text-center mb-8 h-164 flex-center p-8">
                                        <img src="<?= asset_url() ?>images/thumbs/course-img5.png" alt="Course Image">
                                    </a>
                                    <div class="p-8">
                                        <span class="text-13 py-2 px-10 rounded-pill bg-warning-50 text-warning-600 mb-16">Design</span>
                                        <h5 class="mb-0"><a href="course-details.html" class="hover-text-main-600">Design System</a></h5>

                                        <div class="flex-align gap-8 flex-wrap mt-16">
                                            <img src="<?= asset_url() ?>images/thumbs/user-img5.png" class="w-28 h-28 rounded-circle object-fit-cover" alt="User Image">
                                            <div>
                                                <span class="text-gray-600 text-13">Created by <a href="profile.html" class="fw-semibold text-gray-700 hover-text-main-600 hover-text-decoration-underline">Albert James</a> </span>
                                            </div>
                                        </div>

                                        <div class="flex-align gap-8 mt-12 pt-12 border-top border-gray-100">
                                            <div class="flex-align gap-4">
                                                <span class="text-sm text-main-600 d-flex"><i class="ph ph-video-camera"></i></span>
                                                <span class="text-13 text-gray-600">24 Lesson</span>
                                            </div>
                                            <div class="flex-align gap-4">
                                                <span class="text-sm text-main-600 d-flex"><i class="ph ph-clock"></i></span>
                                                <span class="text-13 text-gray-600">40 Hours</span>
                                            </div>
                                        </div>

                                        <div class="flex-between gap-4 flex-wrap mt-24">
                                            <div class="flex-align gap-4">
                                                <span class="text-15 fw-bold text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                                <span class="text-13 fw-bold text-gray-600">4.9</span>
                                                <span class="text-13 fw-bold text-gray-600">(12k)</span>
                                            </div>
                                            <a href="course-details.html" class="btn btn-outline-main rounded-pill py-9">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="card border border-gray-100">
                                <div class="card-body p-8">
                                    <a href="course-details.html" class="bg-main-100 rounded-8 overflow-hidden text-center mb-8 h-164 flex-center p-8">
                                        <img src="<?= asset_url() ?>images/thumbs/course-img6.png" alt="Course Image">
                                    </a>
                                    <div class="p-8">
                                        <span class="text-13 py-2 px-10 rounded-pill bg-danger-50 text-danger-600 mb-16">Frontend</span>
                                        <h5 class="mb-0"><a href="course-details.html" class="hover-text-main-600">React Native Courese</a></h5>

                                        <div class="flex-align gap-8 flex-wrap mt-16">
                                            <img src="<?= asset_url() ?>images/thumbs/user-img6.png" class="w-28 h-28 rounded-circle object-fit-cover" alt="User Image">
                                            <div>
                                                <span class="text-gray-600 text-13">Created by <a href="profile.html" class="fw-semibold text-gray-700 hover-text-main-600 hover-text-decoration-underline">Albert James</a> </span>
                                            </div>
                                        </div>

                                        <div class="flex-align gap-8 mt-12 pt-12 border-top border-gray-100">
                                            <div class="flex-align gap-4">
                                                <span class="text-sm text-main-600 d-flex"><i class="ph ph-video-camera"></i></span>
                                                <span class="text-13 text-gray-600">24 Lesson</span>
                                            </div>
                                            <div class="flex-align gap-4">
                                                <span class="text-sm text-main-600 d-flex"><i class="ph ph-clock"></i></span>
                                                <span class="text-13 text-gray-600">40 Hours</span>
                                            </div>
                                        </div>

                                        <div class="flex-between gap-4 flex-wrap mt-24">
                                            <div class="flex-align gap-4">
                                                <span class="text-15 fw-bold text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                                <span class="text-13 fw-bold text-gray-600">4.9</span>
                                                <span class="text-13 fw-bold text-gray-600">(12k)</span>
                                            </div>
                                            <a href="course-details.html" class="btn btn-outline-main rounded-pill py-9">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Top Course End -->
        </div>

        <div class="col-lg-3">
            <!-- Calendar Start -->
            <div class="card">
                <div class="card-body">
                    <div class="calendar">
                        <div class="calendar__header">
                            <button type="button" class="calendar__arrow left"><i class="ph ph-caret-left"></i></button>
                            <p class="display h6 mb-0">""</p>
                            <button type="button" class="calendar__arrow right"><i class="ph ph-caret-right"></i></button>
                        </div>

                        <div class="calendar__week week">
                            <div class="calendar__week-text">Su</div>
                            <div class="calendar__week-text">Mo</div>
                            <div class="calendar__week-text">Tu</div>
                            <div class="calendar__week-text">We</div>
                            <div class="calendar__week-text">Th</div>
                            <div class="calendar__week-text">Fr</div>
                            <div class="calendar__week-text">Sa</div>
                        </div>
                        <div class="days"></div>
                    </div>
                </div>
            </div>
            <!-- Calendar End -->

            <!-- Assignment Start -->
            <div class="card mt-24">
                <div class="card-body">
                    <div class="mb-20 flex-between flex-wrap gap-8">
                        <h4 class="mb-0">Assignments</h4>
                        <a href="assignment.html" class="text-13 fw-medium text-main-600 hover-text-decoration-underline">See All</a>
                    </div>
                    <div class="p-xl-4 py-16 px-12 flex-between gap-8 rounded-8 border border-gray-100 hover-border-gray-200 transition-1 mb-16">
                        <div class="flex-align flex-wrap gap-8">
                            <span class="text-main-600 bg-main-50 w-44 h-44 rounded-circle flex-center text-2xl flex-shrink-0"><i class="ph-fill ph-graduation-cap"></i></span>
                            <div>
                                <h6 class="mb-0">Do The Research</h6>
                                <span class="text-13 text-gray-400">Due in 9 days</span>
                            </div>
                        </div>
                        <a href="assignment.html" class="text-gray-900 hover-text-main-600"><i class="ph ph-caret-right"></i></a>
                    </div>
                    <div class="p-xl-4 py-16 px-12 flex-between gap-8 rounded-8 border border-gray-100 hover-border-gray-200 transition-1 mb-16">
                        <div class="flex-align flex-wrap gap-8">
                            <span class="text-main-600 bg-main-50 w-44 h-44 rounded-circle flex-center text-2xl flex-shrink-0"><i class="ph ph-code"></i></span>
                            <div>
                                <h6 class="mb-0">PHP Dvelopment</h6>
                                <span class="text-13 text-gray-400">Due in 2 days</span>
                            </div>
                        </div>
                        <a href="assignment.html" class="text-gray-900 hover-text-main-600"><i class="ph ph-caret-right"></i></a>
                    </div>
                    <div class="p-xl-4 py-16 px-12 flex-between gap-8 rounded-8 border border-gray-100 hover-border-gray-200 transition-1">
                        <div class="flex-align flex-wrap gap-8">
                            <span class="text-main-600 bg-main-50 w-44 h-44 rounded-circle flex-center text-2xl flex-shrink-0"><i class="ph ph-bezier-curve"></i></span>
                            <div>
                                <h6 class="mb-0">Graphic Design</h6>
                                <span class="text-13 text-gray-400">Due in 5 days</span>
                            </div>
                        </div>
                        <a href="assignment.html" class="text-gray-900 hover-text-main-600"><i class="ph ph-caret-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- Assignment End -->

            <!-- Progress Bar Start -->
            <div class="card mt-24">
                <div class="card-header border-bottom border-gray-100">
                    <h5 class="mb-0">My Progress</h5>
                </div>
                <div class="card-body">
                    <div id="radialMultipleBar"></div>

                    <div class="">
                        <h6 class="text-lg mb-16 text-center"> <span class="text-gray-400">Total hour:</span> 6h 32 min</h6>
                        <div class="flex-between gap-8 flex-wrap">
                            <div class="flex-align flex-column">
                                <h6 class="mb-6">60/60</h6>
                                <span class="w-30 h-3 rounded-pill bg-main-600"></span>
                                <span class="text-13 mt-6 text-gray-600">Completed</span>
                            </div>
                            <div class="flex-align flex-column">
                                <h6 class="mb-6">60/60</h6>
                                <span class="w-30 h-3 rounded-pill bg-main-two-600"></span>
                                <span class="text-13 mt-6 text-gray-600">Completed</span>
                            </div>
                            <div class="flex-align flex-column">
                                <h6 class="mb-6">60/60</h6>
                                <span class="w-30 h-3 rounded-pill bg-gray-500"></span>
                                <span class="text-13 mt-6 text-gray-600">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Progress bar end -->
        </div>

    </div>
</div>