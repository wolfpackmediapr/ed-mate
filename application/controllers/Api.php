<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{


	function __construct()
	{
		parent::__construct();
		// admin_auth();
		$this->load->library('Gpt3');
	}
	
	
	function mailgunApi()
	{
		$KEY = '5caf8ecd6d1a384d449b0e3e166f86fb-b7b36bc2-28582e65'; // API key
		$domain = "mg.allstar-technologies.com"; // Domain

		$RESPONE = array('KEY' => $KEY, 'domain' => $domain); // Construct the response array
		echo json_encode($RESPONE); // Encode the response array into JSON and return it
	}

	function fetchNeededData()
	{
		$data['no_of_pages'] = $this->common_model->select_all('*', 'no_of_pages');
		$data['hours'] = $this->common_model->select_all('*', 'hours');
		$data['type_of_documents'] = $this->common_model->select_all('*', 'type_of_document');
		$data['subjects'] = $this->common_model->select_all('*', 'subject');
		echo json_encode($data);
	}

	function generate_lead()
	{

		if ($_POST['documentType'] == 'Other') {
			$data['document_type'] = $_POST['otherdocumentType'];
		} else {
			$data['document_type'] = $_POST['documentType'];
		}

		if (!empty($_POST['email'])) {
			$data['user_email'] = $_POST['email'];
		}
		if (!empty($_POST['phone'])) {
			$data['user_phone'] = $_POST['phone'];
		} else {
			$data['user_phone'] = '';
		}

		if (!empty($_POST['academicLevel'])) {
			$data['academic_level'] = $_POST['academicLevel'];
		}
		if (!empty($_POST['duedate'])) {
			$data['due_date'] = $_POST['duedate'];
		}
		if (!empty($_POST['duetime'])) {
			$data['lead_due_time'] = $_POST['duetime'];
		}
		if (!empty($_POST['quotedPrice'])) {
			$data['quoted_price'] = $_POST['quotedPrice'];
		}
		if (!empty($_POST['no_of_page'])) {
			$data['no_of_pages'] = $_POST['no_of_page'];
		}
		$check = $this->common_model->select_where_table_rows('user_phone', 'leads', array('user_phone' => $data['user_phone']));
		if ($check > 1) {
			$data['user_type'] = 1;
		}
		if ($_SERVER['HTTP_HOST'] != "localhost") {

			$data['region'] = $_POST['region'];
		}
		$usaTime = $this->getUsaTime();
		$pakTime = $this->getPakTime();

		$data['usa_time'] = $usaTime;
		$data['pak_time'] = $pakTime;

		$data['order_from'] = $_POST['order_from'];
		$data['domain_name'] = $_POST['domain_name'];
		$data['payment_type'] = $_POST['payment_type'];
		date_default_timezone_set("US/Pacific");
		$data['filterDate'] = date('Y-m-d');
		date_default_timezone_set("Asia/Karachi");
		$data['pakFilter'] = date('Y-m-d');
		$lead_id = $_POST['lead_id'];

		$id = '';
		if ($lead_id != 0) {

			$this->db->where('lead_id', $lead_id)->update('leads', $data);
			$id = $lead_id;
		} else {
			$this->db->insert('leads', $data);
			$id = $this->db->insert_id();
		}
		if ($id) {
			echo json_encode(['success' => "success", 'lead_id' => $id]);
		} else {
			echo json_encode(['error' => "error", 'message' => 'Something went wrong.']);
		}
	}

	public function orderProcess()
	{

		if ($_POST['subject'] == 'Other') {
			$data['subject'] = $_POST['otherSubject'];
		} else {
			$data['subject'] = $_POST['subject'];
		}

		if ($_POST['citation_style'] == 'Other') {
			$data['citation_style'] = $_POST['otherCitation'];
		} else {
			$data['citation_style'] = $_POST['citation_style'];
		}

		if ($_POST['documentType'] == 'Other') {
			$data['document_type'] = $_POST['otherdocumentType'];
		} else {
			$data['document_type'] = $_POST['documentType'];
		}


		if (!empty($_POST['name'])) {
			$data['user_name'] = $_POST['name'];
		}



		if (!empty($_POST['description'])) {
			$data['description'] = $_POST['description'];
		}
		if (isset($_POST['no_of_sources'])) {
			$data['name_of_sources'] = $_POST['no_of_sources'];
		}

		if (!empty($_POST['title'])) {
			$data['title'] = $_POST['title'];
		}
		if (!empty($_POST['email'])) {
			$data['user_email'] = $_POST['email'];
		}
		if (!empty($_POST['lead_id'])) {
			$data['lead_id'] = $_POST['lead_id'];
		}

		if (!empty($_POST['quotedPrice'])) {
			$data['quoted_price'] = $_POST['quotedPrice'];
		}

		if ($_POST['payment_type'] == 1) {
			$data['payment_type'] = '1';
		}

		if ($_POST['payment_type'] == 0) {
			$data['payment_type'] = '0';
		}


		if (!empty($_POST['phone'])) {
			$data['user_phone'] = $_POST['phone'];
		}

		if (!empty($_POST['academicLevel'])) {
			$data['academic_level'] = $_POST['academicLevel'];
		}
		if (!empty($_POST['duedate'])) {
			$data['due_date'] = $_POST['duedate'];
		}
		if (!empty($_POST['duetime'])) {
			$data['lead_due_time'] = $_POST['duetime'];
		}

		if (!empty($_POST['no_of_page'])) {
			$data['no_of_pages'] = $_POST['no_of_page'];
		}

		$check = $this->common_model->select_where_table_rows('user_phone', 'orders', array('user_phone' => $data['user_phone']));
		if ($check > 1) {
			$data['user_type'] = 1;
		}
		if ($_SERVER['HTTP_HOST'] != "localhost") {
			$data['region'] = $_POST['region'];
		}

		$usaTime = $this->getUsaTime();
		$pakTime = $this->getPakTime();

		$data['usa_time'] = $usaTime;
		$data['pak_time'] = $pakTime;

		$data['domain_name'] = $_POST['domain_name'];
		$data['order_from'] = $_POST['order_from'];
		$data['createdAt'] = date('Y-m-d H:i:s');
		date_default_timezone_set("US/Pacific");
		$data['filterDate'] = date('Y-m-d');
		date_default_timezone_set("Asia/Karachi");
		$data['pakFilter'] = date('Y-m-d');
		$data['type'] = 1;
		$order_id = $_POST['order_id'];
		$carImages = array();
		if ($order_id == 0) {

			$this->db->insert('orders', $data);
			$id = $this->db->insert_id();
		} else {
			$this->db->where('order_id', $order_id)->update('orders', $data);
			$id = $order_id;
		}

		if (!empty($_POST['files'])) {
			foreach ($_POST['files'] as $key) {
				$carImages[] = array('file_source' => $key, 'order_id' => $id);
			}
			$this->db->insert_batch('order_files', $carImages);
		}

		if ($id) {
			echo json_encode(['success' => "success", 'order_id' => $id]);
		} else {
			echo json_encode(['error' => "error", 'message' => 'Something went wrong.']);
		}
	}

	function getPakTime()
	{
		date_default_timezone_set("Asia/Karachi");
		$dateTime = date('Y-m-d h:i:A');
		return $dateTime;
	}

	function getUsaTime()
	{
		date_default_timezone_set('US/Pacific');

		$time = time();

		if ($time >= strtotime("Second Sunday March 0")  && $time < strtotime("First Sunday November 0")) {

			return date('Y-m-d H:i', $time);
		} else {
			return date('Y-m-d H:i', $time);
		}
		die;
	}

	public function fetchNoOfPages()
	{
		$array = $this->common_model->select_all('*', 'no_of_pages');
		$data = '<option value="">Select No. of Pages</option>';

		foreach ($array as $key) {
			$data .= '<option value="' . $key->id . '">' . $key->name . '</option>';
		}

		echo $data;
	}
	public function fetchDocumentType()
	{
		$array = $this->common_model->select_all('*', 'type_of_document');
		$data = '<option value="">Select Document Type</option>';

		foreach ($array as $key) {
			$data .= '<option value="' . $key->name . '">' . $key->name . '</option>';
		}

		echo $data;
	}
	public function fetchHours()
	{
		$array = $this->common_model->select_all('*', 'hours');
		$data = '<option selected="selected">Select Time</option>';

		foreach ($array as $key) {
			$data .= '<option value="' . $key->name . '">' . $key->name . '</option>';
		}

		echo $data;
	}


// 	public function generateAndFormatEssay()
// 	{
// 		header("Access-Control-Allow-Origin: *");
// 		header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// 		header("Access-Control-Allow-Headers: *");


// 		$this->load->library('form_validation');

// 		$topic = $this->input->post('topic');
// 		$wordCount = $this->input->post('word_count');
// 		$academicLevel = $this->input->post('academic_level');

// 		// Validate inputs as needed

// 		// Generate essay content using GPT-3
// 		$systemMessage = "You are an essay generator, skilled in producing informative content with creative flair.";
// 		$userMessage = "Write an essay on the topic of $topic with a word count of $wordCount words and an academic level of $academicLevel.";
// 		$generatedEssayContent = $this->gpt3->generateChatCompletion($systemMessage, $userMessage);

// 		// Format the generated text into the specified essay structure
// 		$formattedEssay = $this->formatEssay($topic, $generatedEssayContent);

// 		// Display the formatted essay
// 		echo json_encode(array('data' => $formattedEssay));
// 		die;
// 	}
	
// 	private function formatEssay($topic, $content)
// {
//     // Split the content into sections based on double line breaks
//     $sections = explode("\n\n\n", $content);

//     // Create a container for the essay
//     $formattedEssay = "<div class='essay-container'>\n";

//     // Add the title with a prominent heading style
//     $formattedEssay .= "<h1 class='essay-title'>$topic</h1>\n";

//     $isTitleSection = true;

//     // Iterate over each section
//     foreach ($sections as $section) {
//         // Split the section into paragraphs based on double line breaks
//         $paragraphs = explode("\n\n", $section);

//         // Iterate over each paragraph in the section
//         foreach ($paragraphs as $paragraph) {
//             // Trim any leading/trailing whitespace
//             $paragraph = trim($paragraph);

//             // Skip empty paragraphs
//             if (!empty($paragraph)) {
//                 // Check if the paragraph starts with a heading (e.g., "Introduction:")
//                 if (preg_match('/^([A-Za-z\s]+):(?:\s\(.+\))?\s(.+)/', $paragraph, $matches)) {
//                     if ($isTitleSection) {
//                         $formattedEssay .= "<strong>$matches[1]</strong> $matches[2]\n";
//                     } else {
//                         $formattedEssay .= "<h2 class='section-title'>$matches[1]</h2> $matches[2]\n";
//                     }
//                 } else {
//                     $formattedEssay .= "<p class='essay-paragraph'>$paragraph</p>\n";
//                 }
//             }
//         }

//         // After the first section, set $isTitleSection to false
//         $isTitleSection = false;
//     }

//     $formattedEssay .= "</div>";

//     return $formattedEssay;
// }

	public function generateAndFormatEssay() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: *");

    $this->load->library('form_validation');

    $topic = $this->input->post('topic');
    $wordCount = $this->input->post('word_count');
    $academicLevel = $this->input->post('academic_level');

    // Validate inputs as needed

    // Generate essay content using GPT-3
    $systemMessage = "You are an essay generator, skilled in producing informative content with creative flair.";
    $userMessage = "Write an essay on the topic of $topic essay should be of $wordCount words strictly follow and an academic level of $academicLevel.But, write it in a very fastly.";

    // Batch multiple conversations
    $conversations = array(
        array('role' => 'system', 'content' => $systemMessage),
        array('role' => 'user', 'content' => $userMessage),
    );

    // Send batch request
    $generatedEssayContent = $this->gpt3->generateChatCompletionBatch($conversations);

    // Format the generated text into the specified essay structure
    $formattedEssay = $this->formatEssay($topic, $generatedEssayContent);

    // Display the formatted essay
    echo json_encode(array('data' => $formattedEssay));
    die;
}
	private function formatEssay($topic, $content)
{
    // Split the content into sections based on double line breaks
    $sections = explode("\n\n\n", $content);

    // Create a container for the essay
    $formattedEssay = "<div class='essay-container'>\n";

    // Add the title with a prominent heading style
    $formattedEssay .= "<h1 class='essay-title'>$topic</h1>\n";

    // Iterate over each section
    foreach ($sections as $section) {
        // Split the section into paragraphs based on double line breaks
        $paragraphs = explode("\n\n", $section);

        // Iterate over each paragraph in the section
        foreach ($paragraphs as $paragraph) {
            // Trim any leading/trailing whitespace
            $paragraph = trim($paragraph);

            // Skip empty or unwanted paragraphs
            if (!empty($paragraph) && !preg_match('/^(Body Paragraph \d+ \(.+\):)/', $paragraph)) {
                // Check if the paragraph starts with a heading (e.g., "Evolution of Web Design:")
                if (preg_match('/^([A-Za-z\s]+:)/', $paragraph, $matches)) {
                    $formattedEssay .= "<strong>$matches[1]</strong>\n";
                } else {
                    $formattedEssay .= "<p class='essay-paragraph'>$paragraph</p>\n";
                }
            }
        }
    }

    $formattedEssay .= "</div>";

    return $formattedEssay;
}


	private function checkPlagiarism($generatedEssay, $referenceText)
	{
		// Use a basic comparison based on character count
		$threshold = 0.8; // Adjust as needed based on your requirements
		similar_text($generatedEssay, $referenceText, $percentage);

		return $percentage >= ($threshold * 100);
	}
	
	

// 	private function formatEssay($topic, $content)
// 	{
// 		// Insert the generated content into the predefined essay format
// 		$formattedEssay = "<h2>$topic</h2>\n";
// 		$formattedEssay .= "<p>$content</p>"; // Insert the generated essay content

// 		return $formattedEssay;
// 	}
}
