<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Auth;

class CategoryController extends Controller
{
    public function index($id)
    {
        $query = Post::query();
        $query->whereHas('categories', function ($query) use ($id) {
            $query->where('category_post.category_id', $id);
        });
        $posts = $query->orderBy('created_at', 'desc')->get();
        
        $category = Category::find($id);
        
        return view('post.category', [
            'posts' => $posts,
            'category' => $category,
        ]);
    }
}
