<div class="dashboard-body">
    <!-- Progress Indicator -->
    <div class="progress-indicator mb-24">
        <div class="d-flex justify-content-between align-items-center">
            <div class="step-item active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">Course Details</div>
            </div>
            <div class="step-connector"></div>
            <div class="step-item" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">Lessons & Resources</div>
            </div>
            <div class="step-connector"></div>
            <div class="step-item" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">Quiz Setup</div>
            </div>
            <div class="step-connector"></div>
            <div class="step-item" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">Preview & Publish</div>
            </div>
        </div>
    </div>

    <div class="breadcrumb-with-buttons mb-24 flex-between flex-wrap gap-8">
        <!-- Breadcrumb Start -->
        <div class="breadcrumb mb-24">
            <ul class="flex-align gap-4">
                <li><a href="<?= base_url() ?>" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
                <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
                <li><span class="text-main-600 fw-normal text-15">Create Course</span></li>
            </ul>
        </div>
        <!-- Breadcrumb End -->

        <!-- Buttons Start -->
        <div class="flex-align justify-content-end gap-8">
            <button type="button" id="saveDraftBtn" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-9">
                <i class="ph ph-floppy-disk me-2"></i>Save as Draft
            </button>
            <button type="button" id="previewBtn" class="btn btn-outline-main rounded-pill py-9" disabled>
                <i class="ph ph-eye me-2"></i>Preview
            </button>
            <button type="button" id="publishBtn" class="btn btn-main rounded-pill py-9" disabled>
                <i class="ph ph-check me-2"></i>Publish Course
            </button>
        </div>
        <!-- Buttons End -->
    </div>
    <input type="hidden" name="course_id" id="course_id" value="0" />
    <input type="hidden" name="lesson_id" id="lesson_id" value="0" />
    <input type="hidden" name="description" id="description" value="0" />

    <!-- Add Video.js CSS -->
    <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />

    <!-- Course Tab Start -->
    <div id="step-1-content" class="card step-content">
        <div class="card-header border-bottom border-gray-100 flex-align gap-8">
            <h5 class="mb-0">Course Details</h5>
            <button type="button" class="text-main-600 text-md d-flex" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Enter the basic information about your course">
                <i class="ph-fill ph-question"></i>
            </button>
        </div>
        <div class="card-body">
            <form id="courseForm" action="#" method="post" enctype="multipart/form-data">
                <div class="row gy-20">
                    <div class="col-xxl-3 col-md-4 col-sm-5">
                        <div class="mb-20">
                            <label class="h5 fw-semibold font-heading mb-0">Thumbnail Image <span class="text-13 text-gray-400 fw-medium">(Required)</span></label>
                            <p class="text-13 text-gray-400 mt-2">Recommended size: 1280x720px</p>
                        </div>
                        <div id="fileUpload" class="fileUpload image-upload">
                            <div class="upload-preview"></div>
                            <div class="upload-progress d-none">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-9 col-md-8 col-sm-7">
                        <div class="row g-20">
                            <div class="col-sm-12">
                                <label for="courseTitle" class="h5 mb-8 fw-semibold font-heading">Course Title <span class="text-13 text-gray-400 fw-medium">(Required)</span></label>
                                <div class="position-relative">
                                    <input name="course_title" type="text" class="form-control py-11 pe-76" maxlength="100" id="courseTitle" placeholder="Enter a descriptive title for your course">
                                    <div class="text-gray-400 position-absolute inset-inline-end-0 top-50 translate-middle-y me-16">
                                        <span id="current">0</span>
                                        <span id="maximum">/ 100</span>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-sm-6">
                                <label for="courseCategory" class="h5 mb-8 fw-semibold font-heading">Course Category <span class="text-13 text-gray-400 fw-medium">(Required)</span></label>
                                <div class="position-relative">
                                    <select name="category_id" id="courseCategory" class="form-select py-9">
                                        <option value="" disabled selected>Select course category</option>
                                        <?php foreach ($categories as $category) { ?>
                                            <option value="<?= $category->category_id ?>"><?= $category->category_name ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="courseLevel" class="h5 mb-8 fw-semibold font-heading">Course Level <span class="text-13 text-gray-400 fw-medium">(Required)</span></label>
                                <div class="position-relative">
                                    <select name="course_level" id="courseLevel" class="form-select py-9">
                                        <option value="" disabled selected>Select course level</option>
                                        <option value="Beginner">Beginner</option>
                                        <option value="Intermediate">Intermediate</option>
                                        <option value="Advanced">Advanced</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="courseTime" class="h5 mb-8 fw-semibold font-heading">Course Duration <span class="text-13 text-gray-400 fw-medium">(Required)</span></label>
                                <div class="position-relative">
                                    <select name="timeline" id="courseTime" class="form-select py-9">
                                        <option value="" disabled selected>Select course duration</option>
                                        <option value="5 Hours">5 Hours</option>
                                        <option value="10 Hours">10 Hours</option>
                                        <option value="15 Hours">15 Hours</option>
                                        <option value="20 Hours">20 Hours</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="coursePrice" class="h5 mb-8 fw-semibold font-heading">Course Price <span class="text-13 text-gray-400 fw-medium">(Required)</span></label>
                                <div class="position-relative">
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="price" id="coursePrice" class="form-control py-9" min="0" step="0.01" placeholder="Enter course price">
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="courseDescription" class="h5 mb-8 fw-semibold font-heading">Course Description <span class="text-13 text-gray-400 fw-medium">(Required)</span></label>
                                <div class="position-relative">
                                    <textarea name="description" id="courseDescription" class="form-control py-11" rows="4" placeholder="Provide a detailed description of your course"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="flex-align justify-content-end gap-8">
                            <button type="button" class="btn btn-outline-main rounded-pill py-9" onclick="window.location.href='<?= base_url('admin-courses') ?>'">Cancel</button>
                            <button type="submit" class="btn btn-main rounded-pill py-9">Continue to Lessons</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Lessons Form -->
    <div id="step-2-content" class="card step-content" style="display:none;">
        <div class="card-header border-bottom border-gray-100 flex-align gap-8">
            <h5 class="mb-0">Add Lessons & Resources</h5>
            <button type="button" class="text-main-600 text-md d-flex" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add lessons and upload resources (videos, PDFs) for each lesson">
                <i class="ph-fill ph-question"></i>
            </button>
        </div>
        <div class="card-body">
            <form id="lessonsForm" action="#" method="post" enctype="multipart/form-data">
                <div id="lessons-container"></div>
                <button type="button" class="btn btn-outline-main rounded-pill py-9 my-16" id="addLessonBtn">
                    <i class="ph ph-plus me-2"></i>Add Lesson
                </button>
                <div class="flex-align justify-content-end gap-8">
                    <button type="button" class="btn btn-outline-main rounded-pill py-9" id="backToCourseDetails">Back</button>
                    <button type="submit" class="btn btn-main rounded-pill py-9">Continue to Quiz Setup</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quiz Setup Form -->
    <div id="step-3-content" class="card step-content" style="display:none;">
        <div class="card-header border-bottom border-gray-100 flex-align gap-8">
            <h5 class="mb-0">Quiz Setup</h5>
            <button type="button" class="text-main-600 text-md d-flex" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Create quizzes to test student knowledge">
                <i class="ph-fill ph-question"></i>
            </button>
        </div>
        <div class="card-body">
            <form id="quizForm" action="#" method="post">
                <div id="quiz-container"></div>
                <button type="button" class="btn btn-outline-main rounded-pill py-9 my-16" id="addQuizBtn">
                    <i class="ph ph-plus me-2"></i>Add Quiz
                </button>
                <div class="flex-align justify-content-end gap-8">
                    <button type="button" class="btn btn-outline-main rounded-pill py-9" id="backToLessons">Back</button>
                    <button type="submit" class="btn btn-main rounded-pill py-9">Continue to Preview</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview & Publish -->
    <div id="step-4-content" class="card step-content" style="display:none;">
        <div class="card-header border-bottom border-gray-100 flex-align gap-8">
            <h5 class="mb-0">Preview & Publish</h5>
            <button type="button" class="text-main-600 text-md d-flex" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Review your course before publishing">
                <i class="ph-fill ph-question"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="course-preview">
                <div class="row">
                    <div class="col-md-4">
                        <div class="preview-thumbnail mb-4">
                            <img src="" alt="Course Thumbnail" class="img-fluid rounded">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h3 id="preview-title"></h3>
                        <p id="preview-description" class="text-gray-600"></p>
                        <div class="preview-details">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p><strong>Category:</strong> <span id="preview-category"></span></p>
                                    <p><strong>Level:</strong> <span id="preview-level"></span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>Duration:</strong> <span id="preview-duration"></span></p>
                                    <p><strong>Price:</strong> <span id="preview-price"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="preview-lessons mt-4">
                    <h4>Lessons</h4>
                    <div id="preview-lessons-list"></div>
                </div>
                <div class="preview-quizzes mt-4">
                    <h4>Quizzes</h4>
                    <div id="preview-quizzes-list"></div>
                </div>
            </div>
            <div class="flex-align justify-content-end gap-8 mt-4">
                <button type="button" class="btn btn-outline-main rounded-pill py-9" id="backToQuiz">Back</button>
                <button type="button" class="btn btn-main rounded-pill py-9" id="publishCourseBtn">Publish Course</button>
            </div>
        </div>
    </div>
</div>

<style>
.progress-indicator {
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.step-item {
    text-align: center;
    position: relative;
    flex: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.step-item.active .step-number {
    background: var(--main-color);
    color: #fff;
}

.step-label {
    font-size: 14px;
    color: #6c757d;
    transition: all 0.3s ease;
}

.step-item.active .step-label {
    color: var(--main-color);
    font-weight: 600;
}

.step-connector {
    flex: 1;
    height: 2px;
    background: #e9ecef;
    margin: 20px 0;
    position: relative;
}

.step-connector.active {
    background: var(--main-color);
}

.upload-preview {
    min-height: 200px;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-preview:hover {
    border-color: var(--main-color);
}

.upload-preview img {
    max-width: 100%;
    max-height: 200px;
    object-fit: cover;
}

.lesson-item {
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

.lesson-item .card-header {
    background: #f8f9fa;
}

.quiz-item {
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

.quiz-item .card-header {
    background: #f8f9fa;
}

.preview-thumbnail img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.preview-details {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
}

.preview-lessons, .preview-quizzes {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}
</style>

<script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>