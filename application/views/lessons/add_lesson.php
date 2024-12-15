<div class="dashboard-body">
    <!-- Breadcrumb Start -->
    <div class="breadcrumb mb-24">
        <ul class="flex-align gap-4">
            <li><a href="<?= base_url('dashboard') ?>" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
            <li><span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span></li>
            <li><a href="<?= base_url('lessons') ?>" class="text-gray-200 fw-normal text-15 hover-text-main-600">Lessons</a></li>
            <li><span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span></li>
            <li><span class="text-main-600 fw-normal text-15"><?= $page_name ?></span></li>
        </ul>
    </div>
    <!-- Breadcrumb End -->

    <input type="hidden" name="lesson_id" id="lesson_id" value="0" />
    <input type="hidden" name="description" id="description" value="" />

    <div class="tab-content" id="pills-tabContent">
        <!-- My Details Tab start -->
        <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab" tabindex="0">
            <div class="card mt-24">
                <div class="card-header border-bottom">
                    <h4 class="mb-4">Add Details</h4>
                    <p class="text-gray-600 text-15">Please fill details about Lesson</p>
                </div>

                <div class="card-body" id="card-body-1">
                    <form action="#">
                        <div class="row gy-4">
                            <div class="col-sm-6 col-xs-6">
                                <label for="lesson_title" class="form-label mb-8 h6">Lesson Title</label>
                                <input name="lesson_title" type="text" class="form-control py-11" id="lesson_title" placeholder="Enter Lesson Title">
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <label for="content" class="form-label mb-8 h6">Description</label>
                                <textarea name="content" class="form-control py-11" id="content" placeholder="Enter Description"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="flex-align justify-content-end gap-8">
                                    <button onclick="createLesson()" type="button" class="btn btn-main rounded-pill py-9">Save & Next</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Video Upload Section -->
                <div style="display: none;" class="card-body" id="card-body-2">
                    <div class="row gy-4">
                        <div class="col-sm-12 col-xs-12">
                            <!-- Drop Course Videos Start -->
                            <div class="upload-card-item p-16 rounded-12 bg-main-50 mb-20">
                                <div class="flex-align gap-10 flex-wrap">
                                    <span class="w-36 h-36 text-lg rounded-circle bg-white flex-center text-main-600 flex-shrink-0">
                                        <i class="ph-fill ph-video-camera"></i>
                                    </span>

                                    <div>
                                        <p class="text-15 text-gray-500">
                                            Drag & drop your single/multiple videos, or
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
                                    </div>
                                </div>
                            </div>
                            <!-- Drop Course Videos End -->

                            <!-- Uploaded Videos List Start -->
                            <div id="uploaded-video-list" class="upload-card-item p-16 rounded-12 bg-main-50 mb-20"></div>
                            <!-- Uploaded Videos List End -->
                        </div>

                        <div class="col-12">
                            <div class="flex-align justify-content-end gap-8">
                                <button onclick="TabsNew(1)" type="button" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-9">Back</button>
                                <button onclick="publishLesson()" id="save-changes-btn" type="button" class="btn btn-main rounded-pill py-9" disabled>Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Video Upload Section -->
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Toggle between tabs
    function TabsNew(_id) {
        let lesson_title = $("#lesson_title").val();
        let content = $("#content").val();
        if (lesson_title && content) {
            if (_id === 1) {
                $("#card-body-1").show();
                $("#card-body-2").hide();
            } else {
                $("#card-body-1").hide();
                $("#card-body-2").show();
            }
        }
    }

    // Create lesson and move to video upload tab
    function createLesson() {
        let lesson_title = $("#lesson_title").val();
        let content = $("#content").val();
        let lesson_id = $("#lesson_id").val();

        $("#description").val(content);

        $.ajax({
            type: "POST",
            url: "<?= base_url('LessonsController/storeLesson'); ?>",
            data: {
                content: content,
                lesson_title: lesson_title,
                lesson_id: lesson_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $("#lesson_id").val(response.lesson_id);
                    alert(response.message);
                    TabsNew(2);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error creating lesson: ' + error);
            }
        });
    }

    function publishLesson() {
        var lesson_id = document.getElementById("lesson_id").value;
        $.ajax({
            type: "POST",
            url: "<?= base_url('LessonsController/publishLesson'); ?>",
            data: {
                lesson_id: lesson_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // location.reload();
                    window.location.replace('<?=base_url()?>lessons')
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

    // Upload videos on file selection
    $(document).ready(function() {
        $('#video').on('change', function(e) {
            var files = e.target.files;
            if (files.length > 0) {
                uploadVideos(files);
            }
        });
    });

    // Upload video function
    function uploadVideos(files) {
        var formData = new FormData();
        for (var i = 0; i < files.length; i++) {
            formData.append('res_files[]', files[i]);
        }

        formData.append('lesson_id', $("#lesson_id").val());

        $.ajax({
            url: '<?= base_url('CoursesController/uploadResources'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
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
                if (response.resUploadedFiles && response.resUploadedFiles.length > 0) {
                    response.resUploadedFiles.forEach(function(file) {
                        var fileHTML = `
                            <div id="item_${file.upload_id}" class="upload-card-item p-16 rounded-12 bg-main-50 mb-20">
                                <div class="flex-between flex-wrap gap-4">
                                    <div class="flex-align gap-10">
                                        <video src="${file.path}" class="w-88 h-56 rounded-8" controls></video>
                                        <div>
                                            <p class="text-15 text-gray-500">${file.name}</p>
                                            <p class="text-13 text-gray-600">${file.duration}</p>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" id="title_${file.upload_id}" class="form-control mt-10" placeholder="Enter title for ${file.name}" oninput="checkTitles()">
                                <button onclick="saveTitle(${file.upload_id})" class="btn btn-primary mt-10">Save Title</button>
                                <p id="title-status-${file.upload_id}" class="text-success mt-10" style="display:none;">Title saved!</p>
                            </div>
                        `;
                        $('#uploaded-video-list').append(fileHTML);
                    });
                } else {
                    alert('Video upload failed!');
                }
            },
            error: function(xhr, status, error) {
                alert('Error uploading videos: ' + error);
            }
        });
    }

    // Save title for each video
    function saveTitle(uploadId) {
        let title = $(`#title_${uploadId}`).val();
        if (title) {
            $.ajax({
                type: "POST",
                url: '<?= base_url('CoursesController/saveTitle'); ?>',
                data: { title: title, uploadId: uploadId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $(`#title-status-${uploadId}`).show();
                        $(`#saveTitleBtn_${uploadId}`).prop('disabled', true);
                        checkTitles();
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error saving title: ' + error);
                }
            });
        }
    }

    // Enable save changes button only if all titles are filled
    function checkTitles() {
        let allFilled = true;
        $('#uploaded-video-list input[type="text"]').each(function() {
            if ($(this).val() === '') {
                allFilled = false;
            }
        });

        $('#save-changes-btn').prop('disabled', !allFilled);
    }
</script>
