<div class="dashboard-body">

    <div class="breadcrumb-with-buttons mb-24 flex-between flex-wrap gap-8">
        <!-- Breadcrumb Start -->
        <div class="breadcrumb mb-24">
            <ul class="flex-align gap-4">
                <li><a href="index.html" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
                <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
                <li><span class="text-main-600 fw-normal text-15">Create Account</span></li>
            </ul>
        </div>
        <!-- Breadcrumb End -->

        <!-- Buttons Start -->
        <div class="flex-align justify-content-end gap-8">
            <button type="button" id="saveDraftBtn" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-9">Save as Draft</button>

            <button type="button" class="btn btn-main rounded-pill py-9" disabled>Publish Course</button>
        </div>
        <!-- Buttons End -->
    </div>
    <input type="hidden" name="course_id" id="course_id" value="0" />
    <input type="hidden" name="lesson_id" id="lesson_id" value="0" />
    <input type="hidden" name="description" id="description" value="0" />



    <!-- Create Course Step List Start -->
    <ul class="step-list mb-24">
        <li class="step-list__item py-15 px-24 text-15 text-heading fw-medium flex-center gap-6  active">
            <span class="icon text-xl d-flex"><i class="ph ph-circle"></i></span>
            Course Details
            <span class="line position-relative"></span>
        </li>
        <!-- <li class="step-list__item py-15 px-24 text-15 text-heading fw-medium flex-center gap-6  ">
            <span class="icon text-xl d-flex"><i class="ph ph-circle"></i></span>
            Upload Videos
            <span class="line position-relative"></span>
        </li>
        <li class="step-list__item py-15 px-24 text-15 text-heading fw-medium flex-center gap-6  ">
            <span class="icon text-xl d-flex"><i class="ph ph-circle"></i></span>
            About Course
            <span class="line position-relative"></span>
        </li> -->
        <li class="step-list__item py-15 px-24 text-15 text-heading fw-medium flex-center gap-6  ">
            <span class="icon text-xl d-flex"><i class="ph ph-circle"></i></span>
            Create Quiz
            <span class="line position-relative"></span>
        </li>
        <li class="step-list__item py-15 px-24 text-15 text-heading fw-medium flex-center gap-6  ">
            <span class="icon text-xl d-flex"><i class="ph ph-circle"></i></span>
            Publish Course
            <span class="line position-relative"></span>
        </li>
    </ul>
    <!-- Create Course Step List End -->

    <!-- Course Tab Start -->
    <div id="step-1-content" class="card step-content">
        <div class="card-header border-bottom border-gray-100 flex-align gap-8 ">
            <h5 class="mb-0">Course Details</h5>
            <button type="button" class="text-main-600 text-md d-flex" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Course Details">
                <i class="ph-fill ph-question"></i>
            </button>
        </div>
        <div class="card-body">
            <form id="courseForm" action="#" method="post" enctype="multipart/form-data">
                <div class="row gy-20">
                    <div class="col-xxl-3 col-md-4 col-sm-5">
                        <div class="mb-20">
                            <label class="h5 fw-semibold font-heading mb-0">Thumbnail Image <span class="text-13 text-gray-400 fw-medium">(Required)</span> </label>
                        </div>
                        <div id="fileUpload" class="fileUpload image-upload"></div>
                    </div>
                    <div class="col-xxl-9 col-md-8 col-sm-7">
                        <div class="row g-20">
                            <div class="col-sm-12">
                                <label for="courseTitle" class="h5 mb-8 fw-semibold font-heading">Course Title <span class="text-13 text-gray-400 fw-medium">(Required)</span> </label>
                                <div class="position-relative">
                                    <input name="course_title" type="text" class="text-counter placeholder-13 form-control py-11 pe-76" maxlength="100" id="courseTitle" placeholder="Title of the Course">
                                    <div class="text-gray-400 position-absolute inset-inline-end-0 top-50 translate-middle-y me-16">
                                        <span id="current">18</span>
                                        <span id="maximum">/ 100</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="courseCategory" class="h5 mb-8 fw-semibold font-heading">Course Category </label>
                                <div class="position-relative">
                                    <select name="category_id" id="courseCategory" class="form-select py-9 placeholder-13 text-15">
                                        <option value="" disabled selected>Select course category</option>
                                        <?php foreach ($categories as $category) { ?>
                                            <option value="<?= $category->category_id ?>"><?= $category->category_name ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="courseLevel" class="h5 mb-8 fw-semibold font-heading">Course Level</label>
                                <div class="position-relative">
                                    <select name="course_level" id="courseLevel" class="form-select py-9 placeholder-13 text-15">
                                        <option value="" disabled selected>Select course level</option>
                                        <option value="Beginner">Beginner</option>
                                        <option value="Intermediate">Intermediate</option>
                                        <option value="Advanced">Advanced</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="courseTime" class="h5 mb-8 fw-semibold font-heading">Course Time</label>
                                <div class="position-relative">
                                    <select name="timeline" id="courseTime" class="form-select py-9 placeholder-13 text-15">
                                        <option value="0" disabled selected>Select course Timeline</option>
                                        <option value="5 Hours">5 Hours</option>
                                        <option value="10 Hours">10 Hours</option>
                                        <option value="15 Hours">15 Hours</option>
                                        <option value="20 Hours">20 Hours</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="courseLesson" class="h5 mb-8 fw-semibold font-heading">Total Lesson</label>
                                <div class="position-relative">
                                    <select name="lesson_id[]" id="courseLesson" class="locationMultiple form-select py-9 placeholder-13 text-15" multiple>
                                        <option value="" disabled>Select course lesson</option>
                                        <?php foreach ($lessons as $lesson) { ?>
                                            <option value="<?= $lesson->lesson_id ?>"><?= $lesson->lesson_title ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <label for="courseLesson" class="h5 mb-8 fw-semibold font-heading">Course Description</label>
                                <div class="position-relative">
                                    <textarea name="description" class="form-control py-11" placeholder="Course Description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-align justify-content-end gap-8">
                        <a href="mentor-courses.html" class="btn btn-outline-main rounded-pill py-9">Cancel</a>
                        <button type="submit" class="btn btn-main rounded-pill py-9">Continue</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    // $this->load->view('courses/upload_videos'); 
    ?>
    <?php
    //  $this->load->view('courses/about_course'); 
    ?>
    <?php $this->load->view('courses/create_quiz'); ?>
    <?php $this->load->view('courses/publish_course'); ?>

    <!-- Course Tab End -->
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Bind click event to the button
        $('#saveDraftBtn').click(function() {
            $('#courseForm').submit(); // Trigger form submission
        });

        // AJAX form submission
        $('#courseForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this); // Create a FormData object from the form

            $.ajax({
                type: "POST",
                url: "<?= base_url('CoursesController/storeCourse'); ?>", // Update with your controller and method
                data: formData,
                contentType: false, // Important for file uploads
                processData: false, // Prevent jQuery from automatically transforming the data into a query string
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // alert(`message: ${response.message} , course_id: ${response.course_id}`);
                        $("#course_id").val(response.course_id);
                        $("#lesson_id").val(response.lesson_id);
                        $("#description").val(response.description);
                        nextStep(2);
                        // location.reload(); // Optionally, reload the page or redirect
                    } else {
                        alert(response.message); // Show error message
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error submitting form: ' + error); // Show AJAX error
                }
            });
        });
    });
</script>

<script>
    function nextStep(step) {
        // Hide all step contents
        $('.step-content').hide();

        // Show the content of the current step
        $('#step-' + step + '-content').show();
        let description = $("#description").val();

        $("#about-description").html(description);

        // Remove 'active' and 'done' classes from all steps
        $('.step-list__item').removeClass('active done');

        // Add 'done' class to all previous steps
        $('.step-list__item').each(function(index) {
            if (index < step - 1) {
                $(this).addClass('done');
            }
        });

        // Add 'active' class to the current step
        $('#step-' + step).addClass('active');
    }
</script>