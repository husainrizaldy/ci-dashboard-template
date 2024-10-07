<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
$route['default_controller']    = 'Dashboard';
$route['404_override']          = 'Extras/not_found';
$route['translate_uri_dashes']  = FALSE;

/**
 * SUPPORT PAGES
 */
$route['not-found']                 = 'Extras/not_found';
$route['dashboard/dash-service']    = 'Dashboard/x_dashboard_datas';
/**
 * AUTHENTICATION
 */
$route['login/(.+)']    = 'auth/Auth/index/$1';
$route['login']         = 'auth/Auth/index';
$route['logaction']     = 'auth/Auth/x_login_action';
$route['logout']        = 'auth/Auth/logout';

/**
 * DASHBOARD
 */
$route['dashboard']		= 'Dashboard/index';

/**
 * APPS
 */

$route['apps/users']                      		= 'apps/Users/index';
$route['apps/users/app-user-service']         	= 'apps/Users/x_user_service';

$route['apps/profile']                    		= 'apps/Profile/index';
$route['apps/profile/app-profile-service']    	= 'apps/Profile/x_profile_service';

$route['apps/roles']                    		= 'apps/Roles/index';
$route['apps/roles/app-roles-service']    		= 'apps/Roles/x_roles_service';

$route['apps/access']                      		= 'apps/Access/index';
$route['apps/access/app-access-service']        = 'apps/Access/x_access_service';

$route['apps/menu/modul']                      	= 'apps/Menu/page_modul';
$route['apps/menu/content']                     = 'apps/Menu/page_content';
$route['apps/menu/sub-content']                 = 'apps/Menu/page_sub';
$route['apps/menu/app-menu-service']        	= 'apps/Menu/x_menu_service';
