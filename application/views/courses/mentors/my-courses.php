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