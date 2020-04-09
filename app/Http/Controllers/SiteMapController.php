<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\User\Category;
use App\Model\User\Post;
use App\Model\User\Tag;
use App\Model\User\Subcategory;
use Illuminate\Http\Request;
class SitemapController extends Controller
{
    public function index() {
      $articles = Post::all()->first();
      $categories = Category::all()->first();
      $tags = Tag::all()->first();
      $subcategories = Subcategory::all()->first();
      return response()->view('sitemap.index', [
          'article' => $articles,
          'category' => $categories,
          'subcategory' => $subcategories,
          'tag' => $tags,
      ])->header('Content-Type', 'text/xml');
    }

    public function articles() {
       $article = Post::latest()->get();
       return response()->view('sitemap.article', [
           'article' => $article,
       ])->header('Content-Type', 'text/xml');
   }

   public function categories() {
       $category = Category::all();
       return response()->view('sitemap.category', [
           'category' => $category,
       ])->header('Content-Type', 'text/xml');
   }

   public function subcategories() {
       $subcategory = Subcategory::all();
       return response()->view('sitemap.subcategory', [
           'subcategory' => $subcategory,
       ])->header('Content-Type', 'text/xml');
   }

   public function tags() {
       $tag = Tag::all();
       return response()->view('sitemap.tag', [
           'tag' => $tag,
       ])->header('Content-Type', 'text/xml');
   }

}