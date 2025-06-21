<div class="dashboard-body">
    <div class="breadcrumb mb-24">
        <ul class="flex-align gap-4">
            <li><a href="index.html" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
            <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
            <li><span class="text-main-600 fw-normal text-15">Resources</span></li>
        </ul>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="mb-16">Lesson Resources</h4>
            <form id="upload-resource-form" enctype="multipart/form-data">
                <input type="hidden" id="lesson_id" name="lesson_id" value="<?= isset($lesson_id) ? htmlspecialchars($lesson_id) : '' ?>">
                <div class="mb-16">
                    <input type="file" id="resource_file" name="resource_file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-main">Upload Resource</button>
            </form>
            <div id="upload-progress" class="my-2 d-none">
                <progress id="progress-bar" value="0" max="100"></progress>
                <span id="progress-status"></span>
            </div>
            <div id="resource-list-container" class="mt-24">
                <h5>Resources</h5>
                <div id="loading-spinner" class="text-center py-4 d-none">
                    <div class="spinner-border text-main-600" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="error-message" class="alert alert-danger d-none" role="alert">
                    Failed to load resources. Please try again.
                </div>
                <ul id="resource-list" class="list-group mt-3"></ul>
            </div>
        </div>
    </div>
</div>
<script>
function getLessonId() {
    return document.getElementById('lesson_id').value;
}

function showLoading() {
    document.getElementById('loading-spinner').classList.remove('d-none');
    document.getElementById('error-message').classList.add('d-none');
}
function hideLoading() {
    document.getElementById('loading-spinner').classList.add('d-none');
}
function showError() {
    document.getElementById('error-message').classList.remove('d-none');
}
function renderResource(resource) {
    return `<li class="list-group-item d-flex justify-content-between align-items-center">
        <span><a href="${resource.resource_url}" target="_blank">${resource.resource_name}</a></span>
        <button class="btn btn-danger btn-sm" onclick="deleteResource(${resource.id})">Delete</button>
    </li>`;
}
function loadResources() {
    showLoading();
    const lessonId = getLessonId();
    fetch(`<?= base_url('api/resources?lesson_id=') ?>${lessonId}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (Array.isArray(data)) {
                document.getElementById('resource-list').innerHTML = data.map(renderResource).join('');
            } else if (data.data && Array.isArray(data.data)) {
                document.getElementById('resource-list').innerHTML = data.data.map(renderResource).join('');
            } else {
                showError();
            }
        })
        .catch(error => {
            hideLoading();
            showError();
        });
}
function deleteResource(resourceId) {
    if (!confirm('Are you sure you want to delete this resource?')) return;
    fetch(`<?= base_url('api/resources/') ?>${resourceId}`, {
        method: 'DELETE',
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadResources();
        } else {
            alert('Failed to delete resource.');
        }
    })
    .catch(() => alert('Failed to delete resource.'));
}
document.getElementById('upload-resource-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const lessonId = getLessonId();
    const fileInput = document.getElementById('resource_file');
    if (!fileInput.files.length) return;
    const formData = new FormData();
    formData.append('lesson_id', lessonId);
    formData.append('resource_file', fileInput.files[0]);
    document.getElementById('upload-progress').classList.remove('d-none');
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '<?= base_url('api/resources') ?>', true);
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const percent = (e.loaded / e.total) * 100;
            document.getElementById('progress-bar').value = percent;
            document.getElementById('progress-status').textContent = Math.round(percent) + '%';
        }
    };
    xhr.onload = function() {
        document.getElementById('upload-progress').classList.add('d-none');
        if (xhr.status === 200) {
            fileInput.value = '';
            loadResources();
        } else {
            alert('Upload failed.');
        }
    };
    xhr.onerror = function() {
        document.getElementById('upload-progress').classList.add('d-none');
        alert('Upload failed.');
    };
    xhr.send(formData);
});
document.addEventListener('DOMContentLoaded', loadResources);
</script> 