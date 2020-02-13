<?php

namespace App\Http\Controllers;

use App\Http\Resources\Article as ArticleResource;
use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller{

   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index(){

      // Get all articles.
      $articles = Article::paginate(10);

      // Return all this articles as a collection.
      return ArticleResource::collection($articles);
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create(){
      //
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request){

      // Get a single article.
      $article = $request->isMethod("put") ? Article::findOrFail($request->id) : new Article;

      $article->id = $request->input("id");
      $article->title = $request->input("title");
      $article->body = $request->input("body");

      if($article->save()){
         return new ArticleResource($article);
      }
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id){

      // Get a single article.
      $article = Article::findOrFail($id);

      // Return the article as a resource.
      return new ArticleResource($article);
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id){
      //
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id){

   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id){

      // Get a single article.
      $article = Article::findOrFail($id);

      // If the delete function return true then return
      // the article as a resource.
      if($article->delete()){
         return new ArticleResource($article);
      }
   }
}
