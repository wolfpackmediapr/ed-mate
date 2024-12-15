<!-- Course Tab Start -->
<div id="step-22-content" class="card step-content" style="display:none;">
    <div class="card-header border-bottom border-gray-100 flex-align gap-8">
        <h5 class="mb-0">Upload Videos</h5>
        <button type="button" class="text-main-600 text-md d-flex" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Upload Videos">
            <i class="ph-fill ph-question"></i>
        </button>
    </div>
    <div class="card-body">

        <!-- Drop Course Videos Start -->
        <div class="upload-card-item p-16 rounded-12 bg-main-50 mb-20">
            <div class="flex-align gap-10 flex-wrap">
                <span class="w-36 h-36 text-lg rounded-circle bg-white flex-center text-main-600 flex-shrink-0">
                    <i class="ph-fill ph-video-camera"></i>
                </span>

                <div class="">
                    <p class="text-15 text-gray-500">
                        Drag & drop your single/multiple videos of course, or
                        <label for="video" class="text-main-600 cursor-pointer">Browse</label>
                        <input type="file" id="video" accept="video/mp4,video/x-m4v,video/*" multiple hidden>
                    </p>
                    <p class="text-13 text-gray-600">Mp4 format with 16:9 aspect ratio (max file size 100mb each)</p>
                    <span class="show-uploaded-video-name d-none" id="uploaded-video-name"></span>

                    <!-- Display progress bar and status -->
                    <div id="progress-container" style="display:none;">
                        <progress id="progress-bar" value="0" max="100" style="width:100%"></progress>
                        <span id="progress-status"></span>
                    </div>

                    <!-- Uploaded videos will be displayed here -->
                    <!-- <div id="uploaded-video-list"></div> -->
                </div>

            </div>
        </div>
        <!-- Drop Course Videos End -->

        <!-- Upload Card item Start -->
        <div id="uploaded-video-list" class="upload-card-item p-16 rounded-12 bg-main-50 mb-20">
        </div>

        <!-- Upload Card item End -->


        <div class="flex-align justify-content-end gap-8 mt-20">
            <button onclick="nextStep(1)" href="javascript:void(0)" class="btn btn-outline-main rounded-pill py-9">Back</button>
            <button onclick="nextStep(3)" href="javascript:void(0)" class="btn btn-main rounded-pill py-9">Continue</button>
        </div>
    </div>
</div>
<!-- Course Tab End -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function() {
        $('#video').on('change', function(e) {
            console.log('File input changed');
            var files = e.target.files;
            if (files.length > 0) {
                uploadVideos(files);
            }
        });
    });

    function uploadVideos(files) {
        var formData = new FormData();

        // Append files to formData
        for (var i = 0; i < files.length; i++) {
            formData.append('res_files[]', files[i]);
        }

        formData.append('lesson_id', $("#lesson_id").val());
        let description = $("#description").val();
        // AJAX request to upload videos
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
                        $('#progress-container').show();
                        $('#progress-bar').val(percentComplete);
                        $('#progress-status').text(Math.round(percentComplete) + '% complete');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                console.log(response); // Log the response to check the data structure
                if (response.resUploadedFiles && response.resUploadedFiles.length > 0 || response.resUploadedFiles.filetype === 'mp4' || response.resUploadedFiles.filetype === 'avi'|| response.resUploadedFiles.filetype === 'mov') {
                    // $('#uploaded-video-list').empty(); // Clear previous list
                    response.resUploadedFiles.forEach(function(file) {
                        var fileHTML = `
                        <div id="item_${file.upload_id}" class="upload-card-item p-16 rounded-12 bg-main-50 mb-20">
                            <div class="flex-between flex-wrap gap-4">
                                <div class="flex-align gap-10">
                                    <video src="${file.path}" alt="Video Thumbnail" class="w-88 h-56 rounded-8" controls></video>
                                    <div class="">
                                        <p class="text-15 text-gray-500">${file.name}</p>
                                        <p class="text-13 text-gray-600">${file.duration}</p>
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
                                                                <button onclick="deleteVideo(${file.upload_id})" type="button" class="delete-item-btn py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 w-100 rounded-8 fw-normal text-xs d-block text-start">
                                                                    <span class="text">Delete</span>
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
                            <p class="mt-20 pt-20 border-top border-main-200">${description}</p>
                        </div>
                    `;
                        $('#uploaded-video-list').append(fileHTML);
                    });
                } else {
                    $('#uploaded-video-list').append('<p>No videos uploaded yet.</p>');
                }
                $('#progress-container').hide(); // Hide progress after completion
            },
            error: function(xhr, status, error) {
                console.error('Error:', error); // Use console.error for better visibility of errors
            }
        });
    }
</script>

<script>
    function deleteVideo(resource_id) {
        if (confirm('Are you sure you want to delete this video?')) {
            // AJAX request to delete the video
            $.ajax({
                url: '<?= base_url('CoursesController/deleteVideo'); ?>', // Set your delete URL here
                type: 'POST',
                data: {
                    resource_id: resource_id
                }, // Send the file ID to the server
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $(`#item_${resource_id}`).remove();
                        // alert('Video deleted successfully!');
                    } else {
                        alert('Failed to delete video.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the video.');
                }
            });
        }
    }
</script>