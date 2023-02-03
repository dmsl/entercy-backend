<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application. These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!*/

 //I have changed auth:api to auth:web on this file to allow api on the web
Route::middleware('auth:api')->post('/current_user', function (Request $request) {
    return $request->user();
});
/*---->
# File: app/Providers/RouteServiceProvider.php  ----> I have changed that on the following file to allow api on the web
protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('web') # <-- CHANGE to 'web'
             ->namespace($this->namespace."\\API")
             ->group(base_path('routes/api.php'));
    }*/

    //changes back if you want ot test APIs on browser
/*Route::middleware('auth:web')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::post('/login_socialmedia', 'Api\AuthController@login_socialmedia');
Route::post('/password_change', 'Api\AuthController@password_change'); //when user already logged in
Route::post('/reset_password', 'Api\AuthController@reset_password'); //default laravel
Route::post('/reset_password_link_custom', 'Api\AuthController@reset_password_link_custom'); //custom-email reset password link
Route::post('/reset_password_custom', 'Api\AuthController@reset_password_custom'); //custom-actuall reset password
Route::post('/register_email_link_custom', 'Api\AuthController@register_email_link_custom'); //custom-email register link
Route::post('/register_verify_custom', 'Api\AuthController@register_verify_custom'); //custom-actuall registrer verify

//Route::get('/test2', 'Api\AuthController@test2');
Route::post('/refreshtoken', 'Api\AuthController@refreshToken');
Route::post('/test', 'Api\AuthController@test')->middleware('auth:api');

Route::post('/logout', 'Api\AuthController@logout')->middleware('auth:api');
Route::post('sites', 'SitesController@api_getsites')->middleware('auth:api');
Route::post('site/{siteid}', 'SitesController@api_getsite')->middleware('auth:api');
Route::post('site', 'SitesController@api_getsite')->middleware('auth:api');
Route::post('sites_by_district', 'SitesController@api_getsites_by_district')->middleware('auth:api');
Route::post('sites_by_category', 'SitesController@api_getsites_by_category')->middleware('auth:api');
Route::post('sites_by_chronological', 'SitesController@api_getsites_by_chronological')->middleware('auth:api');
Route::post('all_sites', 'SitesController@api_get_all_sites')->middleware('auth:api');
Route::post('specific_sites', 'SitesController@api_get_specific_sites')->middleware('auth:api');
Route::post('MapJourneyPois', 'SitesController@api_MapJourneyPois')->middleware('auth:api');
Route::post('MapJourneyPois_by_userjourneyid', 'SitesController@api_MapJourneyPois_by_userjourneyid')->middleware('auth:api');
Route::post('POIContentViewByMediaTypeID', 'SitesController@api_POIContentViewByMediaTypeID')->middleware('auth:api');
////Route::post('users', 'UsersController@api_getusers')->middleware('auth:api');
//Route::post('user/{userid}', 'UsersController@api_getuser')->middleware('auth:api');
////Route::post('user', 'UsersController@api_getuser')->middleware('auth:api');
Route::post('pois', 'PoisController@api_getpois')->middleware('auth:api');
//Route::post('poi/{poiid}', 'PoisController@api_getpoi')->middleware('auth:api');
Route::post('poi', 'PoisController@api_getpoi')->middleware('auth:api');
Route::post('pois_by_parentPOI', 'PoisController@api_getpois_by_parentpoi')->middleware('auth:api');
Route::post('pois_with_cover_image', 'PoisController@api_getpois_with_cover_image')->middleware('auth:api');
Route::post('poi_with_cover_image', 'PoisController@api_getpoi_with_cover_image')->middleware('auth:api');
Route::post('api_get_kG_data', 'PoisController@api_get_kG_data')->middleware('auth:api');
Route::post('poimedias', 'PoimediasController@api_getpoimedias')->middleware('auth:api');
Route::post('poimedia', 'PoimediasController@api_getpoimedia')->middleware('auth:api');
Route::post('api_stitching_procedure', 'PoimediasController@api_stitching_procedure');
Route::post('call_stitching_tool', 'PoimediasController@call_stitching_tool');
Route::get('check_stitching_video_completion', 'PoimediasController@check_stitching_video_completion'); //runs every hour
Route::post('poimedia_by_poiID', 'PoimediasController@api_getpoimedia_by_poiID')->middleware('auth:api');
Route::post('poimedia_types', 'Api\AuthController@api_getpoimediatypes')->middleware('auth:api');
Route::post('poimedia_type', 'Api\AuthController@api_getpoimediatype')->middleware('auth:api');
Route::post('cy_districts', 'Api\AuthController@api_getcy_districts')->middleware('auth:api');
Route::post('cy_district', 'Api\AuthController@api_getcy_district')->middleware('auth:api');
Route::post('sitecategories', 'Api\AuthController@api_getsitecategories')->middleware('auth:api');
Route::post('sitecategory', 'Api\AuthController@api_getsitecategory')->middleware('auth:api');
Route::post('accessibilities', 'Api\AuthController@api_getaccessibilities')->middleware('auth:api');
Route::post('accessibility', 'Api\AuthController@api_getaccessibility')->middleware('auth:api');

Route::post('siteaccessibility', 'SiteaccessibilitiesController@api_getsiteaccessibility')->middleware('auth:api');
Route::post('siteaccessibilities_by_siteID', 'SiteaccessibilitiesController@api_getsiteaccessibilities_by_siteid')->middleware('auth:api');

Route::post('siteworkinghour', 'SiteworkinghoursController@api_getsiteworkinghour')->middleware('auth:api');
Route::post('siteworkinghours_by_siteID', 'SiteworkinghoursController@api_getsiteworkinghour_by_siteid')->middleware('auth:api');

Route::post('tags', 'TagsController@api_gettags')->middleware('auth:api');
Route::post('tag', 'TagsController@api_gettag')->middleware('auth:api');
Route::post('poitag', 'PoitagsController@api_getpoitag')->middleware('auth:api');
Route::post('poitags_by_poiID', 'PoitagsController@api_getpoitags_by_poiid')->middleware('auth:api');

Route::post('app_contents', 'App_ContentsController@api_get_app_contents')->middleware('auth:api');
Route::post('app_content', 'App_ContentsController@api_get_app_content')->middleware('auth:api');
Route::post('landingpage_categories', 'App_ContentsController@api_landingpage_categories')->middleware('auth:api');
Route::post('about_us', 'App_ContentsController@api_about_us')->middleware('auth:api');
Route::post('login_info', 'App_ContentsController@api_login_info')->middleware('auth:api');
Route::post('register_info', 'App_ContentsController@api_register_info')->middleware('auth:api');
Route::post('join_info', 'App_ContentsController@api_join_info')->middleware('auth:api');

Route::post('chronologicals', 'ChronologicalsController@api_get_chronologicals')->middleware('auth:api');
Route::post('chronological', 'ChronologicalsController@api_get_chronological')->middleware('auth:api');

Route::post('transportations', 'TransportationsController@api_get_transportations')->middleware('auth:api');
Route::post('transportation', 'TransportationsController@api_get_transportation')->middleware('auth:api');

Route::post('traveltypes', 'TraveltypesController@api_get_traveltypes')->middleware('auth:api');
Route::post('traveltype', 'TraveltypesController@api_get_traveltype')->middleware('auth:api');

//THE "store_userpreferences" API SHOULD NOT BE USED BY ANYONE ANYMORE
Route::post('store_userpreferences', 'UserpreferencesController@store_userpreferences')->middleware('auth:api');
Route::post('userpreferences', 'UserpreferencesController@api_get_userpreferences_by_userid')->middleware('auth:api');
Route::post('update_user', 'UserpreferencesController@update_user')->middleware('auth:api');
Route::post('get_user_details', 'UserpreferencesController@get_user_details')->middleware('auth:api');
Route::post('is_profile_completed', 'UserpreferencesController@is_profile_completed')->middleware('auth:api');

Route::post('storytelling', 'StorytellingsController@api_getstorytelling')->middleware('auth:api');
Route::post('storytellings_by_poiID', 'StorytellingsController@api_getstorytellings_by_poiid')->middleware('auth:api');

Route::post('storytellingrate', 'StorytellingratesController@api_getstorytellingrate')->middleware('auth:api');
Route::post('store_storytellingrate', 'StorytellingratesController@store_storytelling_rating')->middleware('auth:api');

Route::post('poirate', 'PoiratesController@api_getpoirate')->middleware('auth:api');
Route::post('store_poirate', 'PoiratesController@store_poi_rating')->middleware('auth:api');

Route::post('thematicroutes', 'ThematicroutesController@api_getthematicroutes')->middleware('auth:api');
Route::post('thematicroute', 'ThematicroutesController@api_getthematicroute')->middleware('auth:api');
Route::post('thematicroute_mediacounter', 'ThematicroutesController@api_getthematicroute_mediacounter')->middleware('auth:api');

Route::post('services', 'ServicesController@api_getservices')->middleware('auth:api');
Route::post('service', 'ServicesController@api_getservice')->middleware('auth:api');
Route::post('service_categories', 'ServicesController@api_getservicecategories')->middleware('auth:api');
Route::get('service_email_reminder', 'ServicesController@service_email_reminder'); //runs every sunday at 00:00 - send email

Route::post('mediatypes', 'PoimediatypesController@api_getmediatypes')->middleware('auth:api');
Route::post('mediatype', 'PoimediatypesController@api_getmediatype')->middleware('auth:api');

Route::post('questionsanswers', 'QuestionsController@api_getquestionsanswers')->middleware('auth:api');
Route::post('post_answer', 'UseranswersController@api_postanswer')->middleware('auth:api');
Route::post('all_user_answers', 'UseranswersController@api_getall_useranswers')->middleware('auth:api');
Route::post('user_answer', 'UseranswersController@api_get_useranswer')->middleware('auth:api');

Route::post('get_sites_by_AR', 'SitesController@api_getsites_by_AR')->middleware('auth:api');
Route::post('get_site_rooms', 'SitesController@api_getsites_by_rooms')->middleware('auth:api');
Route::post('get_site_groups', 'SitesController@api_getsites_by_groups')->middleware('auth:api');
Route::post('get_groups_by_outdoor_sites', 'SitesController@api_get_groups_by_outdoor_sites')->middleware('auth:api');
Route::post('get_site_outdoor_groups', 'SitesController@api_getsites_by_outdoor_groups')->middleware('auth:api');
Route::post('get_site_outdoor_links', 'SitesController@api_getsites_by_outdoor_links')->middleware('auth:api');
Route::post('get_group_links', 'Qr_room_linksController@api_get_group_links')->middleware('auth:api');
Route::post('store_site_group', 'SitesController@api_savesitegroup_by_groupID')->middleware('auth:api');
Route::post('store_site_link', 'SitesController@api_savesitelink_by_linkID')->middleware('auth:api');
Route::post('get_qr_room_by_qr_code', 'Qr_roomsController@api_get_qr_room_by_qr_code')->middleware('auth:api');
Route::post('get_qr_groupsmedia_by_qr_room', 'Qr_roomsController@api_get_qr_groupsmedia_by_qr_room')->middleware('auth:api');
Route::post('get_api_journey_reccomendation', 'UserJourneysController@api_journey_reccomendation')->middleware('auth:api');
Route::post('get_sites_by_outdoor_group_id', 'Outdoor_groupsController@api_get_sites_by_outdoor_group_id')->middleware('auth:api');
Route::post('get_outdoor_links_by_outdoor_group', 'Outdoor_groupsController@api_get_outdoor_links_by_outdoor_group')->middleware('auth:api');
Route::post('get_sites_by_outdoor_link_id', 'Outdoor_linksController@api_get_sites_by_outdoor_link_id')->middleware('auth:api');
Route::post('get_api_OutdoorAugmented', 'Outdoor_groupsController@api_OutdoorAugmented')->middleware('auth:api');
Route::post('api_get_app_content_video', 'App_ContentsController@api_get_app_content_video')->middleware('auth:api');

Route::post('api_create_journey', 'UserJourneysController@api_create_journey')->middleware('auth:api');
Route::post('api_check_userstatus', 'UserJourneysController@api_check_userstatus')->middleware('auth:api');
Route::post('api_getall_user_journey_by_user_id', 'UserJourneysController@api_getall_user_journey_by_user_id')->middleware('auth:api');
Route::post('api_change_userstatus', 'UserJourneysController@api_change_userstatus')->middleware('auth:api');
Route::post('api_refresh_journey', 'UserJourneysController@api_refresh_journey')->middleware('auth:api');

Route::post('storeDeviceToken_api', 'WebNotificationController@storeDeviceToken_api')->middleware('auth:api');
Route::post('retrieveDeviceToken_api', 'WebNotificationController@retrieveDeviceToken_api')->middleware('auth:api');
Route::post('sendWebNotification_api', 'WebNotificationController@sendWebNotification_api')->middleware('auth:api');

Route::post('store_userjourney_rate', 'Userjourney_ratesController@store_userjourney_rate')->middleware('auth:api');
Route::post('get_userjourney_rate', 'Userjourney_ratesController@get_userjourney_rate')->middleware('auth:api');
Route::post('store_visited_site_rate', 'Visited_site_ratesController@store_visited_site_rate')->middleware('auth:api');
Route::post('get_visited_site_rate', 'Visited_site_ratesController@get_visited_site_rate')->middleware('auth:api');
Route::post('get_overall_site_rate', 'Visited_site_ratesController@get_overall_site_rate')->middleware('auth:api');
Route::post('store_visited_site_of_journey', 'Visited_sites_of_journeysController@store_visited_site_of_journey')->middleware('auth:api');
Route::post('store_visited_site_of_journey_GPS', 'Visited_sites_of_journeysController@store_visited_site_of_journey_GPS')->middleware('auth:api');
Route::post('get_visited_sites_of_journey', 'Visited_sites_of_journeysController@get_visited_sites_of_journey')->middleware('auth:api');

Route::post('get_userjourney_sites_with_imgs', 'UserJourneysController@get_userjourney_sites_with_imgs')->middleware('auth:api');
Route::post('store_userjourney_and_sites_rates', 'Userjourney_ratesController@store_userjourney_and_sites_rates')->middleware('auth:api');
Route::post('calendar_view', 'UserJourneysController@calendar_view')->middleware('auth:api');

Route::post('are_optional_questions_completed', 'UserJourneysController@are_optional_questions_completed')->middleware('auth:api');
Route::post('api_journey_reccomendation_sites', 'UserJourneysController@api_journey_reccomendation_sites')->middleware('auth:api');
Route::post('api_journey_reccomendation_services', 'UserJourneysController@api_journey_reccomendation_services')->middleware('auth:api');

//Route::post('get_object_types', 'Object_typesController@get_object_types')->middleware('auth:api'); //table deleted
//Route::post('get_event_types', 'Event_typesController@get_event_types')->middleware('auth:api'); //table deleted
Route::post('post_user_tracking', 'User_trackingsController@post_user_tracking')->middleware('auth:api');
Route::post('get_user_tracking', 'User_trackingsController@get_user_tracking')->middleware('auth:api');

Route::post('post_favourite_poi', 'Favourite_poisController@post_favourite_poi')->middleware('auth:api');
Route::post('get_favourite_poi', 'Favourite_poisController@get_favourite_poi')->middleware('auth:api');
Route::post('delete_favourite_poi', 'Favourite_poisController@delete_favourite_poi')->middleware('auth:api');

Route::get('send_recommendation_notification', 'WebNotificationController@send_recommendation_notification'); //runs every day at around 11:00 o clock
Route::get('check_departing_date', 'UserJourneysController@check_departing_date'); //runs every day at around 11:00 o clock

Route::post('get_kb_by_poiid', 'PoisController@get_kb_by_poiid')->middleware('auth:api');


//test.
/*Route::middleware('auth:web')->get('allroutes', function() {
    $routeCollection = Route::getRoutes();
    //return Route::getRoutes()->get();
    echo "<table style='width:100%'>";
        foreach ($routeCollection as $value) {
          if (($value->getPrefix() == 'api') && ($value->uri != 'api/allroutes'))
          {
            echo "<tr>";
                echo "<td>" . $value->getPrefix() . "</td>";
                echo "<td>" . $value->uri . "</td>";
                //echo "<td>" . $value->getActionMethod() . "</td>";
                //echo "<td>" . $value->getName() . "</td>";
                //echo "<td>" . $value->getActionName() . "</td>";
            echo "</tr>";
          }
        }
    echo "</table>";
});*/
