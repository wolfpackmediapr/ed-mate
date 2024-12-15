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

    <div class="card mt-24">
        <div class="card-header border-bottom">
            <h4 class="mb-4">Pricing Breakdown</h4>
            <p class="text-gray-600 text-15">Creating a detailed pricing plan for your course requries considering various factors.</p>
        </div>
        <div class="card-body">
            <div class="row gy-4">

                <?php foreach ($plans as $plan) { ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="plan-item rounded-16 border border-gray-100 transition-2 position-relative">
                            <span class="text-2xl d-flex mb-16 text-main-600"><i class="ph ph-package"></i></span>
                            <h3 class="mb-4"><?= $plan->plan_title ?></h3>
                            <span class="text-gray-600"><?= $plan->short_tagline ?></span>
                            <h2 class="h1 fw-medium text-main mb-32 mt-16 pb-32 border-bottom border-gray-100 d-flex gap-4">
                                $<?= $plan->price ?> <span class="text-md text-gray-600">/year</span>
                            </h2>
                            <ul>
                                <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                                    <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                                    <?= $plan->description ?>
                                </li>

                            </ul>
                            <a href="#" class="btn btn-outline-main w-100 rounded-pill py-16 border-main-300 text-17 fw-medium mt-32">Get Started</a>
                        </div>
                    </div>
                <?php } ?>


                <div class="col-12">
                    <label class="form-label mb-8 h6 mt-32">Terms & Policy</label>
                    <ul class="list-inside">
                        <li class="text-gray-600 mb-4">1. Set up multiple pricing levels with different features and functionalities to maximize revenue</li>
                        <li class="text-gray-600 mb-4">2. Continuously test different price points and discounts to find the sweet spot that resonates with your target audience</li>
                        <li class="text-gray-600 mb-4">3. Price your course based on the perceived value it provides to students, considering factors</li>
                    </ul>
                    <button type="button" class="btn btn-main text-sm btn-sm px-24 rounded-pill py-12 d-flex align-items-center gap-2 mt-24" data-bs-toggle="modal" data-bs-target="#priceModal">
                        <i class="ph ph-plus me-4"></i>
                        Add New Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Modal -->
<div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="exampleModalLabelOne" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="pricingPlanForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabelOne">Add Pricing Plan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="row gy-4">
                            <div class="col-sm-6 col-xs-6">
                                <label for="plan_title" class="form-label mb-8 h6">Plan Title</label>
                                <input name="plan_title" type="text" class="form-control py-11" id="plan_title" placeholder="Plan Title" required>
                            </div>



                            <div class="col-sm-6 col-xs-6">
                                <label for="price" class="form-label mb-8 h6">Price /Year</label>
                                <input name="price" type="number" class="form-control py-11" id="price" placeholder="$20 /Year" required>
                            </div>

                            <div class="col-sm-12 col-xs-12">
                                <label for="short_tagline" class="form-label mb-8 h6">Short Tagline</label>
                                <input name="short_tagline" type="text" class="form-control py-11" id="short_tagline" placeholder="ex. Perfect plan for students" required>
                            </div>
                            <div class="col-12">
                                <div class="editor">
                                    <label class="form-label mb-8 h6">Details</label>
                                    <div id="editor">
                                        <p>Intro video the course
                                            Interactive quizzes
                                            Course curriculum
                                            Community supports
                                            Certificate of completion
                                            Sample lesson showcasing
                                            Access to course community</p>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-main py-9">Save</button>
                    <button type="button" class="btn btn-secondary py-9" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#pricingPlanForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Get form data except for the editor content
            let formData = $(this).serialize();

            // Get the content of the editor (only inside the .ql-editor class)
            let details = $('.ql-editor').clone(); // Clone the content to manipulate
            details.find('.ql-tooltip').remove(); // Remove unwanted tooltip divs

            let cleanHtml = details.html(); // Extract clean HTML content

            // Add the editor content to the form data
            formData += '&details=' + encodeURIComponent(cleanHtml); // Append details to the serialized form data

            // Send data via AJAX
            $.ajax({
                url: "<?= base_url('AdminPlans/addPricingPlan'); ?>", // Replace with your actual URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Ensure response is JSON
                    try {
                        const result = JSON.parse(response);

                        if (result.success) {
                            // Handle success, e.g., display a success message
                            alert('Pricing plan added successfully!');
                            $('#priceModal').modal('hide'); // Close the modal
                            // Optionally, refresh the pricing plans list or update UI here
                        } else {
                            // Handle error
                            alert('Error adding pricing plan: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                        alert('Unexpected response from server.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>