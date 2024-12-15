<script src="https://js.stripe.com/v3/"></script>
<div class="dashboard-body">
    <div class="breadcrumb mb-24">
        <ul class="flex-align gap-4">
            <li><a href="index.html" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
            <li><span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span></li>
            <li><span class="text-main-600 fw-normal text-15"><?= $page_name ?></span></li>
        </ul>
    </div>

    <div class="card mt-24">
        <div class="card-header border-bottom">
            <h4 class="mb-4">Pricing Breakdown</h4>
            <p class="text-gray-600 text-15">Creating a detailed pricing plan for your course requires considering various factors.</p>
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
                            <button data-plan-title="<?= $plan->plan_title ?>" data-plan-id="<?= $plan->plan_id ?>" data-price="<?= $plan->price ?>" class="checkout-button btn btn-outline-main w-100 rounded-pill py-16 border-main-300 text-17 fw-medium mt-32">Get Started</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    var stripe = Stripe('<?= $this->config->item('stripe_key') ?>');

    // Attach event listener to each checkout button
    document.querySelectorAll('.checkout-button').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            var planId = this.getAttribute('data-plan-id');
            var price = this.getAttribute('data-price');
            var title = this.getAttribute('data-plan-title');
            var selected_course_id = '<?= $course_id ?>';



            fetch('<?= base_url('payment/createCheckoutSession'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'plan_id=' + planId + '&price=' + price + '&title=' + title + '&selected_course_id=' + selected_course_id
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(session) {
                    if (session.error) {
                        alert(session.error);
                    } else {
                        return stripe.redirectToCheckout({
                            sessionId: session.id
                        });
                    }
                });
        });
    });
</script>