<div class="dashboard-body">
    <!-- Breadcrumb Start -->
    <div class="breadcrumb mb-24">
        <ul class="flex-align gap-4">
            <li><a href="index.html" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
            <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
            <li><span class="text-main-600 fw-normal text-15"><?= $page_name ?></span></li>
        </ul>
    </div>
    <!-- Breadcrumb End -->

    <div class="tab-content" id="pills-tabContent">
        <!-- My Details Tab start -->
        <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab" tabindex="0">
            <div class="card mt-24">
                <div class="card-header border-bottom">
                    <h4 class="mb-4">Add Details</h4>
                    <p class="text-gray-600 text-15">Please fill details about category</p>
                </div>
                <div class="card-body">
                    <form action="#">
                        <div class="row gy-4">
                            <div class="col-sm-6 col-xs-6">
                                <label for="cname" class="form-label mb-8 h6">Category Name</label>
                                <input name="category_name" type="text" class="form-control py-11" id="cname" placeholder="Enter Category Name">
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <label for="description" class="form-label mb-8 h6">Description</label>
                                <!-- <input name="description" type="text" class="form-control py-11" id="description" placeholder="Enter Last Name"> -->
                                <textarea name="description" class="form-control py-11" id="description" placeholder="Enter Description"></textarea>
                            </div>


                            <div class="col-12">
                                <div class="flex-align justify-content-end gap-8">
                                    <button type="reset" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-9">Cancel</button>
                                    <button onclick="createCategory()" type="button" class="btn btn-main rounded-pill py-9">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function createCategory() {
        var category_name = document.getElementById("cname").value;
        var description = document.getElementById("description").value;

        $.ajax({
            type: "POST",
            url: "<?= base_url('CategoriesController/storeCategory'); ?>",
            data: {
                description: description,
                category_name: category_name,
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Category created successfully
                    alert(response.message);
                    location.reload();
                } else {
                    // Error creating category
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                // AJAX request failed
                alert('Error creating category: ' + error);
            }
        });
    }
</script>