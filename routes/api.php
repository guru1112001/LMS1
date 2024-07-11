<?php

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TeachingMaterialController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\LeaveController;
//use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PasswordResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'
    //, 'verified'
])->group(function () {
    Route::get('/user', function (Request $request) {
        return new \App\Http\Resources\UserResource($request->user());
    });

    Route::put('/user', [UserController::class, 'update']);
});

// Authentication Routes
Route::post('login', [AuthController::class, 'login']);
Route::get('cities', function() {
    return \App\Http\Resources\CityResource::collection(\App\Models\City::all());
});
Route::get('states', function() {
    return \App\Http\Resources\StateResource::collection(\App\Models\State::all());
});
Route::get('qualifications', function() {
    return \App\Http\Resources\QualificationResource::collection(\App\Models\Qualification::all());
});

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['status' => true, 'message' => __($status)])
        : response()->json(['status' => false, 'message' => __($status)]);
})->middleware('guest')
    ->name('password.email');


//Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
//Route::post('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
//Route::post('reset-password', [AuthController::class, 'reset'])->name('password.update');

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Place protected routes here
    Route::post('logout', [AuthController::class, 'logout']);


    //Batch + Course
    Route::get('/batches', [BatchController::class,'index']);
    Route::get('/{id}/batch', [BatchController::class,'view']);

    //Sections By Batch
    Route::get('/{id}/curriculum',[CurriculumController::class,'index']);

    //Sections By Batch
    Route::get('/{id}/sections/{curriculum_id?}',[SectionController::class,'index']);

    //Teaching Material
    Route::get('/{id}/materials/{curriculum_id?}/{section_id?}/',[TeachingMaterialController::class,'index']);

    Route::post('/leaves/apply', [LeaveController::class, 'applyLeave']);
    Route::get('/leaves/list',[LeaveController::class,'index']);
    Route::get('/calenders/list', [CalendarController::class, 'fetchData']);

    Route::post('password/change', [PasswordResetController::class, 'changePassword']);

    //Route::get('/Syllabus',[\App\Http\Controllers\SyllabusController::class,'getUserSyllabus']);

    Route::get('/attendances', [\App\Http\Controllers\AttendanceController::class, 'index']);
    Route::get('/attendance-report', [\App\Http\Controllers\AttendanceController::class, 'getAttendanceReport']);

    Route::get('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index']);
    Route::get('/tutors', [\App\Http\Controllers\UserController::class, 'tutors']);

    //api for listing for sections



//	 Route::get('/attendances', [AttendanceController::class, 'index']);
//	  Route::get('/batches',[BatchController::class,'get_batches']);
});

