<style>
    #step-2-content {
        max-height: 300px;
        overflow-y: auto;
        padding: 15px;
    }
</style>

<div id="step-2-content" class="card step-content" style="display:none;">
    <div class="card-header border-bottom border-gray-100 flex-between flex-wrap gap-8">
        <div class="flex-align gap-8 flex-wrap">
            <h5 class="mb-0">Quiz Questions</h5>
            <button type="button" class="text-main-600 text-md d-flex" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Quiz Questions">
                <i class="ph-fill ph-question"></i>
            </button>
        </div>

        <!-- Button trigger modal for adding question -->
        <button type="button" class="border border-gray-100 rounded-pill py-8 px-20" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
            Add Question
        </button>

        <!-- Add Question Modal -->
        <div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addQuestionModalLabel">Add Question</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="newQuestion" class="form-control" placeholder="Add quiz question">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary py-9" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-main py-9" onclick="addQuestion()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form id="quizForm" method="post">
            <div id="questionsContainer">
                <!-- Questions will be appended here dynamically -->
            </div>
            <!-- Hidden fields to store IDs -->
            <input type="hidden" id="quizId" value="0">
            <div id="questionIdsContainer">
                <!-- Hidden fields for question IDs -->
            </div>
            <div class="flex-align justify-content-end gap-8">
                <button onclick="nextStep(3)" href="javascript:void(0)" class="btn btn-outline-main rounded-pill py-9">Back</button>
                <button type="submit" id="continueBtn" class="btn btn-main rounded-pill py-9" disabled>Continue</button>
            </div>
        </form>
    </div>
</div>

<script>
    let questions = [];
    let quizId = document.getElementById('quizId').value; // Retrieve quizId from hidden field
    let questionIds = {}; // Store question IDs dynamically

    // Add question function
    function addQuestion() {
        const questionText = document.getElementById('newQuestion').value;
        if (questionText.trim() !== "") {
            const questionId = questions.length;
            questions.push({
                questionText: questionText,
                choices: [],
                correctAnswer: null
            });
            renderQuestions();
            document.getElementById('newQuestion').value = '';
            new bootstrap.Modal(document.getElementById('addQuestionModal')).hide();
            validateForm();
        }
    }

    // Render the list of questions and choices
    function renderQuestions() {
        const questionsContainer = document.getElementById('questionsContainer');
        questionsContainer.innerHTML = '';

        questions.forEach((question, qIndex) => {
            const questionBlock = `
                <div class="mb-20">
                    <label for="question${qIndex}" class="h6 mb-8 fw-semibold">Question</label>
                    <input type="text" class="form-control fw-medium text-15" id="question${qIndex}" value="${question.questionText}" readonly>
                    <div class="mt-2 mb-20">
                        <label class="h6 mb-8 fw-semibold">Multiple Choices</label>
                        <div id="choicesContainer${qIndex}" class="row g-20">
                            ${renderChoices(qIndex)}
                        </div>
                        <!-- Button trigger modal for adding choices -->
                        <button type="button" class="text-main-600 mt-16 text-15 fw-medium" data-bs-toggle="modal" data-bs-target="#addChoiceModal${qIndex}">
                            Add Choices
                        </button>

                        <!-- Add Choice Modal -->
                        <div class="modal fade" id="addChoiceModal${qIndex}" tabindex="-1" aria-labelledby="addChoiceModalLabel${qIndex}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addChoiceModalLabel${qIndex}">Add Choice</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="newChoice${qIndex}" class="form-control" placeholder="Add Quiz Choice">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary py-9" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-main py-9" onclick="addChoice(${qIndex})">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-20">
                        <label for="answer${qIndex}" class="h6 mb-8 fw-semibold">Answer</label>
                        <div class="select-has-ico">
                            <select name="correct_answers[${qIndex}]" id="answer${qIndex}" class="form-control form-select rounded-8 bg-gray-50 border border-main-200 py-19" onchange="setCorrectAnswer(${qIndex}, this.value)">
                                <option value="" selected disabled>Select correct answer</option>
                                ${renderDropdownOptions(question, qIndex)}
                            </select>
                        </div>
                    </div>
                </div>
            `;
            questionsContainer.insertAdjacentHTML('beforeend', questionBlock);

            // Update hidden field with question ID
            const hiddenQuestionId = `<input type="hidden" id="questionId${qIndex}" value="${questionIds && questionIds[qIndex] ? questionIds[qIndex] : 0}">`;
            document.getElementById('questionIdsContainer').insertAdjacentHTML('beforeend', hiddenQuestionId);
        });
    }

    // Render choices for each question
    function renderChoices(questionIndex) {
        let choicesHtml = '';
        questions[questionIndex].choices.forEach((choice, cIndex) => {
            choicesHtml += `
                <div class="col-sm-6">
                    <div class="delete-item py-15 px-16 rounded-8 bg-gray-50 border border-main-200 flex-align gap-8">
                        <span class="w-24 h-24 bg-white rounded-circle flex-center text-capitalize text-14">${String.fromCharCode(65 + cIndex)}</span>
                        <span class="text-gray-500">${choice}</span>
                        <button type="button" class="delete-btn text-danger-600 text-xl ms-auto d-flex" onclick="removeChoice(${questionIndex}, ${cIndex})"><i class="ph-fill ph-x-circle"></i></button>
                    </div>
                </div>
            `;
        });
        return choicesHtml;
    }

    // Render dropdown options based on question's choices
    function renderDropdownOptions(question, questionIndex) {
        let optionsHtml = '';
        question.choices.forEach((choice, index) => {
            optionsHtml += `<option value="${index}" ${index === question.correctAnswer ? 'selected' : ''}>${choice}</option>`;
        });
        return optionsHtml;
    }

    // Add a choice to a specific question
    function addChoice(questionIndex) {
        const choiceText = document.getElementById(`newChoice${questionIndex}`).value;
        if (choiceText.trim() !== "") {
            questions[questionIndex].choices.push(choiceText);
            renderQuestions();
            document.getElementById(`newChoice${questionIndex}`).value = '';
            new bootstrap.Modal(document.getElementById(`addChoiceModal${questionIndex}`)).hide();
            validateForm();
        }
    }

    // Remove a choice from a specific question
    function removeChoice(questionIndex, choiceIndex) {
        questions[questionIndex].choices.splice(choiceIndex, 1);
        renderQuestions();
        validateForm();
    }

    // Set correct answer for a specific question
    function setCorrectAnswer(questionIndex, choiceIndex) {
        questions[questionIndex].correctAnswer = parseInt(choiceIndex); // Ensure index is an integer
        validateForm();
    }

    // Validate form: Check if all questions have choices and a correct answer
    function validateForm() {
        const continueBtn = document.getElementById('continueBtn');
        const isFormValid = questions.every(question => question.choices.length > 0 && question.correctAnswer !== null);
        continueBtn.disabled = !isFormValid;
    }

    // Handle form submission
    document.getElementById('quizForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);
        formData.append('quiz_title', 'New Quiz');
        formData.append('course_id', $("#course_id").val());
        formData.append('lesson_id', $("#lesson_id").val());

        formData.append('questions', JSON.stringify(questions)); // Append questions array as JSON

        fetch('<?php echo site_url('coursescontroller/save_quiz'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update hidden fields with returned IDs
                    document.getElementById('quizId').value = data.quiz_id;
                    questionIds = data.question_ids || {}; // Ensure questionIds is an object

                    // Update hidden fields for question IDs
                    document.querySelectorAll('#questionIdsContainer input').forEach((input, index) => {
                        input.value = questionIds[index] || 0;
                    });

                    // Handle success (e.g., redirect to another page or show a success message)
                    console.log('Quiz saved successfully');
                    console.log('Quiz ID:', data.quiz_id);
                    console.log('Question IDs:', data.question_ids);
                    console.log('Response Data:', data);
                    nextStep(3);
                    setCourseContent(data.resources, data.course);

                    // alert($("#course_id").val());
                } else {
                    // Handle error
                    console.error('Failed to save quiz:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    function setCourseContent(resources, course) {
        // course 
        // Display course title, description, and image
        setCourseTitle(course)
        // end course 
        if (!resources || !Array.isArray(resources)) {
            console.warn('No resources found or resources is not an array.');
            return; // Exit the function if resources is undefined or not an array
        }

        var uploadContainer = $("#files-append"); // Replace with your container ID
        uploadContainer.empty(); // Clear previous entries if necessary

        resources.forEach(function(resource) {
            var fileSize = (resource.file_size / 1024).toFixed(2) + " MB"; // Assuming the size is in KB

            // Build the HTML structure for each resource
            var fileHTML = `
            <div class="flex-between gap-8">
                <div class="flex-align gap-10">
                    <span class="w-36 h-36 text-lg rounded-circle bg-white flex-center text-main-600 bg-main-50">
                        <i class="ph ph-paperclip"></i>
                    </span>
                    <div>
                        <p class="text-15 text-gray-500">${resource.path}</p>
                        <p class="text-13 text-gray-600">${fileSize}</p>
                    </div>
                </div>
                <div class="flex-align gap-8">
                    <span class="text-main-600 d-flex text-xl"><i class="ph-fill ph-check-circle"></i></span>
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
                                            </li>
                                            <li class="mb-0">
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

            // Append the generated HTML for each video
            uploadContainer.append(fileHTML);
        });
    }

    function setCourseTitle(course) {
        if (!course || typeof course !== 'object') {
            console.warn('Invalid course data');
            return;
        }
        const courseContainer = $("#course-info-container"); // Assume you have a container for course info
        courseContainer.empty(); // Clear previous content if necessary

        const courseHTML = `
        <div class="flex-between">
            <span class="py-6 px-16 bg-gray-50 text-gray-500 rounded-pill text-15">${course.course_title || 'No Title Available'}</span>
            <div class="flex-align gap-8">
                <span class="text-main-600 d-flex text-xl"><i class="ph-fill ph-check-circle"></i></span>
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
                                            <button type="button" class="edit-item-btn py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 w-100 rounded-8 fw-normal text-xs d-block text-start">
                                                <span class="text">Edit</span>
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
        <h3 class="my-8">${course.course_title || 'No Title Available'}</h3>
        <div class="flex-align gap-24">
            <div class="flex-align gap-8">
                <span class="text-gray-200 d-flex text-16"><i class="ph-fill ph-clock"></i></span>
                <span class="text-gray-200 d-flex text-15">${course.timeline || 'N/A'}</span>
            </div>
            <div class="flex-align gap-8">
                <span class="text-gray-200 d-flex text-16"><i class="ph-fill ph-clock"></i></span>
                <span class="text-gray-200 d-flex text-15">${course.course_level || 'N/A'}</span>
            </div>
        </div>
        <div class="my-24">
            <img src="<?= base_url() ?>uploads/courses/${course.thumbnail_image || 'default-image.png'}" alt="${course.course_title || 'Course Image'}" class="rounded-16 cover-img">
        </div>
        <h5 class="mb-16 fw-bold">Course Description</h5>
        <p class="text-gray-300 text-15 max-w-845">${course.description || 'No description available.'}</p>
    `;

        // Append course HTML to the container
        courseContainer.append(courseHTML);

    }
</script>