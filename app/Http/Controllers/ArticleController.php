<?php

namespace App\Http\Controllers;

use App\Article;
use App\type;
use Illuminate\Http\Request;
use Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();
        $types = Type::all();
        return view('article.index', ['articles'=>$articles, 'types'=>$types]);
    }

    public function storeAjax(Request $request) {


        $article = new Article;

        $input = [
            'articleTitle' => $request->articleTitle,
            'articleDescription' => $request->articleDescription,
            'articleType' => $request->articleType
        ];
        $rules = [
            'articleTitle' => 'required|min:3',
            'articleDescription' => 'required|min:3',
            'articleType' => 'numeric'
        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $article->title = $request->articleTitle;
            $article->description = $request->articleDescription;
            $article->type_id = $request->articleType;

            $article->save();

            $success = [
                'success' => 'Article added successfully',
                'articleID' => $article->id,
                'articleTitle' => $article->title,
                'articleDescription' => $article->description,
                'articleType' => $article->articleType->title
            ];

            $success_json = response()->json($success);

            return $success_json;
        }

        $errors = [
            'error' => $validator->messages()->get('*')
        ];

        $errors_json = response()->json($errors);

        return $errors_json;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    public function showAjax(Article $article) {

        $success = [
            'success' => 'Client recieved successfully',
            'articleId' => $article->id,
            'articleTitle' => $article->title,
            'articleDescription' => $article->description,
            'articleType' => $article->articleType->title
        ];

        $success_json = response()->json($success);

        return $success_json;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }
    public function editAjax(Article $article) {
        $success = [
            'success' => 'Article recieved successfully',
            'articleid' => $article->id,
            'articleTitle' => $article->title,
            'articleDescription' => $article->description,
            'articleType' => $article->type_id
        ];

        $success_json = response()->json($success);

        return $success_json;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
    }
    public function updateAjax(Request $request, Article $article) {
        $input = [
            'articleTitle' => $request->articleTitle,
            'articleDescription' => $request->articleDescription,
            'articleType' => $request->articleType
        ];
        $rules = [
            'articleTitle' => 'required|min:3',
            'articleDescription' => 'required|min:3',
            'articleType' => 'numeric'
        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $article->title = $request->articleTitle;
            $article->description = $request->articleDescription;
            $article->type_id = $request->articleType;

            $article->save();

            $success = [
                'success' => 'Article updated successfully',
                'articleid' => $article->id,
                'articleTitle' => $article->title,
                'articleDescription' => $article->description,
                'articleType' => $article->articleType->title
            ];

            $success_json = response()->json($success);

            return $success_json;
        }

        $errors = [
            'error' => $validator->messages()->get('*')
        ];

        $errors_json = response()->json($errors);

        return $errors_json;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }

    public function destroySelected(Request $request) {


        $checkedArticles = $request->checkedArticles; // visus id

        $messages = array();

        //error 0
        //success 1

        //error - 'danger'
        //success - 'success'

        $errorsuccess = array();

        foreach($checkedArticles as $articleId) {
            //kaip pasirinkti kompanija pagal Id?
            // $company = Company::where("id", $companyId);
            $article = Article::find($articleId);
            // if($clients_count > 0) {
            //    $errorsuccess[] = 'danger';
            //    $messages[] = "Company ".$companyId."cannot be deleted because it has clients";

            // } else {
                $deleteAction = $article->delete();
                if($deleteAction) {
                    $errorsuccess[] = 'success';
                    $messages[] = "Company ".$articleId." deleted successfully";
                } else {
                    $messages[] = "Something went wrong";
                    $errorsuccess[] = 'danger';
                }
        }


        $success = [
            'success' => $checkedArticles,
            'messages' => $messages,
            'errorsuccess' => $errorsuccess
        ];

        $success_json = response()->json($success);

        return $success_json;

    }

    public function searchAjax(Request $request) {

        $searchValue = $request->searchField;


        $articles = Article::query()
            ->where('title', 'like', "%{$searchValue}%")
            ->orWhere('description', 'like', "%{$searchValue}%")
            ->get();
        //eilute yra klientas, kiekvienas stulpelis yra informacija apie klienta

        foreach ($articles as $article) {
            $article['articleTitle'] = $article->articleType->title;
        }


        if($searchValue == '' || count($articles)!= 0) {

            $success = [
                'success' => 'Found '.count($articles),
                'articles' => $articles
            ];

            $success_json = response()->json($success);


            return $success_json; //yra musu sekmes pranesimas
        }

        $error = [
            'error' => 'No results are found'
        ];

        $errors_json = response()->json($error);

        return $errors_json;

    }

    public function filterAjax(Request $request) {

        $type_id = $request->type_id;

        $sortCol = $request->sortCol;
        //Rikiavimo tvarka
        $sortOrder = $request->sortOrder;


        if($type_id == 'all') {
            $articles = Article::orderBy($sortCol, $sortOrder)->get();
        } else {
            $articles = Article::where('type_id', $type_id)->orderBy($sortCol, $sortOrder)->get();
        }

        foreach ($articles as $article) {
            $article['articleTitle'] = $article->articleType->title;
        }

        $articles_count = count($articles);

        if ($articles_count == 0) {
            $error = [
                'error' => 'There are no articles',
            ];

            $error_json = response()->json($error);
            return $error_json;
        }

        $success = [
            'success' => 'Articles filtered successfuly',
            'articles' => $articles
        ];

        $success_json = response()->json($success);

        return $success_json;



    }
}


