// Custom JS for Create Course page
$(document).ready(function() {
    // Initialize components
    initializeComponents();
    
    // Step navigation
    let currentStep = 1;
    const totalSteps = 4;
    
    // Course data
    let courseData = {
        id: null,
        lessons: [],
        quizzes: []
    };

    /**
     * Initialize all components
     */
    function initializeComponents() {
        // Initialize Select2 for category
        $('#courseCategory').select2({
            placeholder: "Select a category",
            allowClear: true,
            width: '100%'
        });

        // Initialize Quill editor for description
        const quill = new Quill('#courseDescription', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        });

        // Character counter for course title
        $('#courseTitle').on('input', function() {
            const maxLength = 100;
            const currentLength = $(this).val().length;
            $('#titleCharCount').text(currentLength);
            
            if (currentLength > maxLength) {
                $(this).val($(this).val().substring(0, maxLength));
                $('#titleCharCount').text(maxLength);
            }
        });

        // Initialize file upload
        initializeFileUpload();

        // Initialize form validation
        initializeValidation();

        // Button handlers
        $('#nextStep').on('click', handleNextStep);
        $('#prevStep').on('click', handlePrevStep);
        $('#saveDraft').on('click', saveDraft);
        $('#publishCourse').on('click', publishCourse);
        
        // Dynamic content handlers
        $('#addLesson').on('click', addNewLesson);
        $('#addQuiz').on('click', addNewQuiz);
    }

    /**
     * Initialize file upload functionality
     */
    function initializeFileUpload() {
        const thumbnailUpload = $('#thumbnailUpload');
        const thumbnailInput = $('#thumbnailInput');

        thumbnailUpload.on('click', function(e) {
            if (e.target === this || $(e.target).hasClass('upload-placeholder')) {
                thumbnailInput.trigger('click');
            }
        });

        thumbnailInput.on('change', function() {
            handleThumbnailUpload(this.files[0]);
        });

        thumbnailUpload.on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });

        thumbnailUpload.on('dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });

        thumbnailUpload.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const files = e.originalEvent.dataTransfer.files;
            if (files.length) {
                handleThumbnailUpload(files[0]);
            }
        });
    }

    /**
     * Handle thumbnail file upload
     */
    function handleThumbnailUpload(file) {
        if (!file) return;

        if (!file.type.match('image.*')) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File',
                text: 'Please select an image file.'
            });
            return;
        }

        const formData = new FormData();
        formData.append('thumbnail', file);

        $('.upload-progress').removeClass('d-none');
        $.ajax({
            url: base_url + 'course/save_course_details',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        $('.progress-bar').css('width', percent + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                response = typeof response === 'string' ? JSON.parse(response) : response;
                if (response.success) {
                    $('#thumbnailUpload').html(`<img src="${response.url}" alt="Course Thumbnail">`);
                    courseData.thumbnail_url = response.url;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: response.error || 'Failed to upload thumbnail.'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: 'An error occurred while uploading the file.'
                });
            },
            complete: function() {
                $('.upload-progress').addClass('d-none');
                $('.progress-bar').css('width', '0%');
                thumbnailInput.val('');
            }
        });
    }

    /**
     * Initialize form validation
     */
    function initializeValidation() {
        $('#createCourseForm').validate({
            ignore: [],
            rules: {
                title: {
                    required: true,
                    minlength: 5,
                    maxlength: 100
                },
                category: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "Please enter a course title",
                    minlength: "Title must be at least 5 characters long",
                    maxlength: "Title cannot exceed 100 characters"
                },
                category: {
                    required: "Please select a category"
                }
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            }
        });
    }

    /**
     * Handle next step button click
     */
    function handleNextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                saveCurrentStep().then(function(success) {
                    if (success) {
                        currentStep++;
                        updateStepDisplay();
                    }
                });
            }
        }
    }

    /**
     * Handle previous step button click
     */
    function handlePrevStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
        }
    }

    /**
     * Validate current step
     */
    function validateCurrentStep() {
        switch(currentStep) {
            case 1:
                return $('#createCourseForm').valid() && 
                       quill.getText().trim().length > 0;
            case 2:
                return courseData.lessons.length > 0;
            case 3:
                return true; // Quizzes are optional
            case 4:
                return true; // Preview step
            default:
                return false;
        }
    }

    /**
     * Save current step
     */
    async function saveCurrentStep() {
        switch(currentStep) {
            case 1:
                return await saveCourseDetails();
            case 2:
                return await saveLessons();
            case 3:
                return await saveQuizzes();
            default:
                return true;
        }
    }

    /**
     * Save course details (Step 1)
     */
    async function saveCourseDetails() {
        const formData = new FormData();
        formData.append('title', $('#courseTitle').val());
        formData.append('category', $('#courseCategory').val());
        formData.append('description', quill.root.innerHTML);
        
        if ($('#thumbnailInput')[0].files[0]) {
            formData.append('thumbnail', $('#thumbnailInput')[0].files[0]);
        }

        try {
            const response = await $.ajax({
                url: base_url + 'course/save_course_details',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false
            });

            const result = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (result.success) {
                courseData.id = result.course_id;
                return true;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.error || 'Failed to save course details.'
                });
                return false;
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving course details.'
            });
            return false;
        }
    }

    /**
     * Save lessons (Step 2)
     */
    async function saveLessons() {
        if (!courseData.id) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please save course details first.'
            });
            return false;
        }

        const formData = new FormData();
        formData.append('course_id', courseData.id);
        formData.append('lessons', JSON.stringify(courseData.lessons));

        // Append lesson videos and resources
        courseData.lessons.forEach((lesson, index) => {
            if (lesson.video) {
                formData.append(`video_${lesson.temp_id}`, lesson.video);
            }
            
            if (lesson.resources) {
                lesson.resources.forEach(resource => {
                    if (resource.file) {
                        formData.append(`resource_${resource.temp_id}`, resource.file);
                    }
                });
            }
        });

        try {
            const response = await $.ajax({
                url: base_url + 'course/save_lessons',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false
            });

            const result = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (result.success) {
                courseData.lesson_ids = result.lesson_ids;
                return true;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.error || 'Failed to save lessons.'
                });
                return false;
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving lessons.'
            });
            return false;
        }
    }

    /**
     * Save quizzes (Step 3)
     */
    async function saveQuizzes() {
        if (!courseData.id) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please save course details first.'
            });
            return false;
        }

        if (courseData.quizzes.length === 0) {
            return true; // Quizzes are optional
        }

        const formData = new FormData();
        formData.append('course_id', courseData.id);
        formData.append('quizzes', JSON.stringify(courseData.quizzes));

        try {
            const response = await $.ajax({
                url: base_url + 'course/save_quizzes',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false
            });

            const result = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (result.success) {
                courseData.quiz_ids = result.quiz_ids;
                return true;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.error || 'Failed to save quizzes.'
                });
                return false;
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving quizzes.'
            });
            return false;
        }
    }

    /**
     * Update step display
     */
    function updateStepDisplay() {
        // Hide all steps
        $('.step-content').addClass('d-none');
        
        // Show current step
        $(`.step-content[data-step="${currentStep}"]`).removeClass('d-none');
        
        // Update progress indicator
        $('.step-item').removeClass('active');
        $(`.step-item[data-step="${currentStep}"]`).addClass('active');
        
        // Update connectors
        $('.step-connector').removeClass('active');
        for (let i = 1; i < currentStep; i++) {
            $(`.step-connector:nth-child(${i * 2})`).addClass('active');
        }
        
        // Show/hide navigation buttons
        $('#prevStep').toggle(currentStep > 1);
        $('#nextStep').toggle(currentStep < totalSteps);
        $('#publishCourse').toggleClass('d-none', currentStep !== totalSteps);
        
        // Load preview data if on last step
        if (currentStep === totalSteps) {
            loadPreviewData();
        }
    }

    /**
     * Add new lesson
     */
    function addNewLesson() {
        const lessonCount = courseData.lessons.length + 1;
        const tempId = Date.now();
        
        const lesson = {
            temp_id: tempId,
            title: '',
            content: '',
            video: null,
            resources: [],
            order_index: lessonCount - 1
        };
        
        courseData.lessons.push(lesson);
        
        const lessonHtml = $(lessonTemplate).clone();
        lessonHtml.find('.lesson-number').text(lessonCount);
        lessonHtml.attr('data-lesson-id', tempId);
        
        // Add event handlers
        lessonHtml.find('.remove-lesson').on('click', function() {
            removeLessonById(tempId);
            lessonHtml.remove();
        });
        
        lessonHtml.find('.lesson-title').on('input', function() {
            lesson.title = $(this).val();
        });
        
        lessonHtml.find('.lesson-content').on('input', function() {
            lesson.content = $(this).val();
        });
        
        // Handle video upload
        const videoInput = $('<input type="file" accept="video/*" style="display: none;">');
        lessonHtml.find('.add-video').on('click', function() {
            videoInput.trigger('click');
        });
        
        videoInput.on('change', function() {
            const file = this.files[0];
            if (file) {
                lesson.video = file;
                lessonHtml.find('.video-preview').html(`
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Video selected: ${file.name}
                    </div>
                `);
            }
        });
        
        // Handle resource upload
        lessonHtml.find('.add-resource').on('click', function() {
            addResourceToLesson(lesson, lessonHtml);
        });
        
        $('#lessonsContainer').append(lessonHtml);
    }

    /**
     * Add resource to lesson
     */
    function addResourceToLesson(lesson, lessonHtml) {
        const resourceInput = $('<input type="file" style="display: none;">');
        
        resourceInput.on('change', function() {
            const file = this.files[0];
            if (file) {
                const tempId = Date.now();
                const resource = {
                    temp_id: tempId,
                    title: file.name,
                    file: file
                };
                
                lesson.resources.push(resource);
                
                const resourceHtml = $(`
                    <div class="resource-item mb-2" data-resource-id="${tempId}">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file me-2"></i>
                            <span>${file.name}</span>
                            <button type="button" class="btn btn-sm btn-danger ms-auto remove-resource">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `);
                
                resourceHtml.find('.remove-resource').on('click', function() {
                    lesson.resources = lesson.resources.filter(r => r.temp_id !== tempId);
                    resourceHtml.remove();
                });
                
                lessonHtml.find('.lesson-resources').append(resourceHtml);
            }
        });
        
        resourceInput.trigger('click');
    }

    /**
     * Remove lesson by ID
     */
    function removeLessonById(tempId) {
        courseData.lessons = courseData.lessons.filter(lesson => lesson.temp_id !== tempId);
        
        // Update lesson numbers
        $('.lesson-item').each(function(index) {
            $(this).find('.lesson-number').text(index + 1);
        });
    }

    /**
     * Add new quiz
     */
    function addNewQuiz() {
        const quizCount = courseData.quizzes.length + 1;
        const tempId = Date.now();
        
        const quiz = {
            temp_id: tempId,
            title: '',
            description: '',
            passing_score: 70,
            questions: []
        };
        
        courseData.quizzes.push(quiz);
        
        const quizHtml = $(quizTemplate).clone();
        quizHtml.find('.quiz-number').text(quizCount);
        quizHtml.attr('data-quiz-id', tempId);
        
        // Add event handlers
        quizHtml.find('.remove-quiz').on('click', function() {
            removeQuizById(tempId);
            quizHtml.remove();
        });
        
        quizHtml.find('.quiz-title').on('input', function() {
            quiz.title = $(this).val();
        });
        
        quizHtml.find('.quiz-description').on('input', function() {
            quiz.description = $(this).val();
        });
        
        quizHtml.find('.add-question').on('click', function() {
            addQuestionToQuiz(quiz, quizHtml);
        });
        
        $('#quizzesContainer').append(quizHtml);
    }

    /**
     * Add question to quiz
     */
    function addQuestionToQuiz(quiz, quizHtml) {
        const tempId = Date.now();
        
        const question = {
            temp_id: tempId,
            text: '',
            type: 'multiple_choice',
            options: [],
            correct_answer: ''
        };
        
        quiz.questions.push(question);
        
        const questionHtml = $(`
            <div class="question-item mb-3" data-question-id="${tempId}">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Question Text</label>
                            <input type="text" class="form-control question-text" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Options</label>
                            <div class="options-container"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-option">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>
                        <div class="form-group">
                            <label>Correct Answer</label>
                            <select class="form-select correct-answer" required>
                                <option value="">Select correct answer</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger mt-3 remove-question">
                            <i class="fas fa-trash"></i> Remove Question
                        </button>
                    </div>
                </div>
            </div>
        `);
        
        // Add event handlers
        questionHtml.find('.question-text').on('input', function() {
            question.text = $(this).val();
        });
        
        questionHtml.find('.add-option').on('click', function() {
            addOptionToQuestion(question, questionHtml);
        });
        
        questionHtml.find('.correct-answer').on('change', function() {
            question.correct_answer = $(this).val();
        });
        
        questionHtml.find('.remove-question').on('click', function() {
            quiz.questions = quiz.questions.filter(q => q.temp_id !== tempId);
            questionHtml.remove();
        });
        
        quizHtml.find('.quiz-questions').append(questionHtml);
    }

    /**
     * Add option to question
     */
    function addOptionToQuestion(question, questionHtml) {
        const optionText = prompt('Enter option text:');
        if (optionText) {
            question.options.push(optionText);
            
            const optionHtml = $(`
                <div class="option-item d-flex align-items-center mb-2">
                    <i class="fas fa-circle-dot me-2"></i>
                    <span>${optionText}</span>
                    <button type="button" class="btn btn-sm btn-danger ms-auto remove-option">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);
            
            optionHtml.find('.remove-option').on('click', function() {
                const index = question.options.indexOf(optionText);
                if (index > -1) {
                    question.options.splice(index, 1);
                    optionHtml.remove();
                    updateCorrectAnswerOptions(question, questionHtml);
                }
            });
            
            questionHtml.find('.options-container').append(optionHtml);
            updateCorrectAnswerOptions(question, questionHtml);
        }
    }

    /**
     * Update correct answer options
     */
    function updateCorrectAnswerOptions(question, questionHtml) {
        const select = questionHtml.find('.correct-answer');
        const currentValue = select.val();
        
        select.empty().append('<option value="">Select correct answer</option>');
        
        question.options.forEach((option, index) => {
            select.append(`<option value="${option}">${option}</option>`);
        });
        
        if (question.options.includes(currentValue)) {
            select.val(currentValue);
        }
    }

    /**
     * Remove quiz by ID
     */
    function removeQuizById(tempId) {
        courseData.quizzes = courseData.quizzes.filter(quiz => quiz.temp_id !== tempId);
        
        // Update quiz numbers
        $('.quiz-item').each(function(index) {
            $(this).find('.quiz-number').text(index + 1);
        });
    }

    /**
     * Load preview data
     */
    async function loadPreviewData() {
        if (!courseData.id) return;

        try {
            const response = await $.get(base_url + `course/get_preview_data/${courseData.id}`);
            const result = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (result.success) {
                displayPreviewData(result.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.error || 'Failed to load preview data.'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while loading preview data.'
            });
        }
    }

    /**
     * Display preview data
     */
    function displayPreviewData(data) {
        // Course details
        $('#previewTitle').text(data.course.title);
        $('#previewDescription').html(data.course.description);
        if (data.course.thumbnail_url) {
            $('#previewThumbnail').attr('src', data.course.thumbnail_url);
        }
        
        // Lessons
        const lessonsList = $('#previewLessonsList');
        lessonsList.empty();
        
        if (data.lessons && data.lessons.length > 0) {
            data.lessons.forEach((lesson, index) => {
                lessonsList.append(`
                    <div class="lesson-preview-item mb-3">
                        <h5>Lesson ${index + 1}: ${lesson.title}</h5>
                        <p>${lesson.content}</p>
                        ${lesson.video_url ? `
                            <div class="video-preview mb-2">
                                <i class="fas fa-video"></i> Video Included
                            </div>
                        ` : ''}
                        ${lesson.resources && lesson.resources.length > 0 ? `
                            <div class="resources-preview">
                                <strong>Resources:</strong>
                                <ul>
                                    ${lesson.resources.map(resource => `
                                        <li>${resource.title}</li>
                                    `).join('')}
                                </ul>
                            </div>
                        ` : ''}
                    </div>
                `);
            });
        } else {
            lessonsList.append('<p>No lessons added yet.</p>');
        }
        
        // Quizzes
        const quizzesList = $('#previewQuizzesList');
        quizzesList.empty();
        
        if (data.quizzes && data.quizzes.length > 0) {
            data.quizzes.forEach((quiz, index) => {
                quizzesList.append(`
                    <div class="quiz-preview-item mb-3">
                        <h5>Quiz ${index + 1}: ${quiz.title}</h5>
                        <p>${quiz.description}</p>
                        <p>Passing Score: ${quiz.passing_score}%</p>
                        ${quiz.questions && quiz.questions.length > 0 ? `
                            <div class="questions-preview">
                                <strong>Questions:</strong> ${quiz.questions.length}
                            </div>
                        ` : ''}
                    </div>
                `);
            });
        } else {
            quizzesList.append('<p>No quizzes added yet.</p>');
        }
    }

    /**
     * Save course as draft
     */
    async function saveDraft() {
        if (!courseData.id) {
            await saveCourseDetails();
        }
        
        if (!courseData.id) return;

        try {
            const response = await $.post(base_url + 'course/save_draft', {
                course_id: courseData.id,
                title: $('#courseTitle').val(),
                description: quill.root.innerHTML,
                category: $('#courseCategory').val()
            });

            const result = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Course saved as draft.'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.error || 'Failed to save draft.'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving draft.'
            });
        }
    }

    /**
     * Publish course
     */
    async function publishCourse() {
        if (!courseData.id) return;

        try {
            const response = await $.post(base_url + 'course/publish_course', {
                course_id: courseData.id
            });

            const result = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Course published successfully!',
                    confirmButtonText: 'View Course'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = base_url + `course/view/${courseData.id}`;
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.error || 'Failed to publish course.'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while publishing course.'
            });
        }
    }
}); 