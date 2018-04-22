<?php

use App\Article;
use Illuminate\Pagination\LengthAwarePaginator;

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

Route::get('/', function () {
	$page = request()->get('page', 1);
	$perPage = 20;

    $article = Article::find(1);

    $comments = $article->nestedComments($page, $perPage);

    $comments = new LengthAwarePaginator(
    	$comments,
    	count($article->comments->where('parent_id', null)),
    	$perPage,
    	$page,
    	['path' => request()->url(), 'query' => request()->query()]
    );

    return view('comments.index', compact('article', 'comments'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
