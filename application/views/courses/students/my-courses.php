<!-- Breadcrumb Start -->
<div class="breadcrumb mb-24">
    <ul class="flex-align gap-4">
        <li><a href="index.html" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
        <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
        <li><span class="text-main-600 fw-normal text-15">My Courses</span></li>
    </ul>
</div>
<!-- Breadcrumb End -->

<!-- Course Tab Start -->
<div class="card">
    <div class="card-body">
        <div class="mb-24 flex-between gap-16 flex-wrap-reverse">
            <a href="<?=base_url('admin-courses/createCourse')?>" class="btn btn-main rounded-pill py-7 flex-align gap-4 fw-normal">
                <span class="d-flex text-md"><i class="ph ph-plus"></i></span>
                Create New Course
            </a>
        </div>
        <div id="step-3-content" class="card step-content" style="display:none;">
            <div class="card-header border-bottom border-gray-100 flex-align gap-8">
                <h5 class="mb-0">About Course</h5>
                <button type="button" class="text-main-600 text-md d-flex" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="About Course">
                    <i class="ph-fill ph-question"></i>
                </button>
            </div>
            <div class="card-body">
                <h6 class="mb-8 fw-semibold">Course Description</h6>
                <div class="p-16 rounded-12 bg-main-50 mb-20">
                    <p id="about-description"></p>
                </div>

                <h6 class="mb-8 fw-semibold">Attachment Files</h6>
                <div class="upload-card-item p-16 rounded-12 bg-main-50 mb-20">
                    <div class="flex-between gap-8">
                        <div class="flex-align gap-10 flex-wrap">
                            <span class="w-36 h-36 text-lg rounded-circle bg-white flex-center text-main-600 flex-shrink-0">
                                <i class="ph ph-paperclip"></i>
                            </span>
                            <div class="">
                                <p class="text-15 text-gray-500">Drag & drop your single/multiple videos of course, or <label for="video_resource" class="text-main-600 cursor-pointer">Browse</label> </p>
                                <input type="file" id="video_resource" accept="video/mp4,video/x-m4v,application/pdf" multiple hidden>
                                <p class="text-13 text-gray-600">(max file size 100mb each)</p>

                                <span class="show-uploaded-video-name d-none" id="uploaded-video-name"></span>

                                <!-- Display progress bar and status -->
                                <div id="res_progress-container" style="display:none;">
                                    <progress id="res_progress-bar" value="0" max="100" style="width:100%"></progress>
                                    <span id="res_progress-status"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-align gap-8">
                            <span class="text-main-600 d-flex text-xl"><i class="ph-fill ph-check-circle"></i></span>
                            <!-- Dropdown Start -->
                            <div class="dropdown flex-shrink-0">
                                <button class="text-gray-600 text-xl d-flex rounded-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ph-fill ph-dots-three-outline"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                                    <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                        <div class="card-body p-12">
                                            <div class="max-h-200 overflow-y-auto scroll-sm pe-8">
                                                <ul>
                                                    <li class="mb-0">
                                                        <button type="button" class="delete-item-btn py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 w-100 rounded-8 fw-normal text-xs d-block text-start">
                                                            <span class="text">Delete</span>
                                                        </button>
                                                        <button type="button" class="view-item-btn py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 w-100 rounded-8 fw-normal text-xs d-block text-start">
                                                            <span class="text">View</span>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Dropdown end -->
                        </div>
                    </div>
                </div>
                <div id="res-uploaded-video-list" class="upload-card-item p-16 rounded-12 bg-main-50 mb-20">

                </div>

                <div class="flex-align justify-content-end gap-8 mt-20">
                    <button onclick="nextStep(2)" href="javascript:void(0)" class="btn btn-outline-main rounded-pill py-9">Back</button>
                    <button onclick="nextStep(4)" href="javascript:void(0)" class="btn btn-main rounded-pill py-9">Continue</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#video_resource').on('change', function(e) {
            console.log('File input changed');
            var files = e.target.files;
            if (files.length > 0) {
                uploadResources(files);
            }
        });
    });

    function uploadResources(files) {
        var formData = new FormData();

        // Append files to formData
        for (var i = 0; i < files.length; i++) {
            formData.append('res_files[]', files[i]);
        }

        formData.append('course_id', $("#course_id").val());
        let description = $("#description").val();

        $.ajax({
            url: '<?= base_url('CoursesController/uploadResources'); ?>', // Set your upload URL here
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json', // Specify that the expected response is JSON
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var percentComplete = (e.loaded / e.total) * 100;
                        $('#res_progress-container').show();
                        $('#res_progress-bar').val(percentComplete);
                        $('#res_progress-status').text(Math.round(percentComplete) + '% complete');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                console.log(response); // Log the response to check the data structure
                if (response.resUploadedFiles && response.resUploadedFiles.length > 0) {
                    $('#res-uploaded-video-list').empty(); // Clear previous list
                    response.resUploadedFiles.forEach(function(file) {
                        // Dynamically populate file details
                        var fileHTML = `
                    <div class="flex-between gap-8">
                        <div class="flex-align gap-10">
                            <span class="w-36 h-36 text-lg rounded-circle bg-white flex-center text-main-600">
                                <i class="ph ph-files"></i>
                            </span>
                            <div>
                                <p class="text-15 text-gray-500">${file.name}</p> <!-- File name -->
                                <p class="text-13 text-gray-600">${(file.size / 1024).toFixed(2)} MB</p> <!-- File size in MB -->
                            </div>
                        </div>
                        <div class="flex-align gap-8">
                            <span class="text-main-600 d-flex text-xl"><i class="ph-fill ph-check-circle"></i></span>
                            <!-- Dropdown Start -->
                            <div class="dropdown flex-shrink-0">
                                <button class="text-gray-600 text-xl d-flex rounded-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ph-fill ph-dots-three-outline"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                                    <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                        <div class="card-body p-12">
                                            <div class="max-h-200 overflow-y-auto scroll-sm pe-8">
                                                <ul>
                                                    <li class="mb-0">
                                                        <button type="button" class="delete-item-btn py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 w-100 rounded-8 fw-normal text-xs d-block text-start">
                                                            <span class="text">Delete</span>
                                                        </button>
                                                        <button type="button" class="view-item-btn py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 w-100 rounded-8 fw-normal text-xs d-block text-start">
                                                            <span class="text">View</span>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                        $('#res-uploaded-video-list').append(fileHTML); // Append the dynamically created HTML
                    });
                } else {
                    $('#res-uploaded-video-list').append('<p>No files uploaded yet.</p>');
                }
                $('#res_progress-container').hide(); // Hide progress after completion
            },
            error: function(xhr, status, error) {
                console.error('Error:', error); // Use console.error for better visibility of errors
            }
        });
    }
</script>

<!-- My Courses Tab Content -->
<div class="tab-pane fade show active" id="my-courses" role="tabpanel" aria-labelledby="my-courses-tab">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">My Courses</h4>
                    <div class="d-flex gap-2">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search courses..." id="courseSearch">
                            <button class="btn btn-primary" type="button">
                                <i class="ph ph-magnifying-glass"></i>
                            </button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="ph ph-funnel"></i> Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-filter="all">All Courses</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="in-progress">In Progress</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="completed">Completed</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="not-started">Not Started</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4" id="coursesGrid">
                        <!-- Course cards will be dynamically loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course Card Template -->
<template id="courseCardTemplate">
    <div class="col-md-6 col-lg-4 course-card">
        <div class="card h-100">
            <div class="position-relative">
                <img src="" class="card-img-top course-thumbnail" alt="Course Thumbnail">
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-primary course-category"></span>
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title course-title"></h5>
                <p class="card-text course-description"></p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <img src="" class="rounded-circle me-2 creator-avatar" width="24" height="24" alt="Creator">
                        <small class="text-muted creator-name"></small>
                    </div>
                    <div class="course-rating">
                        <i class="ph ph-star-fill text-warning"></i>
                        <span class="rating-score"></span>
                        <small class="text-muted rating-count"></small>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted progress-text">0% Complete</small>
                    <a href="#" class="btn btn-primary btn-sm course-link">Continue Learning</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load courses when the page loads
    loadCourses();

    // Search functionality
    const searchInput = document.getElementById('courseSearch');
    searchInput.addEventListener('input', debounce(function() {
        loadCourses(this.value);
    }, 300));

    // Filter functionality
    document.querySelectorAll('[data-filter]').forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            const filterValue = this.dataset.filter;
            loadCourses(searchInput.value, filterValue);
        });
    });
});

function loadCourses(searchTerm = '', filter = 'all') {
    fetch('/mentor-courses/getMentorCourses')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const coursesGrid = document.getElementById('coursesGrid');
                coursesGrid.innerHTML = ''; // Clear existing courses

                data.data.forEach(course => {
                    if (shouldShowCourse(course, searchTerm, filter)) {
                        const courseCard = createCourseCard(course);
                        coursesGrid.appendChild(courseCard);
                    }
                });
            }
        })
        .catch(error => console.error('Error loading courses:', error));
}

function shouldShowCourse(course, searchTerm, filter) {
    // Search term filter
    if (searchTerm && !course.title.toLowerCase().includes(searchTerm.toLowerCase())) {
        return false;
    }

    // Status filter
    switch (filter) {
        case 'in-progress':
            return course.progress > 0 && course.progress < 100;
        case 'completed':
            return course.progress === 100;
        case 'not-started':
            return course.progress === 0;
        default:
            return true;
    }
}

function createCourseCard(course) {
    const template = document.getElementById('courseCardTemplate');
    const card = template.content.cloneNode(true);

    // Set course data
    card.querySelector('.course-thumbnail').src = course.thumbnail || '/assets/images/thumbs/course-img1.png';
    card.querySelector('.course-category').textContent = course.category;
    card.querySelector('.course-title').textContent = course.title;
    card.querySelector('.course-description').textContent = course.description || 'No description available';
    card.querySelector('.creator-name').textContent = course.creator;
    card.querySelector('.rating-score').textContent = course.rating?.score || '0.0';
    card.querySelector('.rating-count').textContent = `(${course.rating?.count || 0})`;
    card.querySelector('.progress-bar').style.width = `${course.progress || 0}%`;
    card.querySelector('.progress-text').textContent = `${course.progress || 0}% Complete`;
    card.querySelector('.course-link').href = course.url;

    return card;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func.apply(this, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>

<style>
.course-card {
    transition: transform 0.2s ease-in-out;
}

.course-card:hover {
    transform: translateY(-5px);
}

.course-thumbnail {
    height: 200px;
    object-fit: cover;
}

.progress {
    background-color: #e9ecef;
}

.progress-bar {
    background-color: #0d6efd;
    transition: width 0.3s ease-in-out;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5em 0.8em;
}

.creator-avatar {
    object-fit: cover;
}

.course-rating {
    font-size: 0.9rem;
}

.course-rating i {
    font-size: 1rem;
}
</style>