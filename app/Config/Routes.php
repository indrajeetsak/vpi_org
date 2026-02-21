<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route to login
$routes->get('/', 'Auth::login');

// Auth Routes grouped under /auth
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->addPlaceholder('mobile', '[0-9]+'); // Add validation for mobile numbers
    $routes->post('authenticate-mobile', 'Auth::authenticateMobile');
    $routes->match(['GET', 'POST'], 'authenticate', 'Auth::authenticate');
    $routes->get('register', 'Auth::register');
    $routes->get('get-states', 'Auth::getStates'); // Fetch states for registration
    $routes->post('process-step1', 'Auth::processStep1');
    $routes->post('process-step2', 'Auth::processStep2');
   $routes->post('process-step3', 'Auth::processStep3');
    $routes->post('confirm-registration', 'Auth::confirmRegistration');
    $routes->post('process-post-application', 'Auth::processPostApplication'); // Handles final submission from apply_for_post_form
    $routes->get('apply_for_post_form', 'Auth::applyForPostForm'); // New route for Step 5
    $routes->get('check-email/(:segment)', 'Auth::checkEmail/$1');
    $routes->get('get-districts/(:segment)', 'Auth::getDistricts/$1');
    $routes->get('get-mla-areas/(:segment)', 'Auth::getMlaAreas/$1');
   
    $routes->get('get-blocks/(:segment)', 'Auth::getBlocks/$1');
    $routes->get('get-ls-hierarchy-by-mla-area/(:segment)', 'Auth::getLsHierarchyByMlaArea/$1'); // New route
   $routes->get('get-levels', 'Auth::getLevels'); // For AJAX in step 4 apply_for_post_form
    $routes->get('get-posts-by-level/(:segment)', 'Auth::getPostsByLevel/$1'); // For AJAX in step 4 apply_for_post_form
    $routes->get('logout', 'Auth::logout');
});

// Dashboard Routes
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('profile', 'Dashboard::profile');
    $routes->post('profile', 'Dashboard::updateProfile');
    $routes->get('id-card', 'Dashboard::idCard');
    $routes->get('visiting-card', 'Dashboard::visitingCard');
    $routes->get('letter-head', 'Dashboard::letterHead');
    // Application for Post routes
   // $routes->get('apply', 'Dashboard::applyForPostShowForm');
   $routes->get('apply', 'Dashboard::applyForPostShowForm'); // Shows form for existing users
    $routes->post('apply', 'Dashboard::processPostApplication');
    // $routes->get('get-levels-by-organ', 'Dashboard::getLevelsByOrgan'); // For AJAX
    $routes->get('get-posts-by-organ-level', 'Dashboard::getPostsBLevel'); // For AJAX
$routes->get('registration-thanks', 'Dashboard::registrationThanks'); // Page after successful payment and before logout
});

// Payment Routes
$routes->group('payment', ['filter' => 'auth'], function($routes) {
    $routes->get('initiate', 'PaymentController::initiate');
    $routes->post('initiate_ccavenue', 'PaymentController::initiateCCAvenue');
    $routes->match(['GET', 'POST'], 'ccavenue_response_handler', 'PaymentController::ccavenueResponseHandler');
    $routes->get('success', 'PaymentController::paymentSuccess');
    $routes->get('failure', 'PaymentController::paymentFailure');
});

// General "Thank You for Registering" page (if needed before login/post application)
// $routes->get('registration-thanks', 'Auth::registrationThanks');

// Committee Details Routes
$routes->get('committee-details', 'CommitteeController::index');
$routes->post('committee/get-members', 'CommitteeController::getMembers');
$routes->get('committee/get-sectors/(:num)', 'CommitteeController::getSectors/$1');

// Temporary Debug Route - REMOVE AFTER USE
$routes->get('admin/check_user/(:segment)', 'Admin::checkAdminUser/$1');
$routes->get('debug/goalpara', 'Debug::dumpGoalpara');
$routes->get('debug/columns/(:segment)', 'Debug::listColumns/$1');

// Admin Routes
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::index'); // Explicit route for admin dashboard
    $routes->get('applications', 'Admin::applications');
    $routes->get('usersRequest', 'Admin::applications'); // Route for the 'Application Requests' page
    $routes->get('users/viewApplication/(:num)', 'Admin::viewApplication/$1');// 'GET: admin/users/approveApplication/3'.
    $routes->get('users/view/(:num)', 'Admin::viewApplicationDetails/$1');
    $routes->get('users/approveApplication/(:num)', 'Admin::approveApplication/$1');// 'GET: admin/users/approveApplication/3'.
    $routes->get('applications/reject/(:num)', 'Admin::rejectApplication/$1');
    $routes->get('users', 'Admin::users');
    $routes->get('usersList', 'Admin::users'); // Explicit route for Office Bearers List page
    $routes->get('users/edit/(:num)', 'Admin::editUserForm/$1');
    $routes->post('users/update/(:num)', 'Admin::updateUser/$1');
    $routes->get('users/delete/(:num)', 'Admin::deleteUser/$1');
    $routes->get('export', 'Admin::export');

    $routes->get('export/active_users', 'Admin::exportActiveUsers'); // Route for exporting active users
    $routes->get('export/pending_applications', 'Admin::exportPendingApplications'); // Route for exporting all users (detailed)
    $routes->get('export/all_users_detailed', 'Admin::exportAllUsersDetailed'); // Route for exporting all users (detailed)
    $routes->get('locations/get_districts_by_state/(:num)', 'Admin\Locations::getDistrictsByState/$1');
    $routes->get('locations/get_data_by_district/(:num)/(:segment)', 'Admin\Locations::getDataByDistrict/$1/$2');
    $routes->post('locations/update_district_data', 'Admin\Locations::updateDistrictData');
    $routes->post('locations/add_districts', 'Admin\Locations::addDistricts');
    $routes->post('locations/update_districts', 'Admin\Locations::updateDistricts');
    $routes->post('locations/delete_district/(:num)', 'Admin\Locations::deleteDistrict/$1');
    // Blocks routes
    $routes->get('locations/get_blocks_by_district/(:num)', 'Admin\Locations::getBlocksByDistrict/$1');
    $routes->post('locations/add_blocks', 'Admin\Locations::addBlocks');
    // MLA Areas routes
    $routes->get('locations/get_mla_areas_by_district/(:num)', 'Admin\Locations::getMlaAreasByDistrict/$1');
    $routes->post('locations/add_mla_areas', 'Admin\Locations::addMlaAreas');
    // Sectors routes
    $routes->get('locations/get_sectors_by_block/(:num)', 'Admin\\Locations::getSectorsByBlock/$1');
    $routes->post('locations/add_sectors', 'Admin\\Locations::addSectors');
    // Villages routes
    $routes->get('locations/get_villages_by_sector/(:num)', 'Admin\\Locations::getVillagesBySector/$1');
    $routes->post('locations/add_villages', 'Admin\\Locations::addVillages');
    // Circles routes (Fixed)
    $routes->get('locations/get_all_circles', 'Admin\\Locations::getAllCircles');
    $routes->get('logout', 'Auth::logout'); // Admin logout

    $routes->get('manage-location', 'Admin::manageLocation');
    $routes->get('manage-location', 'Admin::manageLocation');
    $routes->get('manage-constituencies', 'Admin::manageConstituencies');

    // Admin Management Routes
    $routes->get('manage-admins', 'Admin::manageAdmins');

    // Manage Circles Routes
    $routes->get('manage-circles', 'Admin\Circles::index');
    $routes->get('circles/get_sectors', 'Admin\Circles::getSectorsForCircle');
    $routes->post('circles/update_assignment', 'Admin\Circles::updateCircleAssignment');

    // Manage Fronts Routes
    $routes->get('manage-fronts', 'Admin\Fronts::index');
    $routes->post('fronts/update', 'Admin\Fronts::update');
    $routes->get('create-admin', 'Admin::createAdmin');
    $routes->post('store-admin', 'Admin::storeAdmin');
    $routes->get('edit-admin/(:num)', 'Admin::editAdmin/$1');
    $routes->post('update-admin/(:num)', 'Admin::updateAdmin/$1');
    $routes->get('delete-admin/(:num)', 'Admin::deleteAdmin/$1');

    // Office Bearer routes
    $routes->get('add-office-bearer', 'Admin::addOfficeBearer');
    $routes->post('save-office-bearer', 'Admin::saveOfficeBearer');
    $routes->get('posts/by-level/(:segment)', 'Admin::getPostsByAppointmentLevel/$1');
    $routes->get('posts/availability', 'Admin::getPostsAvailability');
    $routes->get('committee-details/(:num)', 'Admin::committeeDetails/$1');
    $routes->post('query-committee', 'Admin::queryCommittee');
    
    // 3 Loksabha routes
    $routes->get('constituencies/get_3ls_by_4ls/(:num)', 'Admin::get3LsByFourLs/$1');
    $routes->post('constituencies/add_3ls', 'Admin::add3Ls');
    
    // 2 Loksabha routes
    $routes->get('constituencies/get_2ls_by_3ls/(:num)', 'Admin::get2LsByThreeLs/$1');
    $routes->post('constituencies/add_2ls', 'Admin::add2Ls');
    
    // 1 Loksabha routes
    $routes->get('constituencies/get_1ls_by_2ls/(:num)', 'Admin::get1LsByTwoLs/$1');
    $routes->post('constituencies/add_1ls', 'Admin::add1Ls');
});