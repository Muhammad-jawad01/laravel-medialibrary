
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OpenAiController;

use App\Http\Controllers\WeatherController;

Route::get('/weather-ui', function () {
    return view('weather.show');
})->name('weather.ui');

Route::get('/weather', [WeatherController::class, 'show'])->name('weather.show');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {

    if (auth()->user()->id === 1) {
        return view('dashboard.admin');
    } else {
        return view('dashboard.user');
    }
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/post', [FrontController::class, 'post'])->name('post');
Route::get('/post-create', [FrontController::class, 'create'])->name('create.post');
Route::post('/post-store', [FrontController::class, 'store'])->name('post.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/img', [ProfileController::class, 'getForm'])->name('profile.img');
    Route::post('/profile/img', [ProfileController::class, 'updateImage'])->name('profile.img.post');

    Route::resource('gallery', GalleryController::class);
});

Route::get('/gemini/chat', function () {
    $interactions = \App\Models\GeminiInteraction::orderBy('created_at', 'desc')->take(20)->get();
    return view('gemini.chat', compact('interactions'));
})->name('gemini.chat');
Route::get('/gemini/post', function () {
    $interactions = \App\Models\GeminiInteraction::orderBy('created_at', 'desc')->take(20)->get();
    return view('gemini.post', compact('interactions'));
})->name('gemini.post');

Route::post('/gemini/ask', [OpenAiController::class, 'askGemini'])->name('gemini.ask');
Route::post('/gemini/ask/post', [OpenAiController::class, 'postGemini'])->name('gemini.ask.post');

Route::get('/gemini/history', function () {
    return \App\Models\GeminiInteraction::where('type', 'chat')->orderBy('created_at', 'desc')->take(20)->get()->map(function ($interaction) {
        return [
            'prompt' => $interaction->prompt,
            'response' => $interaction->response,
            'created_at' => $interaction->created_at->format('d M Y H:i'),
        ];
    });
})->name('gemini.history');

Route::get('/gemini/history/post', function () {
    return \App\Models\GeminiInteraction::where('type', 'post')->orderBy('created_at', 'desc')->take(20)->get()->map(function ($interaction) {
        return [
            'prompt' => $interaction->prompt,
            'response' => $interaction->response,
            'created_at' => $interaction->created_at->format('d M Y H:i'),
        ];
    });
})->name('gemini.history.post');

require __DIR__ . '/auth.php';
require __DIR__ . '/student.php';
require __DIR__ . '/staff.php';


Route::get('send-mail', function () {



    $details = [

        'title' => 'Mail from jdTech.com',

        'body' => 'This is for testing email using smtp'

    ];



    \Mail::to('josonroy99@gmail.com')->send(new \App\Mail\MyTestMail($details));



    dd("Email is Sent.");
});
