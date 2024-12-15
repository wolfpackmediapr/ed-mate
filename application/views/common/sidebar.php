<aside class="sidebar">
    <!-- sidebar close btn -->
    <button type="button" class="sidebar-close-btn text-gray-500 hover-text-white hover-bg-main-600 text-md w-24 h-24 border border-gray-100 hover-border-main-600 d-xl-none d-flex flex-center rounded-circle position-absolute"><i class="ph ph-x"></i></button>
    <!-- sidebar close btn -->

    <a href="index.html" class="sidebar__logo text-center p-20 position-sticky inset-block-start-0 bg-white w-100 z-1 pb-10">
        <img src="<?= asset_url() ?>images/logo/logo.png" alt="Logo">
    </a>

    <div class="sidebar-menu-wrapper overflow-y-auto scroll-sm">
        <div class="p-20 pt-10">
            <ul class="sidebar-menu">
                <li class="sidebar-menu__item has-dropdown">
                    <a href="<?= base_url('dashboard') ?>" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-squares-four"></i></span>
                        <span class="text">Dashboard</span>
                        <span class="link-badge">3</span>
                    </a>

                </li>
                <li class="sidebar-menu__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-graduation-cap"></i></span>
                        <span class="text">Courses</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu">
                        <?php if (customMiddleware() == 'Student') { ?>
                            <li class="sidebar-submenu__item">
                                <a href="<?= base_url('student-courses') ?>" class="sidebar-submenu__link"> Student Courses </a>
                            </li>
                        <?php } ?>

                        <?php if (customMiddleware() == 'Mentor' || customMiddleware() == 'Super Admin' || customMiddleware() == 'Admin for Teachers') { ?>
                            <li class="sidebar-submenu__item">
                                <a href="<?= base_url('mentor-courses') ?>" class="sidebar-submenu__link"> Mentor Courses </a>
                            </li>

                            <li class="sidebar-submenu__item">
                                <a href="<?= base_url('create-course') ?>" class="sidebar-submenu__link"> Create Course </a>
                            </li>

                            <li class="sidebar-submenu__item">
                                <a href="<?= base_url('categories') ?>" class="sidebar-submenu__link"> Categories </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <!-- Submenu End -->
                </li>

                <?php if (customMiddleware() == 'Mentor' || customMiddleware() == 'Super Admin' || customMiddleware() == 'Admin for Teachers') { ?>
                    <li class="sidebar-menu__item has-dropdown">
                        <a href="javascript:void(0)" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-graduation-cap"></i></span>
                            <span class="text">Lessons</span>
                        </a>
                        <!-- Submenu start -->
                        <ul class="sidebar-submenu">
                            <li class="sidebar-submenu__item">
                                <a href="<?= base_url('lessons') ?>" class="sidebar-submenu__link">Lessons </a>
                            </li>
                        </ul>
                        <!-- Submenu End -->
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="students.html" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-users-three"></i></span>
                            <span class="text">Students</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="assignment.html" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-clipboard-text"></i></span>
                            <span class="text">Assignments</span>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="sidebar-menu__item">
                        <a href="mentors.html" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-users"></i></span>
                            <span class="text">Mentors</span>
                        </a>
                    </li>
                    <?php if (customMiddleware() == 'Mentor' || customMiddleware() == 'Super Admin' || customMiddleware() == 'Admin for Teachers') { ?>
                    <li class="sidebar-menu__item">
                        <a href="resources.html" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-bookmarks"></i></span>
                            <span class="text">Resources</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="message.html" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-chats-teardrop"></i></span>
                            <span class="text">Messages</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="analytics.html" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-chart-bar"></i></span>
                            <span class="text">Analytics</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="event.html" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-calendar-dots"></i></span>
                            <span class="text">Events</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="library.html" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-books"></i></span>
                            <span class="text">Library</span>
                        </a>
                    </li>
                    <li class="sidebar-menu__item">
                        <a href="<?= base_url('pricing-plan') ?>" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-coins"></i></span>
                            <span class="text">Pricing</span>
                        </a>
                    </li>

                <?php } ?>

                <li class="sidebar-menu__item">
                    <span class="text-gray-300 text-sm px-20 pt-20 fw-semibold border-top border-gray-100 d-block text-uppercase">Settings</span>
                </li>
                <li class="sidebar-menu__item">
                    <a href="setting.html" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-gear"></i></span>
                        <span class="text">Account Settings</span>
                    </a>
                </li>

                <li class="sidebar-menu__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-shield-check"></i></span>
                        <span class="text">Authetication</span>
                    </a>
                    <!-- Submenu start -->
                    <ul class="sidebar-submenu">
                        <li class="sidebar-submenu__item">
                            <a href="sign-in.html" class="sidebar-submenu__link">Sign In</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="sign-up.html" class="sidebar-submenu__link">Sign Up</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="forgot-password.html" class="sidebar-submenu__link">Forgot Password</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="reset-password.html" class="sidebar-submenu__link">Reset Password</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="verify-email.html" class="sidebar-submenu__link">Verify Email</a>
                        </li>
                        <li class="sidebar-submenu__item">
                            <a href="two-step-verification.html" class="sidebar-submenu__link">Two Step Verification</a>
                        </li>
                    </ul>
                    <!-- Submenu End -->
                </li>

            </ul>
        </div>
        <div class="p-20 pt-80">
            <div class="bg-main-50 p-20 pt-0 rounded-16 text-center mt-74">
                <span class="border border-5 bg-white mx-auto border-primary-50 w-114 h-114 rounded-circle flex-center text-success-600 text-2xl translate-n74">
                    <img src="<?= asset_url() ?>images/icons/certificate.png" alt="" class="centerised-img">
                </span>
                <div class="mt-n74">
                    <h5 class="mb-4 mt-22">Get Pro Certificate</h5>
                    <p class="">Explore 400+ courses with lifetime members</p>
                    <a href="pricing-plan.html" class="btn btn-main mt-16 rounded-pill">Get Access</a>
                </div>
            </div>
        </div>
    </div>

</aside>