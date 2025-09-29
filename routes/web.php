<?php

use App\Models\Custom_Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/index', function () {
    if (Auth::user()) {
        return view('dashboard.index');
    } else {
        return view('auth/login');
    }
})->name('/');


Route::get('/dashboard', function () {
    if(!isset(Auth::user()->id) ||Auth::user()->id==''){
        return redirect('/index');
    }
    /*==========Log=============*/
    $trackarray = array(
        "activityName" => "Dashboard",
        "action" => "View Dashboard -> Function: Dashboard/index()",
        "PostData" => "",
        "affectedKey" => "",
        "idUser" => (isset(Auth::user()->id) && Auth::user()->id!=''?Auth::user()->id:0),
        "username" => (isset(Auth::user()->username) && Auth::user()->username!=''?Auth::user()->username:0),
    );
    $trackarray["mainResult"] = "Success";
    $trackarray["result"] = "View Success";
    Custom_Model::trackLogs($trackarray, "all_logs");
    /*==========Log=============*/

        return view('dashboard.index');
    })->middleware(['auth'])->name('dashboard');

    Route::get('/dashboard2', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard2');

/*=====================================Rapid Survey=====================================*/
Route::get('rapid_survey', 'Rapid_survey\Rs_linelisting@index')->middleware(['auth'])->name('rapid_survey');

Route::get('rs_linelisting', 'Rapid_survey\Rs_linelisting@index')->middleware(['auth'])->name('rs_linelisting');
Route::get('rs_linelisting_detail/{type?}/{id?}', 'Rapid_survey\Rs_linelisting@linelisting_detail')->middleware(['auth'])->name('rs_linelisting_detail');
Route::get('rs_randomized_detail/{id?}', 'Rapid_survey\Rs_linelisting@randomized_detail')->middleware(['auth'])->name('rs_randomized_detail');
Route::get('make_pdf/{id?}', 'Rapid_survey\Rs_linelisting@make_pdf')->middleware(['auth'])->name('make_pdf');
Route::post('rs_systematic_randomizer', 'Rapid_survey\Rs_linelisting@systematic_randomizer')->middleware(['auth'])->name('rs_systematic_randomizer');


Route::get('rs_data_collection', 'Rapid_survey\Rs_datacollection@index')->middleware(['auth'])->name('rs_data_collection');
Route::get('rs_data_collection_detail/{type?}/{id?}', 'Rapid_survey\Rs_datacollection@dataCollection_detail')->middleware(['auth'])->name('rs_data_collection_detail');
Route::get('rs_data_collection_statusdetail/{type?}/{id?}', 'Rapid_survey\Rs_datacollection@dataCollection_statusdetail')->middleware(['auth'])->name('rs_data_collection_statusdetail');
Route::get('not_visit_detail/{type?}/{id?}', 'Rapid_survey\Rs_datacollection@notVisit_detail')->middleware(['auth'])->name('not_visit_detail');

Route::get('rs_app_users', 'Rapid_survey\Rs_App_Users@index')->middleware(['auth'])->name('rs_app_users');
Route::post('rs_app_users/addAppUsers', 'Rapid_survey\Rs_App_Users@addAppUsers')->middleware(['auth'])->name('Rs_addAppUsers');
Route::get('rs_app_users/detail/{id?}', 'Rapid_survey\Rs_App_Users@getUserData')->middleware(['auth'])->name('Rs_getUserData');
Route::post('rs_app_users/editAppUsers', 'Rapid_survey\Rs_App_Users@editAppUsers')->middleware(['auth'])->name('Rs_editAppUsers');
Route::post('rs_app_users/resetPwd', 'Rapid_survey\Rs_App_Users@resetPwd')->middleware(['auth'])->name('Rs_resetPwd');
Route::post('rs_app_users/deleteAppUsers', 'Rapid_survey\Rs_App_Users@deleteAppUsers')->middleware(['auth'])->name('Rs_deleteAppUsers');

/*=====================================DHIS Data Validateion RSD=====================================*/
Route::get('rsd', 'RSD\Rsd@index')->middleware(['auth'])->name('rsd');
Route::post('getRsdData', 'RSD\Rsd@getRsdData')->middleware(['auth'])->name('getRsdData');
Route::get('facilityDetail/{id?}', 'RSD\Rsd@facilityDetail')->middleware(['auth'])->name('facilityDetail');
Route::post('facilityDetailData/{id?}', 'RSD\Rsd@facilityDetailData')->middleware(['auth'])->name('facilityDetailData');
Route::get('comparison/{id?}/{date?}/{type?}', 'RSD\Rsd@comparison')->middleware(['auth'])->name('comparison');
Route::post('editRsd', 'RSD\RSD@editRsd')->middleware(['auth'])->name('editRsd');

/*=====================================Sessions=====================================*/
Route::get('get_sessions', 'Sessions\SessionController@index')->middleware(['auth'])->name('get_sessions');

/*=====================================LHW=====================================*/
Route::get('lhw_main_summary', 'LHW\Summary@index')->middleware(['auth'])->name('lhw_main_summary');
Route::get('lhw_summary', 'LHW\Lhw_Summary@index')->middleware(['auth'])->name('lhw_summary');
Route::get('lhw_household_summary', 'LHW\Household_Summary@index')->middleware(['auth'])->name('lhw_household_summary');

Route::get('lhw_hh_verified', 'LHW\HH_Verified@index')->middleware(['auth'])->name('lhw_hh_verified');
Route::get('lhw_hfc_summary', 'LHW\HFC_Summary@index')->middleware(['auth'])->name('lhw_hfc_summary');
Route::get('lhw_family_members_details', 'LHW\Family_Members_Detail@index')->middleware(['auth'])->name('lhw_family_members_details');
Route::get('lhw_interviewed_hh', 'LHW\Interviewed_HH@index')->middleware(['auth'])->name('lhw_interviewed_hh');


Route::get('lhw_app_users', 'LHW\Lhw_App_Users@index')->middleware(['auth'])->name('lhw_app_users');
Route::post('lhw_app_users/addAppUsers', 'LHW\Lhw_App_Users@addAppUsers')->middleware(['auth'])->name('lhw_addAppUsers');
Route::get('lhw_app_users/detail/{id?}', 'LHW\Lhw_App_Users@getUserData')->middleware(['auth'])->name('lhw_getUserData');
Route::post('lhw_app_users/editAppUsers', 'LHW\Lhw_App_Users@editAppUsers')->middleware(['auth'])->name('lhw_editAppUsers');
Route::post('lhw_app_users/resetPwd', 'LHW\Lhw_App_Users@resetPwd')->middleware(['auth'])->name('lhw_resetPwd');
Route::post('lhw_app_users/deleteAppUsers', 'LHW\Lhw_App_Users@deleteAppUsers')->middleware(['auth'])->name('lhw_deleteAppUsers');

//Route::get('editAppUsers_grp', 'LHW\Lhw_App_Users@editAppUsers_grp')->middleware(['auth'])->name('editAppUsers_grp');

Route::post('Summary/changeDistrict', 'LHW\Summary@changeDistrict')->middleware(['auth'])->name('changeDistrict_lhw');
Route::post('Summary/changeTehsil', 'LHW\Summary@changeTehsil')->middleware(['auth'])->name('changeTehsil_lhw');


/*=====================================HFA Rapid Survey=====================================*/

Route::get('hfa_summary', 'HFA_RS\HFA_Summary@index')->middleware(['auth'])->name('hfa_summary');
Route::get('hfa_summary_detail_table/{id?}', 'HFA_RS\HFA_Summary@hfa_summary_detail_table')->middleware(['auth'])->name('hfa_summary_detail_table');
//Route::get('hfa_summary_detail/{type?}/{id?}', 'HFA_RS\HFA_Summary@hfa_detail')->middleware(['auth'])->name('hfa_summary_detail');


Route::get('hfa_app_users', 'HFA_RS\Hfa_App_Users@index')->middleware(['auth'])->name('hfa_app_users');
Route::post('hfa_app_users/addAppUsers', 'HFA_RS\Hfa_App_Users@addAppUsers')->middleware(['auth'])->name('hfa_addAppUsers');
Route::get('hfa_app_users/detail/{id?}', 'HFA_RS\Hfa_App_Users@getUserData')->middleware(['auth'])->name('hfa_getUserData');
Route::post('hfa_app_users/editAppUsers', 'HFA_RS\Hfa_App_Users@editAppUsers')->middleware(['auth'])->name('hfa_editAppUsers');
Route::post('hfa_app_users/resetPwd', 'HFA_RS\Hfa_App_Users@resetPwd')->middleware(['auth'])->name('hfa_resetPwd');
Route::post('hfa_app_users/deleteAppUsers', 'HFA_RS\Hfa_App_Users@deleteAppUsers')->middleware(['auth'])->name('hfa_deleteAppUsers');


/*=====================================UeN KMC=====================================*/

Route::get('kmc_app_users', 'KMC\Kmc_App_Users@index')->middleware(['auth'])->name('kmc_app_users');
Route::post('kmc_app_users/addAppUsers', 'KMC\Kmc_App_Users@addAppUsers')->middleware(['auth'])->name('kmc_addAppUsers');
Route::get('kmc_app_users/detail/{id?}', 'KMC\Kmc_App_Users@getUserData')->middleware(['auth'])->name('kmc_getUserData');
Route::post('kmc_app_users/editAppUsers', 'KMC\Kmc_App_Users@editAppUsers')->middleware(['auth'])->name('kmc_editAppUsers');
Route::post('kmc_app_users/resetPwd', 'KMC\Kmc_App_Users@resetPwd')->middleware(['auth'])->name('kmc_resetPwd');
Route::post('kmc_app_users/deleteAppUsers', 'KMC\Kmc_App_Users@deleteAppUsers')->middleware(['auth'])->name('kmc_deleteAppUsers');


/*=====================================Academic Detailing=====================================*/
Route::get('health_care_providers', 'AD\Health_care_providers@index')->middleware(['auth'])->name('health_care_providers');

Route::post('health_care_providers/addHC_provider', 'AD\Health_care_providers@addHC_provider')->middleware(['auth'])->name('addHC_provider');
Route::get('health_care_providers/detail/{id?}', 'AD\Health_care_providers@getUserData')->middleware(['auth'])->name('getUserData');
Route::post('health_care_providers/deleteHC_provider', 'AD\Health_care_providers@deleteHC_provider')->middleware(['auth'])->name('deleteHC_provider');



/*=====================================App Users=====================================*/
Route::get('/App_Users', 'App_Users@index')->middleware(['auth'])->name('App_Users');

Route::post('App_Users/addAppUsers', 'App_Users@addAppUsers')->middleware(['auth'])->name('addAppUsers');
Route::get('App_Users/detail/{id?}', 'App_Users@getUserData')->middleware(['auth'])->name('getUserData');
Route::post('App_Users/editAppUsers', 'App_Users@editAppUsers')->middleware(['auth'])->name('editAppUsers');
Route::post('App_Users/resetPwd', 'App_Users@resetPwd')->middleware(['auth'])->name('resetPwd');
Route::post('App_Users/deleteAppUsers', 'App_Users@deleteAppUsers')->middleware(['auth'])->name('deleteAppUsers');

/*=====================================Apps=====================================*/
Route::get('apps', 'Apps@index')->middleware(['auth'])->name('apps');

/*=====================================Settings=====================================*/
Route::prefix('settings')->group(function () {
    Route::get('groups', 'Settings\Group@index')->middleware(['auth'])->name('groups');
    Route::post('groups/addGroup', 'Settings\Group@addGroup')->middleware(['auth'])->name('addGroup');
    Route::get('groups/detail/{id?}', 'Settings\Group@getGroupData')->middleware(['auth'])->name('detailGroup');
    Route::post('groups/editGroup', 'Settings\Group@editGroup')->middleware(['auth'])->name('editGroup');
    Route::post('groups/deleteGroup', 'Settings\Group@deleteGroup')->middleware(['auth'])->name('deleteGroup');

    Route::get('groupSettings/{id?}', 'Settings\GroupSettings@index')->middleware(['auth'])->name('groupSettings');
    Route::get('getFormGroupData/{id?}', 'Settings\GroupSettings@getFormGroupData')->middleware(['auth'])->name('getFormGroupData');
    Route::post('fgAdd', 'Settings\GroupSettings@fgAdd')->middleware(['auth'])->name('fgAdd');

    Route::get('pages', 'Settings\Pages@index')->middleware(['auth'])->name('pages');
    Route::post('pages/addPages', 'Settings\Pages@addPages')->middleware(['auth'])->name('addPages');
    Route::get('pages/detail/{id?}', 'Settings\Pages@getPagesData')->middleware(['auth'])->name('detailPages');
    Route::post('pages/editPages', 'Settings\Pages@editPages')->middleware(['auth'])->name('editPages');
    Route::post('pages/deletePages', 'Settings\Pages@deletePages')->middleware(['auth'])->name('deletePages');

//    Route::view('dashboard_users', 'general_settings.dashboard_users')->name('dashboard_users');
    Route::get('Dashboard_Users', 'Settings\Dashboard_Users@index')->middleware(['auth'])->name('dashboard_users');
    Route::post('Dashboard_Users/addDashboardUsers', 'Settings\Dashboard_Users@addDashboardUsers')->middleware(['auth'])->name('addDashboardUsers');
    Route::get('Dashboard_Users/detail/{id?}', 'Settings\Dashboard_Users@getDashboardUsersData')->middleware(['auth'])->name('getDashboardUsersData');
    Route::post('Dashboard_Users/editDashboardUsers', 'Settings\Dashboard_Users@editDashboardUsers')->middleware(['auth'])->name('editDashboardUsers');
    Route::post('Dashboard_Users/deleteDashboardUsers', 'Settings\Dashboard_Users@deleteDashboardUsers')->middleware(['auth'])->name('deleteDashboardUsers');
    Route::post('Dashboard_Users/resetPwd', 'Settings\Dashboard_Users@resetPwd')->middleware(['auth'])->name('resetPwd');
    Route::get('Dashboard_Users/user_log_reports/{id?}', 'Settings\Dashboard_Users@user_log_reports')->middleware(['auth'])->name('user_log_reports');

});
Route::post('changePassword', 'Settings\Dashboard_Users@changePassword')->middleware(['auth'])->name('changePassword');


Route::post('checkSession', 'Check_Session@checkSession')->name('checkSession');
/*=====================================Layout Settings=====================================*/
Route::get('layout-{light}', function ($light) {
    session()->put('layout', $light);
    session()->get('layout');
    if ($light == 'vertical-layout') {
        return redirect()->route('pages-vertical-layout');
    }
    return redirect()->route('index');
});
Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
})->name('clear.cache');

//Language Change
Route::get('lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ur', 'de', 'es', 'fr', 'pt', 'cn', 'ae'])) {
        abort(400);
    }
    Session()->put('locale', $locale);
    Session::get('locale');
    return redirect()->back();
})->name('lang');

require __DIR__ . '/auth.php';
