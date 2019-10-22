<?php

namespace App\Http\Controllers\Site;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function listNews() {
        $listNews = News::all();
        return view("site.newsList", ['listNews' => $listNews]);
    }

    public function detailNews($newsId) {
        $news = News::where(['id' => $newsId])->first();
        return view("site.newsDetail", ['news' => $news]);
    }
}
