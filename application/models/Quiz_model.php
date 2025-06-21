<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('supabase');
    }

    /**
     * Create a new quiz
     */
    public function create_quiz($data) {
        try {
            // Ensure required fields
            if (!isset($data['course_id']) || !isset($data['title'])) {
                throw new Exception('Missing required fields');
            }

            // Prepare quiz data
            $quiz_data = [
                'course_id' => $data['course_id'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'passing_score' => $data['passing_score'] ?? 70,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert into Supabase
            $result = supabase_insert('quizzes', $quiz_data);
            
            if ($result['status'] === 201 && !empty($result['data'][0]['id'])) {
                return [
                    'success' => true,
                    'quiz_id' => $result['data'][0]['id']
                ];
            } else {
                throw new Exception('Failed to create quiz: ' . json_encode($result['data']));
            }
        } catch (Exception $e) {
            log_message('error', 'Quiz creation error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Update an existing quiz
     */
    public function update_quiz($quiz_id, $data) {
        try {
            // Remove any fields that shouldn't be updated
            unset($data['id'], $data['created_at'], $data['course_id']);
            $data['updated_at'] = date('Y-m-d H:i:s');

            $result = supabase_update('quizzes', $quiz_id, $data);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Quiz update error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get quiz by ID
     */
    public function get_quiz($quiz_id) {
        try {
            $result = supabase_query('quizzes', ['id' => 'eq.' . $quiz_id]);
            
            if ($result['status'] === 200 && !empty($result['data'])) {
                // Get questions for this quiz
                $questions = $this->get_quiz_questions($quiz_id);
                
                return [
                    'success' => true,
                    'data' => array_merge(
                        $result['data'][0],
                        ['questions' => $questions['success'] ? $questions['data'] : []]
                    )
                ];
            } else {
                throw new Exception('Quiz not found');
            }
        } catch (Exception $e) {
            log_message('error', 'Quiz fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get all quizzes for a course
     */
    public function get_course_quizzes($course_id) {
        try {
            $result = supabase_query('quizzes', ['course_id' => 'eq.' . $course_id]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Course quizzes fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete a quiz
     */
    public function delete_quiz($quiz_id) {
        try {
            $result = supabase_delete('quizzes', $quiz_id);

            return [
                'success' => $result['status'] === 204,
                'message' => 'Quiz deleted successfully'
            ];
        } catch (Exception $e) {
            log_message('error', 'Quiz deletion error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Add question to quiz
     */
    public function add_question($quiz_id, $question_data) {
        try {
            // Ensure required fields
            if (!isset($question_data['question_text']) || !isset($question_data['correct_answer'])) {
                throw new Exception('Missing required question fields');
            }

            $question = [
                'quiz_id' => $quiz_id,
                'question_text' => $question_data['question_text'],
                'question_type' => $question_data['question_type'] ?? 'multiple_choice',
                'options' => isset($question_data['options']) ? json_encode($question_data['options']) : null,
                'correct_answer' => $question_data['correct_answer'],
                'points' => $question_data['points'] ?? 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $result = supabase_insert('questions', $question);

            return [
                'success' => $result['status'] === 201,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Question addition error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get quiz questions
     */
    public function get_quiz_questions($quiz_id) {
        try {
            $result = supabase_query('questions', ['quiz_id' => 'eq.' . $quiz_id]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Quiz questions fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Submit quiz attempt
     */
    public function submit_attempt($quiz_id, $user_id, $answers) {
        try {
            // Get quiz details and questions
            $quiz = $this->get_quiz($quiz_id);
            if (!$quiz['success']) {
                throw new Exception('Quiz not found');
            }

            // Calculate score
            $total_points = 0;
            $earned_points = 0;
            foreach ($quiz['data']['questions'] as $question) {
                $total_points += $question['points'];
                if (isset($answers[$question['id']]) && $answers[$question['id']] === $question['correct_answer']) {
                    $earned_points += $question['points'];
                }
            }

            $score = ($total_points > 0) ? round(($earned_points / $total_points) * 100) : 0;
            $passed = $score >= $quiz['data']['passing_score'];

            // Save attempt
            $attempt_data = [
                'user_id' => $user_id,
                'quiz_id' => $quiz_id,
                'score' => $score,
                'passed' => $passed,
                'answers' => json_encode($answers),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $result = supabase_insert('quiz_attempts', $attempt_data);

            return [
                'success' => $result['status'] === 201,
                'data' => array_merge($result['data'], [
                    'score' => $score,
                    'passed' => $passed
                ])
            ];
        } catch (Exception $e) {
            log_message('error', 'Quiz attempt submission error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get user's quiz attempts
     */
    public function get_user_attempts($user_id, $quiz_id = null) {
        try {
            $query = ['user_id' => 'eq.' . $user_id];
            if ($quiz_id) {
                $query['quiz_id'] = 'eq.' . $quiz_id;
            }

            $result = supabase_query('quiz_attempts', $query);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Quiz attempts fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
} 