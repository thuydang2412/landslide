<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use DB;

class NewsController extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('auth');
    }


    public function index(Request $request)
    {
        return view('admin.news.index', []);
    }

    public function create(Request $request)
    {
        return view('admin.news.add', [
            []
        ]);
    }

    public function edit(Request $request, $id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.add', [
            'model' => $news]);
    }

    public function show(Request $request, $id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.show', [
            'model' => $news]);
    }

    public function grid(Request $request)
    {
        $len = $_GET['length'];
        $start = $_GET['start'];

        $select = "SELECT id, title, description, created_at ,1,2 ";
        $presql = " FROM news a ";
        if ($_GET['search']['value']) {
            $presql .= " WHERE title LIKE '%" . $_GET['search']['value'] . "%' ";
        }

        $presql .= "  ";

        $sql = $select . $presql . " LIMIT " . $start . "," . $len;


        $qcount = DB::select("SELECT COUNT(a.id) c" . $presql);
        //print_r($qcount);
        $count = $qcount[0]->c;

        $results = DB::select($sql);
        $ret = [];
        foreach ($results as $row) {
            $r = [];
            foreach ($row as $value) {
                $r[] = $value;
            }
            $ret[] = $r;
        }

        $ret['data'] = $ret;
        $ret['recordsTotal'] = $count;
        $ret['iTotalDisplayRecords'] = $count;

        $ret['recordsFiltered'] = count($ret);
        $ret['draw'] = $_GET['draw'];

        echo json_encode($ret);

    }


    public function update(Request $request)
    {
        //
        /*$this->validate($request, [
            'name' => 'required|max:255',
        ]);*/
        $news = null;
        if ($request->id > 0) {
            $news = News::findOrFail($request->id);
        } else {
            $news = new News;
        }


        $news->id = $request->id ?: 0;


        $news->cat_id = $request->cat_id;


        $news->title = $request->title;


        $news->description = $request->description;


        $news->thumbnail = $request->thumbnail;


        $news->content = $request->content;


        $news->sources = $request->sources;

        $news->link = $request->link;


        $news->created_at = $request->created_at;


        $news->updated_at = $request->updated_at;

        //$news->user_id = $request->user()->id;
        $news->save();

        return redirect('/admin/news');

    }

    public function store(Request $request)
    {
        return $this->update($request);
    }

    public function destroy(Request $request, $id)
    {

        $news = News::findOrFail($id);

        $news->delete();
        return "OK";

    }


}