<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'AuthController/login';
$route['logout'] = 'AuthController/logout';
$route['login'] = 'AuthController/login';
$route['register'] = 'AuthController/register';
$route['forgot-password'] = 'AuthController/forgot_password';

// $route['dashboard'] = 'main';

$route['profile'] = 'user/profile';
$route['order-detail/(:any)'] = 'orders/orderDetail/$1';

$route['dashboard'] = 'DashboardController/index';

$route['categories'] = 'CategoriesController';
$route['create-category'] = 'CategoriesController/createCateggory';
$route['create-course'] = 'CoursesController/createCourse';
$route['create-lesson'] = 'LessonsController/createLesson';
$route['lessons'] = 'LessonsController';

// Students Routes 

$route['student-courses'] = 'StudentCoursesController/studentCourses';
$route['student-course-details/(:any)'] = 'StudentCoursesController/courseDetails/$1';
$route['select-plan/(:any)/(:any)'] = 'StudentPlans/index/$1/$1';

$route['payment/createCheckoutSession'] = 'PaymentController/createCheckoutSession';
$route['payment/success'] = 'PaymentController/success';
$route['payment/cancel'] = 'PaymentController/cancel';



// Admin Routes 

$route['pricing-plan'] = 'AdminPlans';

$route['mentor-courses'] = 'AdminCoursesController/mentorCourses';
$route['course-details/(:any)'] = 'AdminCoursesController/courseDetails/$1';

// RESTful API routes for lessons
$route['api/lessons']['GET'] = 'LessonsController/index';
$route['api/lessons']['POST'] = 'LessonsController/storeLesson';
$route['api/lessons/(:num)']['GET'] = 'LessonsController/getLesson/$1';
$route['api/lessons/(:num)']['PUT'] = 'LessonsController/updateLesson/$1';
$route['api/lessons/(:num)']['DELETE'] = 'LessonsController/deleteLesson/$1';

// RESTful API route for categories
$route['api/categories']['GET'] = 'CategoriesController/apiList';

// RESTful API route for courses
$route['api/courses']['GET'] = 'CoursesController/apiList';

// Student Courses API Routes
$route['student-courses/getStudentCourses'] = 'StudentCoursesController/getStudentCourses';

// Mentor/Admin Courses API Route
$route['mentor-courses/getMentorCourses'] = 'AdminCoursesController/getMentorCourses';

// Students Routes
$route['students'] = 'StudentsController/index';
$route['students/create'] = 'StudentsController/create';
$route['api/students/(:num)'] = 'StudentsController/getStudentDetails/$1';
$route['api/students'] = 'StudentsController/getStudents';
$route['students/sync_to_supabase'] = 'StudentsController/sync_to_supabase';

// $route['404_override'] = '';
$route['404_override'] = 'Custom404';

$route['translate_uri_dashes'] = FALSE;

// Super Admin Routes
$route['super-admin'] = 'SuperAdminController/index';
$route['super-admin/dashboard'] = 'SuperAdminController/index';

// Sidebar tab routes
$route['assignment'] = 'PagesController/assignment';
$route['mentors'] = 'PagesController/mentors';
$route['resources'] = 'PagesController/resources';
$route['message'] = 'PagesController/message';
$route['analytics'] = 'PagesController/analytics';
$route['event'] = 'PagesController/event';
$route['library'] = 'PagesController/library';
$route['setting'] = 'PagesController/setting';
// Authentication submenu routes
$route['sign-in'] = 'PagesController/sign_in';
$route['sign-up'] = 'PagesController/sign_up';
$route['forgot-password'] = 'PagesController/forgot_password';
$route['reset-password'] = 'PagesController/reset_password';
$route['verify-email'] = 'PagesController/verify_email';
$route['two-step-verification'] = 'PagesController/two_step_verification';

// Course Creation Routes
$route['course/create'] = 'CourseController/create';
$route['course/save_course_details'] = 'CourseController/save_course_details';
$route['course/save_lessons'] = 'CourseController/save_lessons';
$route['course/save_quizzes'] = 'CourseController/save_quizzes';
$route['course/publish_course'] = 'CourseController/publish_course';
$route['course/save_draft'] = 'CourseController/save_draft';
$route['course/get_preview_data/(:any)'] = 'CourseController/get_preview_data/$1';
$route['course/view/(:any)'] = 'CourseController/view/$1';

$route['account'] = 'AccountController/index';
$route['account/update-profile'] = 'AccountController/updateProfile';
$route['account/change-password'] = 'AccountController/changePassword';
$route['account/update-avatar'] = 'AccountController/updateAvatar';
$route['account/update-notifications'] = 'AccountController/updateNotifications';
