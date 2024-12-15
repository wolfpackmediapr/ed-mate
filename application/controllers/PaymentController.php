<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'libraries/stripe-php/init.php';
class PaymentController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Ensure the user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
        middlewareStudents();
    }
    public function createCheckoutSession()
    {
        $planId = $this->input->post('plan_id');
        $price = $this->input->post('price'); // Price in dollars, e.g., 200
        $title = $this->input->post('title');
        $selected_course_id = $this->input->post('selected_course_id');


        try {
            // Create a checkout session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Course Plan',
                            'description' => 'Plan Name: ' . $title,
                        ],
                        'unit_amount' => $price * 100, // Convert dollars to cents
                    ],
                    'quantity' => 1,
                ]],
                'client_reference_id' => $planId, // Pass the plan ID to reference in the webhook
                'mode' => 'payment',
                'success_url' => base_url('payment/success'),
                'cancel_url' => base_url('payment/cancel'),
            ]);

            // Save the plan_id in the session
            $this->session->set_userdata('plan_id', $planId);
            $this->session->set_userdata('selected_course_id', $selected_course_id);


            // Return session ID as JSON
            echo json_encode(['id' => $session->id]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }




    public function success()
    {
        // Retrieve the plan_id from the session
        $planId = $this->session->userdata('plan_id');
        $user_id = $this->session->userdata('user_id');
        $selected_course_id = $this->session->userdata('selected_course_id');



        $post['plan_id'] = $planId;
        $post['is_paid'] = 1;
        $post['user_id'] = $user_id;
        $post['course_id'] = $selected_course_id; // Adjust as per your logic

        if ($this->common_model->insert_array('course_pricing', $post)) {
            echo "User plan updated successfully. ";

        } else {
            echo "Failed to update user plan in the database.";
        }
    }


    public function cancel()
    {
        // Handle payment cancellation
        echo "Payment Cancelled!";
    }
}
