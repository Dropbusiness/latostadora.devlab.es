<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.


$routes->group('auth',null, function ($routes) {
	$routes->get('/', 'Admin\Auth::index');
	$routes->add('login', 'Admin\Auth::login');
	$routes->get('logout', 'Admin\Auth::logout');
	$routes->get('forgot_password', 'Admin\Auth::forgot_password');
});

$routes->group('admin', ['filter' => 'admin-auth:admin,operator'] ,function ($routes) {
	$routes->get('/', 'Admin\Dashboard::index');
	$routes->get('dashboard', 'Admin\Dashboard::index');
	$routes->get('dashboard/toolsjson', 'Admin\Dashboard::toolsjson');

	$routes->get('users', 'Admin\Users::index');
	$routes->add('users/create', 'Admin\Users::createUser');
	$routes->add('users/edit/(:num)', 'Admin\Users::edit/$1');
	$routes->add('users/activate/(:num)', 'Admin\Users::activate/$1');
	$routes->add('users/deactivate/(:num)', 'Admin\Users::deactivate/$1');
	$routes->add('users/edit_group/(:num)', 'Admin\Users::editGroup/$1');
	$routes->add('users/create_group', 'Admin\Users::createGroup');
	//Customer  Route List
	$routes->get('customer', 'Admin\Customer::index');
	$routes->get('customer/add', 'Admin\Customer::add');
	$routes->post('customer/save', 'Admin\Customer::save');
	$routes->get('customer/delete/(:num)', 'Admin\Customer::delete/$1');
	$routes->get('customer/edit/(:num)', 'Admin\Customer::edit/$1');
	$routes->put('customer/update/(:num)', 'Admin\Customer::update/$1');
	//Category  Route List
	$routes->get('category/(:num)', 'Admin\Category::index/$1');
	$routes->get('category', 'Admin\Category::index');
	$routes->get('category/add', 'Admin\Category::add');
	$routes->post('category/save', 'Admin\Category::save');
	$routes->get('category/delete/(:num)', 'Admin\Category::delete/$1');
	$routes->get('category/edit/(:num)', 'Admin\Category::edit/$1');
	$routes->put('category/update/(:num)', 'Admin\Category::update/$1');
	$routes->get('category/export', 'Admin\Category::export');
	$routes->get('category/cleancache', 'Admin\Category::cleancache');
	$routes->get('category/imgdel', 'Admin\Category::imgdel');
	//Brand  Route List
	$routes->get('brand', 'Admin\Brand::index');
	$routes->get('brand/add', 'Admin\Brand::add');
	$routes->post('brand/save', 'Admin\Brand::save');
	$routes->get('brand/delete/(:num)', 'Admin\Brand::delete/$1');
	$routes->get('brand/edit/(:num)', 'Admin\Brand::edit/$1');
	$routes->put('brand/update/(:num)', 'Admin\Brand::update/$1');
	//Product  Route List
	$routes->get('products', 'Admin\Products::index');
	$routes->get('products/create', 'Admin\Products::create');
	$routes->get('products/(:num)', 'Admin\Products::index/$1');
	$routes->get('products/(:num)/edit', 'Admin\Products::edit/$1');
	$routes->post('products', 'Admin\Products::store');
	$routes->put('products/(:num)', 'Admin\Products::update/$1');
	$routes->get('products/delete/(:num)', 'Admin\Products::destroy/$1');
	$routes->add('products/maintenance', 'Admin\Products::maintenance');
	$routes->get('products/export', 'Admin\Products::export');
	$routes->add('products/jsontools', 'Admin\Products::jsontools');
	//Product imagen Route List
	$routes->get('products/(:num)/images', 'Admin\Products::images/$1');
	$routes->get('products/(:num)/upload-image', 'Admin\Products::uploadImage/$1');
	$routes->post('products/(:num)/upload-image', 'Admin\Products::doUploadImage/$1');
	$routes->delete('products/images/(:num)', 'Admin\Products::destroyImage/$1');
	$routes->get('products/deleteimagen/(:num)', 'Admin\Products::destroyImage/$1');
	$routes->get('products/coverimagen/(:num)', 'Admin\Products::coverimagen/$1');
	$routes->post('products/positionimages/(:num)', 'Admin\Products::positionimages/$1');
	//Product features Route List
	$routes->get('products/(:num)/features', 'Admin\Products::features/$1');
	$routes->post('products/(:num)/setfeatures', 'Admin\Products::setfeatures/$1');
	//Product attributes Route List
	$routes->get('products/(:num)/attributes', 'Admin\Products::attributes/$1');
	$routes->post('products/(:num)/setattributes', 'Admin\Products::setattributes/$1');
	$routes->get('products/deleteCombination/(:num)', 'Admin\Products::deleteCombination/$1');
	//page  Route List
	$routes->get('page', 'Admin\Page::index');
	$routes->get('page/add', 'Admin\Page::add');
	$routes->post('page/save', 'Admin\Page::save');
	$routes->get('page/delete/(:num)', 'Admin\Page::delete/$1');
	$routes->get('page/edit/(:num)', 'Admin\Page::edit/$1');
	$routes->put('page/update/(:num)', 'Admin\Page::update/$1');
	//template email  Route List
	$routes->get('templateemail', 'Admin\Templateemail::index');
	$routes->get('templateemail/add', 'Admin\Templateemail::add');
	$routes->post('templateemail/save', 'Admin\Templateemail::save');
	$routes->get('templateemail/delete/(:num)', 'Admin\Templateemail::delete/$1');
	$routes->get('templateemail/edit/(:num)', 'Admin\Templateemail::edit/$1');
	$routes->put('templateemail/update/(:num)', 'Admin\Templateemail::update/$1');
	//Configuration  Route List
	$routes->get('configuration', 'Admin\Configuration::index');
	$routes->get('configuration/add', 'Admin\Configuration::add');
	$routes->post('configuration/save', 'Admin\Configuration::save');
	$routes->get('configuration/delete/(:num)', 'Admin\Configuration::delete/$1');
	$routes->get('configuration/edit/(:num)', 'Admin\Configuration::edit/$1');
	$routes->put('configuration/update/(:num)', 'Admin\Configuration::update/$1');
    $routes->get('configuration/help', 'Admin\Configuration::help');
	//order  Route List
	$routes->get('order', 'Admin\Order::index');
	$routes->get('order/show/(:num)', 'Admin\Order::show/$1');
	//contact  Route List
	$routes->get('contact', 'Admin\Contact::index');
	$routes->get('contact/show/(:num)', 'Admin\Contact::show/$1');
	//feature  Route List
	$routes->get('feature', 'Admin\Feature::index');
	$routes->get('feature/add', 'Admin\Feature::add');
	$routes->post('feature/save', 'Admin\Feature::save');
	$routes->get('feature/delete/(:num)', 'Admin\Feature::delete/$1');
	$routes->get('feature/edit/(:num)', 'Admin\Feature::edit/$1');
	$routes->put('feature/update/(:num)', 'Admin\Feature::update/$1');
	$routes->get('feature/export', 'Admin\Feature::export');
	//featurevalue  Route List
	$routes->get('featurevalue', 'Admin\Featurevalue::index');
	$routes->get('featurevalue/add', 'Admin\Featurevalue::add');
	$routes->post('featurevalue/save', 'Admin\Featurevalue::save');
	$routes->get('featurevalue/delete/(:num)', 'Admin\Featurevalue::delete/$1');
	$routes->get('featurevalue/edit/(:num)', 'Admin\Featurevalue::edit/$1');
	$routes->put('featurevalue/update/(:num)', 'Admin\Featurevalue::update/$1');
	$routes->get('featurevalue/export', 'Admin\Featurevalue::export');
	//attributes  Route List
	$routes->get('attributes', 'Admin\Attributes::index');
	$routes->get('attributes/add', 'Admin\Attributes::add');
	$routes->post('attributes/save', 'Admin\Attributes::save');
	$routes->get('attributes/delete/(:num)', 'Admin\Attributes::delete/$1');
	$routes->get('attributes/edit/(:num)', 'Admin\Attributes::edit/$1');
	$routes->put('attributes/update/(:num)', 'Admin\Attributes::update/$1');
	$routes->get('attributes/export', 'Admin\Attributes::export');
	//attributesvalue  Route List
	$routes->get('attributesvalue', 'Admin\Attributesvalue::index');
	$routes->get('attributesvalue/add', 'Admin\Attributesvalue::add');
	$routes->post('attributesvalue/save', 'Admin\Attributesvalue::save');
	$routes->get('attributesvalue/delete/(:num)', 'Admin\Attributesvalue::delete/$1');
	$routes->get('attributesvalue/edit/(:num)', 'Admin\Attributesvalue::edit/$1');
	$routes->put('attributesvalue/update/(:num)', 'Admin\Attributesvalue::update/$1');
	$routes->get('attributesvalue/export', 'Admin\Attributesvalue::export');
	//language  Route List
	$routes->get('language', 'Admin\Language::index');
	$routes->get('language/add', 'Admin\Language::add');
	$routes->post('language/save', 'Admin\Language::save');
	$routes->get('language/delete/(:num)', 'Admin\Language::delete/$1');
	$routes->get('language/edit/(:num)', 'Admin\Language::edit/$1');
	$routes->put('language/update/(:num)', 'Admin\Language::update/$1');
	$routes->get('language/change/(:alpha)', 'Admin\Language::change/$1', ['as' => 'admin_language_change']);
	//Artists  Route List
	$routes->get('artists', 'Admin\Artists::index');
	$routes->get('artists/add', 'Admin\Artists::add');
	$routes->post('artists/save', 'Admin\Artists::save');
	$routes->get('artists/delete/(:num)', 'Admin\Artists::delete/$1');
	$routes->get('artists/edit/(:num)', 'Admin\Artists::edit/$1');
	$routes->put('artists/update/(:num)', 'Admin\Artists::update/$1');
	//Events  Route List
	$routes->get('event', 'Admin\Events::index');
	$routes->get('event/add', 'Admin\Events::add');
	$routes->post('event/save', 'Admin\Events::save');
	$routes->get('event/delete/(:num)', 'Admin\Events::delete/$1');
	$routes->get('event/edit/(:num)', 'Admin\Events::edit/$1');
	$routes->put('event/update/(:num)', 'Admin\Events::update/$1');
	//Tours  Route List
	$routes->get('tours', 'Admin\Tours::index');
	$routes->get('tours/add', 'Admin\Tours::add');
	$routes->post('tours/save', 'Admin\Tours::save');
	$routes->get('tours/delete/(:num)', 'Admin\Tours::delete/$1');
	$routes->get('tours/edit/(:num)', 'Admin\Tours::edit/$1');
	$routes->put('tours/update/(:num)', 'Admin\Tours::update/$1');
	//translation  Route List
	$routes->group('translation',function ($routes){
        $routes->get('listing','Admin\Translation::listing',['as' => 'admin_translation_listing']);
        $routes->post('folder-list','Admin\Translation::folderList',['as' => 'admin_translation_folder_listing']);
        $routes->get('files/(:any)/(:any)','Admin\Translation::files/$1/$2',['as' => 'admin_translation_files']);
        $routes->match(['get','post'],'translate/(:any)/(:any)/(:any)','Admin\Translation::translate/$1/$2/$3',['as' => 'admin_translation_translate']);
    });
});



$routes->addPlaceholder('slug', '[a-zA-Z0-9-/ñ/&]+(?:-[a-zA-Z0-9]+)*');
/*
$routes->get('/{locale}/(:segment)/(:segment)/(:segment)/(:segment)', 'Front\Web::event/$1/$2/$3/$4');
$routes->get('(:segment)/(:segment)/(:segment)/(:segment)', 'Front\Web::event/$1/$2/$3/$4');
*/
#cront sendemailtours
$routes->get('/sendemailtours', 'Front\Cron::sendemailtours');
// Reemplaza las rutas actuales con estas
$routes->get('/{locale}/event/(:segment)/(:segment)/(:segment)/(:segment)', 'Front\Web::event/$1/$2/$3/$4');
$routes->get('/event/(:segment)/(:segment)/(:segment)/(:segment)', 'Front\Web::event/$1/$2/$3/$4');

$routes->get('/{locale}/event/(:segment)/(:segment)', 'Front\Web::tour/$1/$2');
$routes->get('/event/(:segment)/(:segment)', 'Front\Web::tour/$1/$2');

$routes->get('/{locale}/contenido/(:slug)', 'Front\Web::content/$1');
$routes->get('/contenido/(:slug)', 'Front\Web::content/$1');

$routes->get('/{locale}/producto/(:slug)', 'Front\Web::product/$1');
$routes->get('/producto/(:slug)', 'Front\Web::product/$1');

$routes->get('/{locale}/contacto', 'Front\Web::contacto');
$routes->get('contacto', 'Front\Web::contacto');

$routes->post('/{locale}/contacto', 'Front\Web::addcontacto');
$routes->post('/contacto', 'Front\Web::addcontacto');


$routes->get('/{locale}/catalogo', 'Front\Web::catalogo');
$routes->get('catalogo', 'Front\Web::catalogo');


$routes->get('/{locale}/signin', 'Front\Web::signin');
$routes->get('/signin', 'Front\Web::signin');
$routes->post('/{locale}/signin', 'Front\Web::attemptLogin');
$routes->post('/signin', 'Front\Web::attemptLogin');

$routes->get('/{locale}/signup', 'Front\Web::signup');
$routes->get('/signup', 'Front\Web::signup');
$routes->post('/{locale}/signup', 'Front\Web::signupadd');
$routes->post('/signup', 'Front\Web::signupadd');


$routes->get('/{locale}/autologin/(:slug)', 'Front\Web::autologin/$1');
$routes->get('/autologin/(:slug)', 'Front\Web::autologin/$1');

$routes->get('/{locale}/recuperar-contrasena', 'Front\Web::forgotPassword');
$routes->get('/recuperar-contrasena', 'Front\Web::forgotPassword');

$routes->post('/{locale}/recuperar-contrasena', 'Front\Web::attemptForgotPassword');
$routes->post('/recuperar-contrasena', 'Front\Web::attemptForgotPassword');

$routes->get('/{locale}/logout', 'Front\Web::logout');
$routes->get('/logout', 'Front\Web::logout');

$routes->get('/{locale}/checkout', 'Front\Web::checkout');
$routes->get('/checkout', 'Front\Web::checkout');
$routes->post('itemsaddcart', 'Front\Web::itemsaddcart');

$routes->get('/{locale}/orderconfirmation/(:hash)', 'Front\Web::orderconfirmation/$1');
$routes->get('/orderconfirmation/(:hash)', 'Front\Web::orderconfirmation/$1');

$routes->get('/{locale}/ordercancel', 'Front\Web::ordercancel');
$routes->get('/ordercancel', 'Front\Web::ordercancel');

$routes->get('/{locale}/message', 'Front\Web::message');
$routes->get('/message', 'Front\Web::message');

$routes->get('/{locale}/micuenta', 'Front\Web::myaccount');
$routes->get('/micuenta', 'Front\Web::myaccount');

$routes->get('/{locale}/misdatos', 'Front\Web::misdatos');
$routes->get('/misdatos', 'Front\Web::misdatos');

$routes->get('/{locale}/change-password', 'Front\Web::changepassword');
$routes->get('/change-password', 'Front\Web::changepassword');

$routes->post('/{locale}/change-password', 'Front\Web::attemptchangepassword');
$routes->post('/change-password', 'Front\Web::attemptchangepassword');

$routes->get('/{locale}/change-dato', 'Front\Web::changedato');
$routes->get('/change-dato', 'Front\Web::changedato');

$routes->post('/{locale}/change-dato', 'Front\Web::attemptchangedato');
$routes->post('/change-dato', 'Front\Web::attemptchangedato');

$routes->get('/{locale}/miscarritos', 'Front\Web::miscarritos');
$routes->get('/miscarritos', 'Front\Web::miscarritos');

$routes->get('/{locale}/detallecarrito/(:num)', 'Front\Web::detallecarrito/$1');
$routes->get('/detallecarrito/(:num)', 'Front\Web::detallecarrito/$1');

$routes->get('/{locale}/mispedidos', 'Front\Web::mispedidos');
$routes->get('/mispedidos', 'Front\Web::mispedidos');

$routes->get('/{locale}/detallepedido', 'Front\Web::detallepedido');
$routes->get('/detallepedido', 'Front\Web::detallepedido');

$routes->get('/adyenprueba', 'Front\Web::adyenprueba');

$routes->get('/adyen', 'Front\Web::adyen');
$routes->post('/adyen', 'Front\Web::adyen');

$routes->post('/adyenpaymentverify', 'Front\Web::adyenpaymentverify');
$routes->get('/adyenpaymentverify', 'Front\Web::adyenpaymentverify');

$routes->post('/adyenhandlewebhook', 'Front\Web::adyenhandlewebhook');

$routes->add('/{locale}/jsontools', 'Front\Web::jsontools');
$routes->add('/jsontools', 'Front\Web::jsontools');

$routes->get('/{locale}/redsyspayment/(:num)', 'Front\Web::redsyspayment/$1');
$routes->get('/redsyspayment/(:num)', 'Front\Web::redsyspayment/$1');

$routes->get('/{locale}/redsysprocess', 'Front\Web::redsysprocess');
$routes->get('/redsysprocess', 'Front\Web::redsysprocess');
$routes->post('/{locale}/redsysprocess', 'Front\Web::redsysprocess');
$routes->post('/redsysprocess', 'Front\Web::redsysprocess');

$routes->get('/change-lang/(:slug)', 'Front\Web::changelang/$1');

$routes->get('/{locale}', 'Front\Web::index');
$routes->get('/', 'Front\Web::index');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
