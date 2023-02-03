<?php
/* Web Routes
|-------------------------------------------------------------------------
| Here is where you can register web routes for your application. These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!*/
 #php artisan route:clear
 #php artisan config:clear
 #php artisan cache:clear
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::post('/forgot','\App\Http\Controllers\Auth\LoginController@forgot');
Route::post('/reset','\App\Http\Controllers\Auth\LoginController@reset');
Route::view('/forgot_password', 'auth.reset_password')->name('password.reset');
Route::get('/cms', 'PagesController@index');

Route::resource('sites','SitesController')->middleware('auth');
//Route::get('/api/getsites', 'SitesController@api_getsites');
//Route::get('/api/getsite/{siteid}', 'SitesController@api_getsite');
Route::post('searchsites', [
    'uses' => 'SitesController@search'
  ]);

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
//Route::get('/api/getusers', 'HomeController@api_getusers');
//Route::get('/api/getuser/{userid}', 'HomeController@api_getuser');
Route::resource('users','UsersController')->middleware('auth');

Route::resource('pois','PoisController')->middleware('auth');
Route::get('/create-main-poi/{siteid}', 'PoisController@create')->middleware('auth');
Route::get('/create-sub-poi/{parentpoiid}', 'PoisController@createsub')->middleware('auth');
Route::post('storesub', [
    'uses' => 'PoisController@storesub'
  ]);

Route::get('storesub', [
    'uses' => 'PoisController@storesub'
  ]);
//Route::get('/api/getpois', 'PoisController@api_getpois');
//Route::get('/api/getpoi/{poiid}', 'PoisController@api_getpoi');

Route::resource('poimedia','PoimediasController')->middleware('auth');
Route::get('/create-poimedia/{poiid}', 'PoimediasController@create')->middleware('auth');

Route::resource('siteaccessibilities','SiteaccessibilitiesController')->middleware('auth');
Route::get('/create-accessibility/{siteid}', 'SiteaccessibilitiesController@create')->middleware('auth');

Route::resource('siteworkinghours','SiteworkinghoursController')->middleware('auth');
Route::get('/create-siteworkinghours/{siteid}', 'SiteworkinghoursController@create')->middleware('auth');

Route::resource('tags','TagsController')->middleware('auth');

Route::resource('poitags','PoitagsController')->middleware('auth');
Route::get('/create-poitags/{poiid}', 'PoitagsController@create')->middleware('auth');
Route::post('search_tags', [
    'uses' => 'PoitagsController@search'
  ]);
Route::get('search_tags', [
    'uses' => 'PoitagsController@search'
  ]);

Route::resource('cydistricts','Cy_districtsController')->middleware('auth');
Route::get('/delete_district_img/{cydistrict_id}', 'Cy_districtsController@delete_district_img')->middleware('auth');
Route::get('/delete_district_video/{cydistrict_id}', 'Cy_districtsController@delete_district_video')->middleware('auth');

Route::resource('sitecategories','SitecategoriesController')->middleware('auth');
Route::get('/delete_category_img/{category_id}', 'SitecategoriesController@delete_category_img')->middleware('auth');
Route::get('/delete_category_video/{category_id}', 'SitecategoriesController@delete_category_video')->middleware('auth');

Route::resource('appcontent','App_ContentsController')->middleware('auth');
Route::get('/delete_appcontent_img/{appcontent_id}', 'App_ContentsController@delete_appcontent_img')->middleware('auth');
Route::get('/delete_appcontent_video/{appcontent_id}', 'App_ContentsController@delete_appcontent_video')->middleware('auth');


Route::resource('chronologicals','ChronologicalsController')->middleware('auth');
Route::get('/delete_chronological_img/{chronological_id}', 'ChronologicalsController@delete_chronological_img')->middleware('auth');
Route::get('/delete_chronological_video/{chronological_id}', 'ChronologicalsController@delete_chronological_video')->middleware('auth');

Route::resource('storytellings','StorytellingsController')->middleware('auth');
Route::get('/create-storytelling/{poiid}', 'StorytellingsController@create')->middleware('auth');
Route::resource('storytellingrates','StorytellingratesController')->middleware('auth');

Route::resource('thematicroutes','ThematicroutesController')->middleware('auth');
Route::resource('thematicroutesites','ThematicroutesitesController')->middleware('auth');
Route::get('/create-thematicroutesites/{thematicroute_id}', 'ThematicroutesitesController@create')->middleware('auth');
Route::post('search', [
    'uses' => 'ThematicroutesitesController@search'
  ]);

Route::resource('services','ServicesController'); //remember to set ->middleware('auth'); once its done
Route::get('/services', 'ServicesController@index')->middleware('auth');

Route::resource('poimediatypes','PoimediatypesController')->middleware('auth');
Route::resource('servicecategories','ServicecategoriesController')->middleware('auth');

Route::resource('questions','QuestionsController')->middleware('auth');
Route::resource('possibleanswers','PossibleanswersController')->middleware('auth');
Route::get('/create-possibleanswer/{question_id}', 'PossibleanswersController@create_possibleanswer')->middleware('auth');

Route::resource('qrrooms','Qr_roomsController')->middleware('auth');
Route::get('/create-qrrooms/{poiid}', 'Qr_roomsController@create')->middleware('auth');

Route::resource('beacons','BeaconsController')->middleware('auth');
Route::get('/create-beacons/{poiid}', 'BeaconsController@create')->middleware('auth');

Route::resource('qrrooms_linked','Qr_room_linksController')->middleware('auth');
Route::get('/create-qrrooms-linked/{group_id}/', 'Qr_room_linksController@create')->middleware('auth');


Route::resource('qrrooms_groups','Qr_room_groupsController')->middleware('auth');
Route::get('/create-qrrooms-grouped/{groupid}', 'Qr_room_groupsController@create')->middleware('auth');

Route::resource('outdoor_groups','Outdoor_groupsController')->middleware('auth');
Route::get('/create-outdoor-groups/{poiid}', 'Outdoor_groupsController@create')->middleware('auth');

Route::resource('outdoor_links','Outdoor_linksController')->middleware('auth');
Route::get('/create-outdoor-links/{outdoor_group_id}', 'Outdoor_linksController@create')->middleware('auth');

Route::get('/create-poiartifacts/{poiid}', 'PoisController@createartifact')->middleware('auth');
Route::get('/removeartifact/{poiid}', 'PoisController@removeartifact')->middleware('auth');
#Route::post('/storeartifacts/{poiid}', 'PoisController@storeartifacts')->middleware('auth');
Route::post('search_poiartifacts', [
    'uses' => 'PoisController@searchartifacts'
  ]);
Route::get('search_poiartifacts', [
    'uses' => 'PoisController@searchartifacts'
  ]);

Route::post('storeartifacts', [
    'uses' => 'PoisController@storeartifacts'
  ]);
Route::get('storeartifacts', [
    'uses' => 'PoisController@storeartifacts'
  ]);

Route::get('/push-notification', 'WebNotificationController@index')->middleware('auth')->name('push-notification');
Route::post('/store-token', 'WebNotificationController@storeDeviceToken')->middleware('auth')->name('save-token');
Route::post('/send-web-notification', 'WebNotificationController@sendWebNotification')->name('send.web-notification');
//Route::get('storeartifact', 'PoisController@createartifact')->middleware('auth');


// {!!Form::open(['route' => ['destroy', $qr_room_link->id,$qr_room_grouped->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}

