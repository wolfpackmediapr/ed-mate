<div class="top-navbar flex-between gap-16">
    <div class="flex-align gap-16">
        <!-- Toggle Button -->
        <button type="button" class="toggle-btn d-xl-none d-flex text-26 text-gray-500">
            <i class="ph ph-list"></i>
        </button>

        <!-- Search Form -->
        <form action="#" class="w-350 d-sm-block d-none">
            <div class="position-relative">
                <button type="submit" class="input-icon text-xl d-flex text-gray-100 pointer-event-none">
                    <i class="ph ph-magnifying-glass"></i>
                </button>
                <input type="text" class="form-control ps-40 h-40 border-transparent focus-border-main-600 bg-main-50 rounded-pill placeholder-15" placeholder="Search...">
            </div>
        </form>
    </div>

    <div class="flex-align gap-16">
        <div class="flex-align gap-8">
            <!-- Dark Mode Toggle -->
            <button id="darkModeToggle" class="text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center" type="button">
                <i class="ph ph-moon-stars"></i>
            </button>

            <!-- Notification Dropdown -->
            <div class="dropdown">
                <button class="dropdown-btn shaking-animation text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="position-relative">
                        <i class="ph ph-bell"></i>
                        <span class="alarm-notify position-absolute end-0"></span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                    <div class="card border border-gray-100 rounded-12 box-shadow-custom p-0 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="py-8 px-24 bg-main-600">
                                <div class="flex-between">
                                    <h5 class="text-xl fw-semibold text-white mb-0">Notifications</h5>
                                    <div class="flex-align gap-12">
                                        <button type="button" class="bg-white rounded-6 text-sm px-8 py-2 hover-text-primary-600">New</button>
                                        <button type="button" class="close-dropdown hover-scale-1 text-xl text-white">
                                            <i class="ph ph-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="p-24 max-h-270 overflow-y-auto scroll-sm">
                                <!-- Notification items will be dynamically loaded here -->
                            </div>
                            <a href="#" class="py-13 px-24 fw-bold text-center d-block text-primary-600 border-top border-gray-100 hover-text-decoration-underline">View All</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Language Dropdown -->
            <div class="dropdown">
                <button class="text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ph ph-globe"></i>
                </button>
                <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                    <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                        <div class="card-body">
                            <div class="max-h-270 overflow-y-auto scroll-sm pe-8">
                                <!-- Language options will be dynamically loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="users arrow-down-icon border border-gray-200 rounded-pill p-4 d-inline-block pe-40 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="position-relative">
                    <img src="<?= asset_url('images/thumbs/user-img.png') ?>" alt="User" class="h-32 w-32 rounded-circle">
                    <span class="activation-badge w-8 h-8 position-absolute inset-block-end-0 inset-inline-end-0"></span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                    <div class="card-body">
                        <div class="flex-align gap-8 mb-20 pb-20 border-bottom border-gray-100">
                            <img src="<?= asset_url('images/thumbs/user-img.png') ?>" alt="User" class="w-54 h-54 rounded-circle">
                            <div>
                                <h4 class="mb-0"><?= $user->email ?? 'User' ?></h4>
                                <p class="fw-medium text-13 text-gray-200"><?= $user->email ?? 'user@example.com' ?></p>
                            </div>
                        </div>
                        <ul class="max-h-270 overflow-y-auto scroll-sm pe-4">
                            <li class="mb-4">
                                <a href="<?= base_url('setting') ?>" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-gear"></i></span>
                                    <span class="text">Account Settings</span>
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="<?= base_url('pricing-plan') ?>" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-chart-bar"></i></span>
                                    <span class="text">Upgrade Plan</span>
                                </a>
                            </li>
                            <li class="pt-8 border-top border-gray-100">
                                <a href="<?= base_url('logout') ?>" class="py-12 text-15 px-20 hover-bg-danger-50 text-gray-300 hover-text-danger-600 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-danger-600 d-flex"><i class="ph ph-sign-out"></i></span>
                                    <span class="text">Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 