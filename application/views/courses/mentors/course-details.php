<style>
    .hidden {
        display: none;
    }
</style>
<div class="dashboard-body">

    <!-- Breadcrumb Start -->
    <div class="breadcrumb mb-24">
        <ul class="flex-align gap-4">
            <li><a href="index.html" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
            <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
            <li><span class="text-main-600 fw-normal text-15">Course Details</span></li>
        </ul>
    </div>
    <!-- Breadcrumb End -->
    <?php
    $createdBY = $this->common_model->select_where_return_row('*', 'users', array('user_id' => $course->created_by));

    ?>
    <div class="row gy-4">
        <div class="col-md-8">
            <!-- Course Card Start -->
            <div id="display-selected-course" class="card">
                <div class="card-body p-lg-20 p-sm-3">
                    <div class="flex-between flex-wrap gap-12 mb-20">
                        <div>
                            <h3 class="mb-4"><?= $course->course_title ?></h3>
                            <p class="text-gray-600 text-15"><?= $createdBY->username ?></p>
                        </div>

                        <div class="flex-align flex-wrap gap-24">
                            <span class="py-6 px-16 bg-main-50 text-main-600 rounded-pill text-15"><?= $category->category_name ?></span>
                            <div class=" share-social position-relative">
                                <button type="button" class="share-social__button text-gray-200 text-26 d-flex hover-text-main-600"><i class="ph ph-share-network"></i></button>
                                <div class="share-social__icons bg-white box-shadow-2xl p-16 border border-gray-100 rounded-8 position-absolute inset-block-start-100 inset-inline-end-0">
                                    <ul class="flex-align gap-8">
                                        <li>
                                            <a href="https://www.facebook.com" class="flex-center w-36 h-36 border border-main-600 text-white rounded-circle text-xl bg-main-600 hover-bg-main-800 hover-border-main-800"><i class="ph ph-facebook-logo"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.google.com" class="flex-center w-36 h-36 border border-main-600 text-white rounded-circle text-xl bg-main-600 hover-bg-main-800 hover-border-main-800"> <i class="ph ph-twitter-logo"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.twitter.com" class="flex-center w-36 h-36 border border-main-600 text-white rounded-circle text-xl bg-main-600 hover-bg-main-800 hover-border-main-800"><i class="ph ph-linkedin-logo"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.instagram.com" class="flex-center w-36 h-36 border border-main-600 text-white rounded-circle text-xl bg-main-600 hover-bg-main-800 hover-border-main-800"><i class="ph ph-instagram-logo"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="bookmark-icon text-gray-200 text-26 d-flex hover-text-main-600">
                                <i class="ph ph-bookmarks"></i>
                            </button>
                        </div>
                    </div>

                    <?php
                    $video_path = $resources[0]->path;
                    $play_path = base_url() . 'uploads/resources/' . $video_path;
                    ?>

                    <div class="rounded-16 overflow-hidden">
                        <video id="player" class="player" playsinline controls data-poster="<?= base_url() ?>uploads/courses/<?= $course->thumbnail_image ?>">
                            <source src="<?= $play_path ?>" type="video/mp4">
                            <source src="<?= $play_path ?>" type="video/webm">
                        </video>
                    </div>

                    <div class="mt-24">
                        <div class="mb-24 pb-24 border-bottom border-gray-100">
                            <h5 class="mb-12 fw-bold">About this course</h5>
                            <p class="text-gray-300 text-15"><?= $lessons[0]->content ?></p>
                        </div>
                        <div class="mb-24 pb-24 border-bottom border-gray-100">
                            <h5 class="mb-12 fw-bold">Description</h5>
                            <p class="text-gray-300 text-15 mb-8"><?= $course->description ?> </p>
                        </div>
                        <div class="">
                            <h5 class="mb-12 fw-bold">Instructor</h5>
                            <div class="flex-align gap-8">
                                <img src="assets/images/thumbs/mentor-img1.png" alt="" class="w-44 h-44 rounded-circle object-fit-cover flex-shrink-0">
                                <div class="d-flex flex-column">
                                    <h6 class="text-15 fw-bold mb-0">Brooklyn Simmons</h6>
                                    <span class="text-13 text-gray-300">Web Design Instructor</span>
                                    <div class="flex-align gap-4 mt-4">
                                        <span class="text-15 fw-bold text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-13 fw-bold text-gray-600">4.9</span>
                                        <span class="text-13 fw-bold text-gray-300">(12k)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Course Card End -->
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-0">
                    <?php foreach ($lessons as $index => $lesson) {
                        // Fetch resources for the current lesson
                        $resources = $this->common_model->select_where_return('resources', array('lesson_id' => $lesson->lesson_id));


                        // Initialize variables
                        $totalResources = count($resources);
                        $completedResources = 0; // You may need to modify this if you have a way to track completed resources
                        $totalDuration = 0;

                        foreach ($resources as $resource) {
                            $filePath = FCPATH . 'uploads/resources/' . $resource->path;
                            $get_duration = get_video_details($filePath);

                            // Check if the duration is set
                            if (isset($get_duration['duration'])) {
                                // Split the duration string into minutes and seconds
                                list($minutes, $seconds) = explode(':', $get_duration['duration']);

                                // Calculate total duration in minutes
                                $durationInMinutes = floatval($minutes) + (floatval($seconds) / 60);

                                // Add to total duration
                                $totalDuration += $durationInMinutes;
                            }
                        }

                        // After calculating total duration, you can format it as desired
                        $totalDurationFormatted = number_format($totalDuration, 2);

                        // Format total duration
                        $formattedDuration = number_format($totalDuration, 1) . ' min';
                    ?>
                        <div class="course-item">
                            <button type="button" class="course-item__button <?= $index === 0 ? 'active' : '' ?> flex-align gap-4 w-100 p-16 border-bottom border-gray-100"
                                onclick="toggleLesson(this)">
                                <span class="d-block text-start">
                                    <span class="d-block h5 mb-0 text-line-1"><?= htmlspecialchars($lesson->lesson_title) ?></span>
                                    <span class="d-block text-15 text-gray-300"><?= $completedResources . ' / ' . $totalResources . ' | ' . $formattedDuration ?></span>
                                </span>
                                <span class="course-item__arrow ms-auto text-20 text-gray-500"><i class="ph ph-arrow-right"></i></span>
                            </button>
                            <div class="course-item-dropdown <?= $index === 0 ? 'active' : 'hidden' ?> border-bottom border-gray-100">
                                <ul class="course-list p-16 pb-0">
                                    <?php if (!empty($resources)) {
                                        foreach ($resources as $resIndex => $resource) {
                                            $filePath = FCPATH . 'uploads/resources/' . $resource->path;
                                            $get_duration = get_video_details($filePath);
                                            // Check if the current index is less than 2 to determine if it should be active
                                            $class = $resIndex < 2 ? 'active' : 'disabled';
                                    ?>
                                            <li class="course-list__item flex-align gap-8 mb-16 <?= $class ?>">
                                                <span class="circle flex-shrink-0 text-32 d-flex text-gray-100"><i class="ph ph-circle"></i></span>
                                                <div class="w-100">
                                                    <a onclick="renderVideo(<?= $resource->resource_id ?>,<?= $course->course_id ?>,<?= $lesson->lesson_id ?>)" href="javascript:void(0)" class="text-gray-300 fw-medium d-block hover-text-main-600 d-lg-block">
                                                        <?= ($resIndex + 1) . '. ' . htmlspecialchars($resource->title) ?>
                                                        <span class="text-gray-300 fw-normal d-block"><?= $get_duration['duration']; ?> min</span>
                                                    </a>
                                                </div>
                                            </li>
                                        <?php }
                                    } else { ?>
                                        <li class="course-list__item flex-align gap-8 mb-16">
                                            <span class="circle flex-shrink-0 text-32 d-flex text-gray-100"><i class="ph ph-circle"></i></span>
                                            <div class="w-100">
                                                <span class="text-gray-300 fw-medium d-block">No resources available</span>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>

            <div class="card mt-24">
                <div class="card-body">
                    <h4 class="mb-20">Featured courses</h4>
                    <div class="rounded-16 overflow-hidden">
                        <video id="featuredPlayer" class="player" playsinline controls data-poster="assets/images/thumbs/featured-course.png">
                            <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-720p.mp4" type="video/mp4">
                            <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-720p.mp4" type="video/webm">
                        </video>
                    </div>
                    <h5 class="mb-16 mt-20">Development for Beginners</h5>
                    <p class="text-gray-300">The Fender Acoustic Guitar is the best choice for both beginners and professionals offering a great sound.</p>

                    <?php

                    $is_paid = $this->common_model->select_where_return('course_pricing', array('course_id' => $course->course_id, 'user_id' => $this->session->userdata('user_id'), 'is_paid' => 1));
                    if (!$is_paid) {
                    ?>

                        <a href="<?= base_url('select-plan/') ?><?= encode($course->course_id) ?>/<?= encode($course->created_by) ?>" class="btn btn-main rounded-pill py-11 w-100  mt-16">Upgrade Now</a>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function toggleLesson(button) {
        // Get the dropdown of the current button
        const dropdown = button.nextElementSibling;

        // Toggle active class
        dropdown.classList.toggle('active');

        // If the dropdown is not currently visible, hide all other dropdowns
        if (!dropdown.classList.contains('active')) {
            // Find all other dropdowns
            const allDropdowns = document.querySelectorAll('.course-item-dropdown');
            allDropdowns.forEach((drop) => {
                if (drop !== dropdown) {
                    drop.classList.remove('active');
                }
            });
        }
    }

    function initJsPlayer() {
        // Plyr Js Start
        const player = new Plyr('#player');
        const featuredPlayer = new Plyr('#featuredPlayer');
        // Plyr Js End
    }

    function renderVideo(resource_id, course_id, lesson_id) {
        const player = new Plyr('#player');
        // alert(`resource_id : ${resource_id} => course_id: ${course_id} => lesson_id: ${lesson_id}`);
        var renderCourse = $("#display-selected-course"); // Replace with your container ID
        renderCourse.empty(); // Clear previous entries if necessary

        $.ajax({
            type: "POST",
            url: "<?= base_url('StudentCoursesController/renderVideo'); ?>",
            data: {
                resource_id: resource_id,
                course_id: course_id,
                lesson_id: lesson_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var courseHTML = `
                    <div class="card-body p-lg-20 p-sm-3">
                    <div class="flex-between flex-wrap gap-12 mb-20">
                        <div>
                            <h3 class="mb-4"> ${response.course.course_title}</h3>
                            <p class="text-gray-600 text-15">${response.createdBY.username}</p>
                        </div>

                        <div class="flex-align flex-wrap gap-24">
                            <span class="py-6 px-16 bg-main-50 text-main-600 rounded-pill text-15">${response.category.category_name}</span>
                            <div class=" share-social position-relative">
                                <button type="button" class="share-social__button text-gray-200 text-26 d-flex hover-text-main-600"><i class="ph ph-share-network"></i></button>
                                <div class="share-social__icons bg-white box-shadow-2xl p-16 border border-gray-100 rounded-8 position-absolute inset-block-start-100 inset-inline-end-0">
                                    <ul class="flex-align gap-8">
                                        <li>
                                            <a href="https://www.facebook.com" class="flex-center w-36 h-36 border border-main-600 text-white rounded-circle text-xl bg-main-600 hover-bg-main-800 hover-border-main-800"><i class="ph ph-facebook-logo"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.google.com" class="flex-center w-36 h-36 border border-main-600 text-white rounded-circle text-xl bg-main-600 hover-bg-main-800 hover-border-main-800"> <i class="ph ph-twitter-logo"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.twitter.com" class="flex-center w-36 h-36 border border-main-600 text-white rounded-circle text-xl bg-main-600 hover-bg-main-800 hover-border-main-800"><i class="ph ph-linkedin-logo"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.instagram.com" class="flex-center w-36 h-36 border border-main-600 text-white rounded-circle text-xl bg-main-600 hover-bg-main-800 hover-border-main-800"><i class="ph ph-instagram-logo"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="bookmark-icon text-gray-200 text-26 d-flex hover-text-main-600">
                                <i class="ph ph-bookmarks"></i>
                            </button>
                        </div>
                    </div>

                    <?php
                    // $video_path = $resources[0]->path;
                    // $play_path = base_url() . 'uploads/resources/' . $video_path;
                    ?>

                    <div class="rounded-16 overflow-hidden">
                        <video id="player" class="player" playsinline controls data-poster="<?= base_url() ?>uploads/courses/${response.course.thumbnail_image}">
                            <source src="<?= base_url() ?>uploads/resources/${response.resource.path}" type="video/mp4">
                            <source src="<?= base_url() ?>uploads/resources/${response.resource.path}" type="video/webm">
                        </video>
                    </div>

                    <div class="mt-24">
                        <div class="mb-24 pb-24 border-bottom border-gray-100">
                            <h5 class="mb-12 fw-bold">About this course</h5>
                            <p class="text-gray-300 text-15">${response.lesson.content}</p>
                        </div>
                        <div class="mb-24 pb-24 border-bottom border-gray-100">
                            <h5 class="mb-12 fw-bold">Description</h5>
                            <p class="text-gray-300 text-15 mb-8">${response.course.description}</p>
                        </div>
                        <div class="">
                            <h5 class="mb-12 fw-bold">Instructor</h5>
                            <div class="flex-align gap-8">
                                <img src="assets/images/thumbs/mentor-img1.png" alt="" class="w-44 h-44 rounded-circle object-fit-cover flex-shrink-0">
                                <div class="d-flex flex-column">
                                    <h6 class="text-15 fw-bold mb-0">Brooklyn Simmons</h6>
                                    <span class="text-13 text-gray-300">Web Design Instructor</span>
                                    <div class="flex-align gap-4 mt-4">
                                        <span class="text-15 fw-bold text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-13 fw-bold text-gray-600">4.9</span>
                                        <span class="text-13 fw-bold text-gray-300">(12k)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    `;
                    renderCourse.append(courseHTML);

                } else {
                    // Error creating lesson
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                // AJAX request failed
                alert('Error creating lesson: ' + error);
            }
        });
    }
</script>