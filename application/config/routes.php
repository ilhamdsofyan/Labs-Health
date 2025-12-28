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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller']	= 'SiteController/actionIndex';
$route['404_override']			= 'SiteController/action404NotFound';
$route['translate_uri_dashes']	= FALSE;

# START API
require_once __DIR__ . '/api.php';
# END API

# START API
require_once __DIR__ . '/rbac.php';
# END API

# START SITE
$route['site']				= 'SiteController/actionIndex';
$route['site/index']		= 'SiteController/actionIndex';
$route['site/login']		= 'SiteController/actionLogin';
$route['site/logout']		= 'SiteController/actionLogout';
$route['site/lock']			= 'SiteController/actionLock';
$route['site/google-auth']	= 'SiteController/actionGoogleAuth';
$route['site/404-not-found']	= 'SiteController/action404NotFound';
# END SITE

# START PROFIL
$route['profil']								= 'ProfilController/actionIndex';
$route['profil/index']							= 'ProfilController/actionIndex';
$route['profil/simpan-info-personal']['post']	= 'ProfilController/actionSimpanInfoPersonal';
$route['profil/ubah-foto']['post']				= 'ProfilController/actionUbahFoto';
$route['profil/simpan-dokumen']['post']			= 'ProfilController/actionSimpanDokumen';
$route['profil/ubah-password/(:num)']['post']	= 'ProfilController/actionUbahPassword/$1';
# END PROFIL

# START CALENDAR
$route['calendar']							= 'CalendarController/actionIndex';
$route['calendar/index']					= 'CalendarController/actionIndex';
$route['calendar/save']['post']				= 'CalendarController/actionSave';
$route['calendar/get-events']				= 'CalendarController/actionGetEvents';
$route['calendar/delete/(:any)']['post']	= 'CalendarController/actionDelete/$1';
# END CALENDAR

# START MODULE MASTER ROUTES
	# ALLERGIES
	$route['master/allergies']	= 'master/AllergiesController/actionIndex';

# END MODULE MASTER ROUTES

# START NOTIFIKASI
$route['notifikasi']				= 'NotifikasiController/actionIndex';
$route['notifikasi/index']			= 'NotifikasiController/actionIndex';
$route['notifikasi/read/(:num)']	= 'NotifikasiController/actionRead/$1';
# END NOTIFIKASI
