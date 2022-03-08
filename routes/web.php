<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backends\DashboardController;

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



Route::group(['middleware' => 'guest'], function (){ 
    Route::get('detail/infokpi/{id}', 'App\Http\Controllers\Frontends\HomeController@show')->name('infokpi.detail'); 
    Route::get('faq', 'App\Http\Controllers\Backends\FAQController@index')->name('faq'); 
});



Route::group(['middleware' => 'sso'], function () {

    // ======================== route dashboard section ========================================= //
    Route::group(['prefix' => 'dashboard'], function() {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('show/{id}', 'App\Http\Controllers\Backends\DashboardController@show')->name('dashboard.show');
        Route::get('info/detail/{id}', 'App\Http\Controllers\Frontends\DashboardController@show')->name('dashboard.infokpi.detail');
        Route::get('api/rencana/summary', 'App\Http\Controllers\Backends\DashboardController@dataSummaryRencana')->name('dashboard.api.rencana.summary');
        Route::get('api/realisasi/summary', 'App\Http\Controllers\Backends\DashboardController@dataSummaryRealisasi')->name('dashboard.api.realisasi.summary');
        Route::get('api/rencanabawahan/summary', 'App\Http\Controllers\Backends\DashboardController@dataSummaryRencanaBawahan')->name('dashboard.api.rencanabawahan.summary');
        Route::get('api/realisasibawahan/summary', 'App\Http\Controllers\Backends\DashboardController@dataSummaryRealisasiBawahan')->name('dashboard.api.realisasibawahan.summary');
        Route::get('api/rencanapengembanan/summary', 'App\Http\Controllers\Backends\DashboardController@dataSummaryRencanaPengembangan')->name('dashboard.api.rencanapengembangan.summary');
    });

    // ======================== route user profile section ========================================= //
    Route::get('profile', 'App\Http\Controllers\Backends\ProfileController@index')->name('profile');
    Route::get('profile/edit', 'App\Http\Controllers\Backends\ProfileController@edit')->name('profile.edit');
    Route::put('update', 'App\Http\Controllers\Backends\ProfileController@update')->name('profile.update');
    Route::put('{id}', 'App\Http\Controllers\Auth\AuthController@privilege')->name('privilege');
    Route::get('logout', 'App\Http\Controllers\Auth\AuthController@logout')->name('logout');

    Route::get('notif', 'App\Http\Controllers\Backends\NotificationController@get')->name('notifications');
    Route::get('notif/{id}', 'App\Http\Controllers\Backends\NotificationController@markAsRead')->name('markasread');


    Route::group(['prefix' => 'hakakses'], function() {
        Route::get('/', 'App\Http\Controllers\Backends\Master\UserPrivilegeController@index')->name('backend.master.user.privilege');
        Route::get('{id}/edit', 'App\Http\Controllers\Backends\Master\UserPrivilegeController@edit')->name('backend.master.user.privilege.edit');
        Route::put('{id}', 'App\Http\Controllers\Backends\Master\UserPrivilegeController@update')->name('backend.master.user.privilege.update');
    });

    Route::group(['prefix' => 'master'], function() {
        Route::group(['prefix' => 'karyawan'], function() {
            Route::get('/', 'App\Http\Controllers\Backends\Master\KaryawanController@index')->name('backend.master.karyawan');
            Route::get('search/npk', 'App\Http\Controllers\Backends\Master\KaryawanController@searchnpk')->name('backend.master.search.npk');
            Route::get('{npk}', 'App\Http\Controllers\Backends\Master\KaryawanController@detail')->name('backend.master.karyawan.detail');
        });

        Route::group(['prefix' => 'jabatan'], function() {
            Route::get('/', 'App\Http\Controllers\Backends\Master\JabatanController@index')->name('backend.master.jabatan');
            Route::get('create', 'App\Http\Controllers\Backends\Master\JabatanController@create')->name('backend.master.jabatan.create');
            Route::post('store', 'App\Http\Controllers\Backends\Master\JabatanController@store')->name('backend.master.jabatan.store');
            Route::get('{id}/edit', 'App\Http\Controllers\Backends\Master\JabatanController@edit')->name('backend.master.jabatan.edit');
            Route::put('{id}', 'App\Http\Controllers\Backends\Master\JabatanController@update')->name('backend.master.jabatan.update');
            Route::delete('{id}', 'App\Http\Controllers\Backends\Master\JabatanController@delete')->name('backend.master.jabatan.delete');
        });

        Route::group(['prefix' => 'user'], function () {
            Route::get('/', 'App\Http\Controllers\Backends\Master\UserController@index')->name('backend.master.user');
            Route::get('create', 'App\Http\Controllers\Backends\Master\UserController@create')->name('backend.master.user.create');
            Route::post('store', 'App\Http\Controllers\Backends\Master\UserController@store')->name('backend.master.user.store');
            Route::get('{id}/edit', 'App\Http\Controllers\Backends\Master\UserController@edit')->name('backend.master.user.edit');
            Route::put('{id}', 'App\Http\Controllers\Backends\Master\UserController@update')->name('backend.master.user.update');
            Route::delete('{id}', 'App\Http\Controllers\Backends\Master\UserController@delete')->name('backend.master.user.delete');
        });

        Route::group(['prefix' => 'organizationalAssignment'], function() {
            Route::get('/','Backends\Master\OrganizationalAssignmentController@index')->name('backend.master.organizationalAssignment');
        });

        Route::group(['prefix' => 'unitkerja'], function () {
            Route::get('/', 'App\Http\Controllers\Backends\Master\UnitKerjaController@index')->name('backend.master.unitkerja');
            Route::post('/data', 'App\Http\Controllers\Backends\Master\UnitKerjaController@data')->name('backend.master.unitkerja.data');
            Route::get('create', 'App\Http\Controllers\Backends\Master\UnitKerjaController@create')->name('backend.master.unitkerja.create');
            Route::post('store', 'App\Http\Controllers\Backends\Master\UnitKerjaController@store')->name('backend.master.unitkerja.store');
            Route::get('{id}/edit', 'App\Http\Controllers\Backends\Master\UnitKerjaController@edit')->name('backend.master.unitkerja.edit');
            Route::put('{id}', 'App\Http\Controllers\Backends\Master\UnitKerjaController@update')->name('backend.master.unitkerja.update');
            Route::delete('{id}', 'App\Http\Controllers\Backends\Master\UnitKerjaController@delete')->name('backend.master.unitkerja.delete');
            Route::get('{id}', 'App\Http\Controllers\Backends\Master\UnitKerjaController@show')->name('backend.master.unitkerja.show');
        });

        Route::group(['prefix' => 'kompetensi'], function () {
            Route::get('/', 'App\Http\Controllers\Backends\Master\KompetensiController@index')->name('backend.master.kompetensi');
            Route::post('/data', 'App\Http\Controllers\Backends\Master\KompetensiController@data')->name('backend.master.kompetensi.data');
            Route::get('create', 'App\Http\Controllers\Backends\Master\KompetensiController@create')->name('backend.master.kompetensi.create');
            Route::post('store', 'App\Http\Controllers\Backends\Master\KompetensiController@store')->name('backend.master.kompetensi.store');
            Route::get('{id}/edit', 'App\Http\Controllers\Backends\Master\KompetensiController@edit')->name('backend.master.kompetensi.edit');
            Route::put('{id}', 'App\Http\Controllers\Backends\Master\KompetensiController@update')->name('backend.master.kompetensi.update');
            Route::delete('{id}', 'App\Http\Controllers\Backends\Master\KompetensiController@delete')->name('backend.master.kompetensi.delete');
            Route::get('{id}', 'App\Http\Controllers\Backends\Master\KompetensiController@show')->name('backend.master.kompetensi.show');
        });

        Route::group(['prefix' => 'periodeRealisasi'], function () {
            Route::get('/', 'App\Http\Controllers\Backends\Master\PeriodeRealisasiController@index')->name('backend.master.periodeRealisasi')/*->middleware('admin')*/;
            Route::post('/data', 'App\Http\Controllers\Backends\Master\PeriodeRealisasiController@data')->name('backend.master.periodeRealisasi.data')/*->middleware('admin')*/;
            Route::get('create', 'App\Http\Controllers\Backends\Master\PeriodeRealisasiController@create')->name('backend.master.periodeRealisasi.create')/*->middleware('admin')*/;
            Route::post('store', 'App\Http\Controllers\Backends\Master\PeriodeRealisasiController@store')->name('backend.master.periodeRealisasi.store')/*->middleware('admin')*/;
            Route::get('{id}/edit', 'App\Http\Controllers\Backends\Master\PeriodeRealisasiController@edit')->name('backend.master.periodeRealisasi.edit')/*->middleware('admin')*/;
            Route::post('update', 'App\Http\Controllers\Backends\Master\PeriodeRealisasiController@update')->name('backend.master.periodeRealisasi.update')/*->middleware('admin')*/;
            Route::get('{id}', 'App\Http\Controllers\Backends\Master\PeriodeRealisasiController@show')->name('backend.master.periodeRealisasi.show')/*->middleware('admin')*/;
        });
    });

    Route::group(['prefix' => 'kpi'], function() {
        Route::group(['prefix' => 'info'], function() {
            Route::get('/', 'App\Http\Controllers\Backends\KPI\InfoKPIController@index')->name('backend.kpi.info')->middleware('admin');
            Route::get('create', 'App\Http\Controllers\Backends\KPI\InfoKPIController@create')->name('backend.kpi.create')->middleware('admin');
            Route::post('store', 'App\Http\Controllers\Backends\KPI\InfoKPIController@store')->name('backend.kpi.store')->middleware('admin');
            Route::get('{id}/edit', 'App\Http\Controllers\Backends\KPI\InfoKPIController@edit')->name('backend.kpi.edit')->middleware('admin');
            Route::put('{id}', 'App\Http\Controllers\Backends\KPI\InfoKPIController@update')->name('backend.kpi.update')->middleware('admin');
            Route::delete('{id}', 'App\Http\Controllers\Backends\KPI\InfoKPIController@delete')->name('backend.kpi.delete')->middleware('admin');
            Route::get('{id}', 'App\Http\Controllers\Backends\KPI\InfoKPIController@show')->name('backends.kpi.show');
        });

        Route::group(['prefix' => 'kamus'], function () {
            Route::get('/', 'App\Http\Controllers\Backends\KPI\KamusKPIController@index')->name('backend.kpi.kamus');
            Route::post('/', 'App\Http\Controllers\Backends\KPI\KamusKPIController@data')->name('backend.kpi.kamus.data');
            Route::get('create', 'App\Http\Controllers\Backends\KPI\KamusKPIController@create')->name('backend.kpi.kamus.create');
            Route::post('store', 'App\Http\Controllers\Backends\KPI\KamusKPIController@store')->name('backend.kpi.kamus.store');
            Route::post('store/document', 'App\Http\Controllers\Backends\KPI\KamusKPIController@document')->name('backend.kpi.kamus.document.store');
            Route::get('/excel', 'App\Http\Controllers\Backends\KPI\KamusKPIController@excel')->name('backend.kpi.kamus.excel');
            Route::get('{id}/edit', 'App\Http\Controllers\Backends\KPI\KamusKPIController@edit')->name('backend.kpi.kamus.edit');
            Route::put('{id}', 'App\Http\Controllers\Backends\KPI\KamusKPIController@update')->name('backend.kpi.kamus.update');
            Route::delete('{id}', 'App\Http\Controllers\Backends\KPI\KamusKPIController@delete')->name('backend.kpi.kamus.delete');
            Route::get('{id}', 'App\Http\Controllers\Backends\KPI\KamusKPIController@show')->name('backend.kpi.kamus.show');
            Route::get('api/find', 'App\Http\Controllers\Backends\KPI\KamusKPIController@apiGetSingle')->name('backend.kpi.kamus.api.getsingle');
        });

        Route::group(['prefix' => 'rencana'], function () { 
            Route::get('individu/{id}/splititem', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@splitItem')->name('backends.kpi.rencana.individu.splititem');
            Route::get('individu/{id}/unsplititem', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@unsplitItem')->name('backends.kpi.rencana.individu.unsplititem');
            Route::post('individu/{id}/storesplit', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@storeSplitItem');
            Route::get('individu/{id}/storeunsplit', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@storeUnsplitItem');


            Route::get('individu/{id}/edititem', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@editItem')->name('backends.kpi.rencana.individu.edititem');
            Route::put('{id}', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@updateItem')->name('backends.kpi.rencana.individu.updateitem');
            Route::post('/', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@kamus')->name('backends.kpi.rencana.individu.kamus');
            Route::get('individu', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@index')->name('backends.kpi.rencana.individu');
            Route::get('individu/detail/{id}', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@getdatadetail')->name('backends.kpi.rencana.individu.data.detail');
            Route::get('individu/{id}', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@show')->name('backends.kpi.rencana.individu.show');
            Route::get('individu/create/step1', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@createStep1')->name('backends.kpi.rencana.individu.create.step1')->middleware(['checkExistingPeriode']);
            Route::post('individu/store/step1', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@storeStep1')->name('backends.kpi.rencana.individu.store.step1');
            Route::get('individu/{id}/editdetail', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@editDetail')->name('backends.kpi.rencana.individu.editdetail');
            Route::post('individu/{id}/storedetail', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@storeDetail')->name('backends.kpi.rencana.individu.storedetail');
            Route::delete('individu/detail/delete', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@deleteItem')->name('backends.kpi.rencana.individu.deleteItem');
            Route::get('individu/{id}/print', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@printPDF')->name('backends.kpi.rencana.individu.print');

            // ====================== attachment Rencana KPI =================================== //
            Route::get('individu/{id}/documents', 'App\Http\Controllers\Backends\KPI\Rencana\DocumentController@indexDocument')->name('backends.kpi.rencana.individu.indexdocument');
            Route::post('individu/{id}/documents', 'App\Http\Controllers\Backends\KPI\Rencana\DocumentController@storeDocument')->name('backends.kpi.rencana.individu.storedocument');
            Route::get('individu/{id}/documents/{attachmentId}/download', 'App\Http\Controllers\Backends\KPI\Rencana\DocumentController@downloadDocument')->name('backends.kpi.rencana.individu.documentdownload');
            Route::delete('individu/documents/delete', 'App\Http\Controllers\Backends\KPI\Rencana\DocumentController@deleteDocument')->name('backends.kpi.rencana.individu.documentdelete');
            Route::get('individu/{id}/documents/detail', 'App\Http\Controllers\Backends\KPI\Rencana\DocumentController@showDocument')->name('backends.kpi.rencana.individu.document.show');

            // ==================== penurunan rencana KPI ======================================== //
            Route::get('individu/{id}/penurunan', 'App\Http\Controllers\Backends\KPI\Rencana\PenurunanController@indexPenurunan')->name('backends.kpi.rencana.individu.pennurunan');
            Route::get('individu/{id}/penurunan/detail', 'App\Http\Controllers\Backends\KPI\Rencana\PenurunanController@showPenurunan')->name('backends.kpi.rencana.individu.penurunan.show');
            Route::get('individu/{id}/penurunan/{idcascade}/edit', 'App\Http\Controllers\Backends\KPI\Rencana\PenurunanController@editPenurunan')->name('backends.kpi.rencana.individu.penurunan.edit');
            Route::put('individu/{id}/penurunan/{idcascade}', 'App\Http\Controllers\Backends\KPI\Rencana\PenurunanController@updatePenurunan')->name('backends.kpi.rencana.individu.penurunan.update');
            Route::post('individu/{id}/penurunan/store', 'App\Http\Controllers\Backends\KPI\Rencana\PenurunanController@storePenurunan')->name('backends.kpi.rencana.individu.storepenurunan');
            Route::delete('individu/penurunan/delete', 'App\Http\Controllers\Backends\KPI\Rencana\PenurunanController@deleteCascade')->name('backends.kpi.rencana.individu.deletepenurunan');
            Route::get('individu/penurunan/delete', 'App\Http\Controllers\Backends\KPI\Rencana\PenurunanController@deleteCascade2');
            Route::get('individu/penurunan/detailrencana/{iddetailrencana}', 'App\Http\Controllers\Backends\KPI\Rencana\PenurunanController@apiGetDetilRencanaKPI')->name('backends.kpi.rencana.individu.penurunan.apidetailkpi');
            Route::post('individu/penurunan/checkexistcascade', 'App\Http\Controllers\Backends\KPI\Rencana\PenurunanController@apiCheckExistCascade')->name('backends.kpi.rencana.individu.penurunan.apicheckexistcascade');

            // ==================== Rencana Unit Kerja =================================== //
            Route::get('individu/{id}/unitkerja', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@unitkerja')->name('backends.kpi.rencana.individu.unitkerja.index');
            Route::get('individu/{iditem}/unitkerja/edititem', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@editItemUnitKerja')->name('backends.kpi.rencana.individu.unitkerja.edititem');
            Route::get('individu/{id}/unitkerja/detail', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@showUnitkerja')->name('backends.kpi.rencana.individu.unitkerja.show');
            Route::get('individu/{id}/unitkerja/print', 'App\Http\Controllers\Backends\KPI\Rencana\RencanaIndividuController@printUnitKerja')->name('backends.kpi.rencana.individu.unitkerja.print');

            // ======================== Rencana Bawahan langsung ========================== //
            Route::get('bawahanlangsung', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanLangsungController@indexBawahanLangsung')->name('backends.kpi.rencana.individu.bawahanlangsung');
            Route::get('bawahanlangsung/{id}', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanLangsungController@detailBawahanLangsung')->name('backends.kpi.rencana.individu.detailbawahanlangsung');
            Route::get('bawahanlangsung/{id}/penurunan', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanLangsungController@penurunanBawahanLangsung')->name('backends.kpi.rencana.individu.penurunanbawahanlangsung');
            Route::get('bawahanlangsung/{id}/documents', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanLangsungController@documentBawahanLangsung')->name('backends.kpi.rencana.individu.documentbawahanlangsung');
            Route::get('bawahanlangsung/{id}/unitkerja', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanLangsungController@unitkerjaBawahanLangsung')->name('backends.kpi.rencana.individu.unitkerjabawahanlangsung');
            Route::get('bawahanlangsung/{id}/print', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanLangsungController@exportToPdf')->name('backends.kpi.rencana.individu.printbawahanlangsung');

            // ================================ Rencana Bawahan Tak Langsung ========================================= //
            Route::get('bawahantaklangsung', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanTakLangsungController@indexBawahanTakLangsung')->name('backends.kpi.rencana.individu.bawahantaklangsung');
            Route::get('bawahantaklangsung/{id}', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanTakLangsungController@detailBawahanTakLangsung')->name('backends.kpi.rencana.individu.detailbawahantaklangsung');
            Route::get('bawahantaklangsung/{id}/penurunan', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanTakLangsungController@penurunanBawahanTakLangsung')->name('backends.kpi.rencana.individu.penurunanbawahantaklangsung');
            Route::get('bawahantaklangsung/{id}/documents', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanTakLangsungController@documentPenurunanTakLangsung')->name('backends.kpi.rencana.individu.documentbawahantaklangsung');
            Route::get('bawahantaklangsung/{id}/unitkerja', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanTakLangsungController@unitkerjaBawahanTakLangsung')->name('backends.kpi.rencana.individu.unitkerjabawahantaklangsung');
            Route::get('bawahantaklangsung/{id}/print', 'App\Http\Controllers\Backends\KPI\Rencana\BawahanTakLangsungController@exportToPdf')->name('backends.kpi.rencana.individu.printbawahantaklangsung');

            // ================ register & unregister ====================== //
            Route::post('register', 'App\Http\Controllers\Backends\KPI\Rencana\RegisterController@register')->name('backends.kpi.rencana.individu.register');
            Route::post('unregister', 'App\Http\Controllers\Backends\KPI\Rencana\RegisterController@unregister')->name('backends.kpi.rencana.individu.unregister');

            // =================== confirm & unconfirm ======================== //
            Route::post('confirm', 'App\Http\Controllers\Backends\KPI\Rencana\ConfirmationController@confirm')->name('backends.kpi.rencana.individu.confirm');
            Route::post('unconfirm', 'App\Http\Controllers\Backends\KPI\Rencana\ConfirmationController@unconfirm')->name('backends.kpi.rencana.individu.unconfirm');

            // =================== approve & unapprove ========================= //
            Route::post('approve', 'App\Http\Controllers\Backends\KPI\Rencana\ApprovalController@approve')->name('backends.kpi.rencana.approve');
            Route::post('unapprove', 'App\Http\Controllers\Backends\KPI\Rencana\ApprovalController@unapprove')->name('backends.kpi.rencana.unapprove');

            Route::post('cancel', 'App\Http\Controllers\Backends\KPI\Rencana\CancellationController@cancel')->name('backends.kpi.rencana.individu.cancellation');
        });

        Route::group(['prefix' => 'realisasi'], function () {
            Route::get('individu', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@index')->name('backends.kpi.realisasi.individu');
            Route::get('individu/create', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@create')->name('backends.kpi.realisasi.create');
            Route::post('store', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@store')->name('backends.kpi.realisasi.store');
            Route::get('individu/{id}', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@show')->name('backends.kpi.realisasi.individu.show');
            Route::get('individu/{id}/editdetail', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@editDetail')->name('backends.kpi.realisasi.individu.editdetail');

            Route::post('individu/{id}/storenilai', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@storeNilai')->name('backends.kpi.realisasi.individu.storenilai');

            Route::post('individu/{id}/store', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@storeItem')->name('realisasi.storeItem');

            Route::get('individu/{id}/print', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@printPDF')->name('backends.kpi.realisasi.individu.print');

            Route::get('individu/print/year', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@getDataRealisasiIndividuInYear')->name('backends.kpi.realisasi.individu.getDataRealisasiIndividuInYear');

            Route::get('individu/print/year/{year}', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@printPDFYear')->name('backends.kpi.realisasi.individu.printYear');

            Route::get('individu/{id}/edititem_realisasi', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@editItemRealisasi')->name('backends.kpi.rencana.individu.edititem_realisasi');

            Route::put('{id}', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@updateItem')->name('backends.kpi.realisasi.individu.updateitem');

            Route::delete('individu/detail/deleteItemRealisasi', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController@deleteItem')->name('backends.kpi.realisasi.individu.deleteItem');

            // ===================== Attachment Realisasi KPI =================================//
            Route::get('individu/{id}/documents', 'App\Http\Controllers\Backends\KPI\Realisasi\DocumentController@indexDocument')->name('backends.kpi.realisasi.individu.indexdocument');
            Route::post('individu/{id}/documents', 'App\Http\Controllers\Backends\KPI\Realisasi\DocumentController@storeDocument')->name('backends.kpi.realisasi.individu.storeDocument');
            Route::get('individu/{id}/documents/{attachmentId}/download', 'App\Http\Controllers\Backends\KPI\Realisasi\DocumentController@downloadDocument')->name('backends.kpi.realisasi.individu.documentdownload');
            Route::delete('individu/documents/delete', 'App\Http\Controllers\Backends\KPI\Realisasi\DocumentController@deleteDocument')->name('backends.kpi.realisasi.individu.deleteDocument');

            Route::get('bawahanlangsung', 'App\Http\Controllers\Backends\KPI\Realisasi\BawahanLangsungController@indexBawahanLangsung')->name('backends.kpi.realisasi.individu.bawahanlangsung');
            Route::get('bawahanlangsung/{id}', 'App\Http\Controllers\Backends\KPI\Realisasi\BawahanLangsungController@detailBawahanLangsung')->name('backends.kpi.realisasi.individu.detailbawahanlangsung');
            Route::get('bawahanlangsung/{id}/documents', 'App\Http\Controllers\Backends\KPI\Realisasi\BawahanLangsungController@documentBawahanLangsung')->name('backends.kpi.realisasi.individu.documentbawahanlangsung');

            Route::get('bawahantaklangsung', 'App\Http\Controllers\Backends\KPI\Realisasi\BawahanTakLangsungController@indexBawahanTakLangsung')->name('backends.kpi.realisasi.individu.bawahantaklangsung');
            Route::get('bawahantaklangsung/{id}', 'App\Http\Controllers\Backends\KPI\Realisasi\BawahanTakLangsungController@detailBawahanTakLangsung')->name('backends.kpi.realisasi.individu.detailbawahantaklangsung');
            Route::get('bawahantaklangsung/{id}/documents', 'App\Http\Controllers\Backends\KPI\Realisasi\BawahanTakLangsungController@documentBawahanTakLangsung')->name('backends.kpi.realisasi.individu.documentbawahantaklangsung');

            Route::get('grafiknilai', 'App\Http\Controllers\Backends\KPI\Realisasi\GrafikNilaiController@index')->name('backends.kpi.realisasi.individu.grafiknilai');
            Route::get('grafiknilai/find', 'App\Http\Controllers\Backends\KPI\Realisasi\GrafikNilaiController@find')->name('backends.kpi.realisasi.individu.findgrafiknilai');

            Route::get('rekapitulasi', 'App\Http\Controllers\Backends\KPI\Realisasi\ReportRecapitulationController@index')->name('backends.kpi.realisasi.individu.rekapitulasi');
            Route::get('rekapitulasi/download', 'App\Http\Controllers\Backends\KPI\Realisasi\ReportRecapitulationController@excel')->name('backends.kpi.realisasi.individu.unduhrekapitulasi');
            Route::get('rekapitulasi/{tahun}', 'App\Http\Controllers\Backends\KPI\Realisasi\ReportRecapitulationController@findPeriodeByTahun')->name('backends.kpi.realisasi.individu.periode');

            // ================ register & unregister ====================== //
            Route::post('register', 'App\Http\Controllers\Backends\KPI\Realisasi\RegisterController@register')->name('backends.kpi.realisasi.individu.register');
            Route::post('unregister', 'App\Http\Controllers\Backends\KPI\Realisasi\RegisterController@unregister')->name('backends.kpi.realisasi.individu.unregister');

            // =================== confirm & unconfirm ======================== //
            Route::post('confirm', 'App\Http\Controllers\Backends\KPI\Realisasi\ConfirmationController@confirm')->name('backends.kpi.realisasi.individu.confirm');
            Route::post('unconfirm', 'App\Http\Controllers\Backends\KPI\Realisasi\ConfirmationController@unconfirm')->name('backends.kpi.realisasi.individu.unconfirm');

            // =================== approve & unapprove ========================= //
            Route::post('approve', 'App\Http\Controllers\Backends\KPI\Realisasi\ApprovalController@approve')->name('backends.kpi.realisasi.approve');
            Route::post('unapprove', 'App\Http\Controllers\Backends\KPI\Realisasi\ApprovalController@unapprove')->name('backends.kpi.realisasi.unapprove');
            Route::post('cancel', 'App\Http\Controllers\Backends\KPI\Realisasi\CancellationController@cancel')->name('backends.kpi.realisasi.individu.cancellation');

            // ============================ REALISASI UNIT KERJA ====================================== //
            Route::group(['prefix'=>'unitkerja'],function() {
                Route::get('/', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@indexUnitKerja')->name('backends.kpi.realisasi.unitkerja');
                Route::get('create', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@createUnitKerja')->name('backends.kpi.realisasi.unitkerja.create');
                Route::post('/', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@storeUnitKerja')->name('backends.kpi.realisasi.unitkerja.store');
                Route::get('{id}/editdetail', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@editdetailUnitKerja')->name('backends.kpi.realisasi.unitkerja.editdetail');
                Route::post('{id}/storenilai', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@storeNilai')->name('backends.kpi.realisasi.unitkerja.storenilai');
                Route::get('{id}', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@showUnitKerja')->name('backends.kpi.realisasi.unitkerja.show');
                Route::get('{id}/documents', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@document')->name('backends.kpi.realisasi.unitkerja.document');
                Route::post('{id}/documents', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@storeDocument')->name('backends.kpi.realisasi.unitkerja.storeDocument');
                Route::get('{id}/documents/{attachmentId}/download', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@downloadDocument')->name('backends.kpi.realisasi.unitkerja.documentdownload');
                Route::delete('documents/delete', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@deleteDocument')->name('backends.kpi.realisasi.unitkerja.deletedocument');
                Route::get('{id}/print', 'App\Http\Controllers\Backends\KPI\Realisasi\RealisasiUnitKerjaController@printPDF')->name('backends.kpi.realisasi.unitkerja.print');
            });

            // ========================= Validasi unit kerja ============================================ //
            Route::group(['prefix' => 'validasi'], function() {
                Route::get('/', 'App\Http\Controllers\Backends\KPI\Realisasi\ValidasiUnitKerjaController@index')->name('backends.realisasi.validasi.unitkerja.index');
                Route::get('{idheaderealisasi}/create', 'App\Http\Controllers\Backends\KPI\Realisasi\ValidasiUnitKerjaController@create')->name('backends.realisasi.validasi.unitkerja.create');
                Route::post('store', 'App\Http\Controllers\Backends\KPI\Realisasi\ValidasiUnitKerjaController@store')->name('backends.realisasi.validasi.unitkerja.store');
                Route::post('approve', 'App\Http\Controllers\Backends\KPI\Realisasi\ValidasiUnitKerjaController@approve')->name('backends.realisasi.validasi.unitkerja.approve');
                Route::get('{idheaderealisasi}/edit', 'App\Http\Controllers\Backends\KPI\Realisasi\ValidasiUnitKerjaController@edit')->name('backends.realisasi.validasi.unitkerja.edit');
                Route::put('{idheaderealisasi}/update', 'App\Http\Controllers\Backends\KPI\Realisasi\ValidasiUnitKerjaController@update')->name('backends.realisasi.validasi.unitkerja.update');
                Route::post('cancel', 'App\Http\Controllers\Backends\KPI\Realisasi\ValidasiUnitKerjaController@cancel')->name('backends.realisasi.validasi.unitkerja.cancel');
            });
        });

        // ============================ RENCANA PENGEMBANGAN ====================================== //
        Route::group(['prefix' => 'pengembangan'], function () {
            Route::get('/', 'App\Http\Controllers\Backends\KPI\RencanaPengembangan\RencanaPengembanganController@index')->name('backends.kpi.rencanapengembangan');
            Route::get('realisasi/{idheaderrealisasi}', 'App\Http\Controllers\Backends\KPI\RencanaPengembangan\RencanaPengembanganController@show')->name('backends.kpi.rencanapengembangan.show');
            Route::post('realisasi/{idheaderrealisasi}/update', 'App\Http\Controllers\Backends\KPI\RencanaPengembangan\RencanaPengembanganController@update')->name('backends.kpi.rencanapengembangan.update');
        });
    });

    Route::get('jenisperiode/{jenisPeriode}', 'App\Http\Controllers\Backends\Master\PeriodeAktifController@findPeriodeByJenisPeriode')->name('backend.jenisperiode');
    Route::get('jenisperiode/{jenisPeriode}/{tahun}', 'App\Http\Controllers\Backends\Master\PeriodeAktifController@findPeriodeByJenisPeriodeAndTahun')->name('backend.jenisperiode.Tahun');

    Route::group(['prefix' => 'pengaturan'], function () {
        Route::group(['prefix' => 'periodeaktif'], function () {
            Route::get('/', 'App\Http\Controllers\Backends\Master\PeriodeAktifController@index')->name('backend.master.periodeaktif')->middleware('admin');
            Route::get('create', 'App\Http\Controllers\Backends\Master\PeriodeAktifController@create')->name('backend.master.periodeaktif.create')->middleware('admin');
            Route::post('store', 'App\Http\Controllers\Backends\Master\PeriodeAktifController@store')->name('backend.master.periodeaktif.store')->middleware('admin');
            Route::get('{id}/edit', 'App\Http\Controllers\Backends\Master\PeriodeAktifController@edit')->name('backend.master.periodeaktif.edit')->middleware('admin');
            Route::post('{id}', 'App\Http\Controllers\Backends\Master\PeriodeAktifController@update')->name('backend.master.periodeaktif.update')->middleware('admin');
            Route::delete('{id}', 'App\Http\Controllers\Backends\Master\PeriodeAktifController@delete')->name('backend.master.periodeaktif.delete')->middleware('admin');
            Route::get('{id}', 'App\Http\Controllers\Backends\Master\PeriodeAktifController@show')->name('backend.master.periodeaktif.show')->middleware('admin');
        });

        Route::group(['prefix' => 'narration', 'App\Http\Controllers\middleware' => 'admin'], function () {
            Route::get('/', 'App\Http\Controllers\Backends\ArtikelBeranda\ArtikelController@index')->name('narration');
            Route::post('store', 'App\Http\Controllers\Backends\ArtikelBeranda\ArtikelController@store')->name('narration.store');
        });

        Route::group(['prefix' => 'faq', 'App\Http\Controllers\middleware' => 'admin'], function () {
            Route::get('/', 'App\Http\Controllers\Backends\FAQController@index')->name('faq.index');
            Route::get('create', 'App\Http\Controllers\Backends\FAQController@create')->name('faq.create');
            Route::post('store', 'App\Http\Controllers\Backends\FAQController@store')->name('faq.store');
            Route::get('edit/{id}', 'App\Http\Controllers\Backends\FAQController@edit')->name('faq.edit');
            Route::put('{id}', 'App\Http\Controllers\Backends\FAQController@update')->name('faq.update');
            Route::delete('{id}', 'App\Http\Controllers\Backends\FAQController@delete')->name('faq.delete');
        });

        Route::group(['prefix' => 'validationmatrix', 'App\Http\Controllers\middleware' => 'admin'], function() {
            Route::get('/', 'App\Http\Controllers\Backends\ValidationMatrixController@index')->name('validationmatrix.index');
            Route::get('create', 'App\Http\Controllers\Backends\ValidationMatrixController@create')->name('validationmatrix.create');
            Route::post('store', 'App\Http\Controllers\Backends\ValidationMatrixController@store')->name('validationmatrix.store');
            Route::get('{id}/edit', 'App\Http\Controllers\Backends\ValidationMatrixController@edit')->name('validationmatrix.edit');
            Route::put('{id}', 'App\Http\Controllers\Backends\ValidationMatrixController@update')->name('validationmatrix.update');
            Route::delete('{id}', 'App\Http\Controllers\Backends\ValidationMatrixController@delete')->name('validationmatrix.delete');
        });
    });

    // =================================== REPORTING ================================================ //
    Route::group(['prefix' => 'laporan'], function() {
        Route::group(['prefix' => 'rencana'], function () {
            Route::get('kpiindividu', 'App\Http\Controllers\Backends\Reporting\RencanaIndividuController@index')->name('report.rencana.kpiindividu.index');
            Route::get('kpiunitkerja', 'App\Http\Controllers\Backends\Reporting\RencanaUnitKerjaController@index')->name('report.rencana.kpiunitkerja.index');
        });
        Route::group(['prefix' => 'realisasi'], function() {
            Route::get('kpiindividu', 'App\Http\Controllers\Backends\Reporting\RealisasiController@indexIndividu')->name('report.realisasi.kpiindividu.index');
            Route::get('kpiunitkerja', 'App\Http\Controllers\Backends\Reporting\RealisasiController@indexUnitKerja')->name('report.realisasi.kpiunitkerja.index');
        });

        Route::get('rencanapengembangan', 'App\Http\Controllers\Backends\Reporting\RencanaPengembanganController@indexReport')->name('report.rencanapengembangan.index');
    });
});

Route::get('/resizeimage/{modulename}/{width}/{height}/{imagename}', ['as' => 'image.resize', 'App\Http\Controllers\uses' => 'ImageController@resize']);
Route::get('/resizeimage/{modulename}/{width}/{imagename}', ['as' => 'image.resize.gallery', 'App\Http\Controllers\uses' => 'ImageController@resizegallery']);
