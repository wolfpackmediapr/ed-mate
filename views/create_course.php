<?php
$page_title = "Create Course";
$page_css = "create-course.css";
$page_js = "create-course.js";
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Progress Indicator -->
                    <div class="progress-indicator mb-4">
                        <div class="d-flex align-items-center">
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

                    <!-- Course Creation Form -->
                    <form id="createCourseForm" class="needs-validation" novalidate>
                        <!-- Step 1: Course Details -->
                        <div class="step-content" data-step="1">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="courseTitle">Course Title</label>
                                        <input type="text" class="form-control" id="courseTitle" name="title" required maxlength="100">
                                        <div class="invalid-feedback">Please enter a course title.</div>
                                        <small class="text-muted"><span id="titleCharCount">0</span>/100 characters</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="courseCategory">Category</label>
                                        <select class="form-select" id="courseCategory" name="category" required>
                                            <option value="">Select a category</option>
                                            <option value="programming">Programming</option>
                                            <option value="design">Design</option>
                                            <option value="business">Business</option>
                                            <option value="marketing">Marketing</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a category.</div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="courseDescription">Description</label>
                                        <div id="courseDescription"></div>
                                        <div class="invalid-feedback">Please enter a course description.</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Course Thumbnail</label>
                                        <div class="upload-preview" id="thumbnailUpload">
                                            <div class="upload-placeholder">
                                                <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                                <p>Drag & drop or click to upload</p>
                                                <small class="text-muted">Recommended size: 800x450px</small>
                                            </div>
                                            <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" class="d-none">
                                            <div class="upload-progress d-none">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">Please upload a course thumbnail.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Lessons & Resources -->
                        <div class="step-content d-none" data-step="2">
                            <div id="lessonsContainer">
                                <!-- Lesson items will be added here dynamically -->
                            </div>
                            <button type="button" class="btn btn-outline-primary mt-3" id="addLesson">
                                <i class="fas fa-plus"></i> Add Lesson
                            </button>
                        </div>

                        <!-- Step 3: Quiz Setup -->
                        <div class="step-content d-none" data-step="3">
                            <div id="quizzesContainer">
                                <!-- Quiz items will be added here dynamically -->
                            </div>
                            <button type="button" class="btn btn-outline-primary mt-3" id="addQuiz">
                                <i class="fas fa-plus"></i> Add Quiz
                            </button>
                        </div>

                        <!-- Step 4: Preview & Publish -->
                        <div class="step-content d-none" data-step="4">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="preview-details">
                                        <h3 id="previewTitle"></h3>
                                        <p id="previewDescription"></p>
                                        <div class="preview-lessons">
                                            <h4>Lessons</h4>
                                            <div id="previewLessonsList"></div>
                                        </div>
                                        <div class="preview-quizzes">
                                            <h4>Quizzes</h4>
                                            <div id="previewQuizzesList"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="preview-thumbnail">
                                        <img id="previewThumbnail" src="" alt="Course Thumbnail">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="prevStep" style="display: none;">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" id="saveDraft">
                                    <i class="fas fa-save"></i> Save Draft
                                </button>
                                <button type="button" class="btn btn-primary" id="nextStep">
                                    Next <i class="fas fa-arrow-right"></i>
                                </button>
                                <button type="submit" class="btn btn-success d-none" id="publishCourse">
                                    <i class="fas fa-check"></i> Publish Course
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lesson Template -->
<template id="lessonTemplate">
    <div class="lesson-item">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lesson <span class="lesson-number"></span></h5>
            <button type="button" class="btn btn-sm btn-danger remove-lesson">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="form-group mb-3">
                <label>Lesson Title</label>
                <input type="text" class="form-control lesson-title" required>
            </div>
            <div class="form-group mb-3">
                <label>Lesson Content</label>
                <textarea class="form-control lesson-content" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Resources</label>
                <div class="lesson-resources">
                    <!-- Resource items will be added here -->
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-resource">
                    <i class="fas fa-plus"></i> Add Resource
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Quiz Template -->
<template id="quizTemplate">
    <div class="quiz-item">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Quiz <span class="quiz-number"></span></h5>
            <button type="button" class="btn btn-sm btn-danger remove-quiz">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="form-group mb-3">
                <label>Quiz Title</label>
                <input type="text" class="form-control quiz-title" required>
            </div>
            <div class="form-group mb-3">
                <label>Description</label>
                <textarea class="form-control quiz-description" rows="2" required></textarea>
            </div>
            <div class="form-group">
                <label>Questions</label>
                <div class="quiz-questions">
                    <!-- Question items will be added here -->
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-question">
                    <i class="fas fa-plus"></i> Add Question
                </button>
            </div>
        </div>
    </div>
</template> 