<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::getAllOrderByCreated_at();
        
        $categories = Category::getAll();
        return view('post.index', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::getAll();
        return view('post.create', [
            'categories' => $categories
        ]);
    }
    
    public function upload(Request $request)
    {
        $image_base64 = explode(";base64,", $request);
        
        $image = base64_decode($image_base64[1]); // 画像データとして取り出す
        $path = uniqid() . '.png'; // 保存に使うファイル名
        $save_path = 'public/'.$path;
        
        Storage::put($save_path, $image);
        return response()->json(['src'=>$path]); // JSONでレスポンスを返す
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $validator = Validator::make($request->all(), [
            'main_image'=>'required',
            'title' => 'required | max:30',
            'body' => 'required',
            'category_ids' => 'required',
        ]);
        // バリデーション:エラー
        if ($validator->fails()) {
            return redirect()
            ->route('post.create')
            ->withInput()
            ->withErrors($validator);
        }
        $data = $request->merge(['user_id' => Auth::user()->id])->all();
        
        $result = Post::create($data);
        //dd($result);
        $result->categories()->sync($request->get('category_ids', []));
        return redirect()->route('post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Post $post)
    {
        return view('post.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Post $post)
    {
        $categories = Category::getAll();
        return view('post.edit', [
            'post' => $post,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Post $post)
    {
        //バリデーション
        $validator = Validator::make($request->all(), [
            'main_image'=>'required',
            'title' => 'required | max:30',
            'body' => 'required',
            'category_ids' => 'required',
        ]);
        //バリデーション:エラー
        if ($validator->fails()) {
            return redirect()
            ->route('post.edit', $post->id)
            ->withInput()
            ->withErrors($validator);
        }
        
        $result = $post->update($request->all());
        $post->categories()->sync($request->get('category_ids', []));
        return redirect()->route('post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Post $post)
    {
        $result = $post->delete();
        return redirect()->route('post.index');
    }
    
    public function mydata()
    {
        // Userモデルに定義した関数を実行する．
        $user = User::find(Auth::user()->id);
        $posts = $user->myposts;
        return view('post.mypage', [
            'posts' => $posts,
            'user' => $user,
        ]);
    }
    
    public function ranking()
    {
        $posts = Post::ranking();
        return view('post.ranking', [
            'posts' => $posts,
        ]);
    }
    
    public function search(Request $request)
    {
        $posts = Post::getAllOrderByCreated_at();
        $search = $request->input('search');
        
        $query = Post::query();

        if ($search !== null) {
            $spaceConversion = mb_convert_kana($search, 's');
            $wordArraySearched = preg_split('/[\s,]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);
            foreach($wordArraySearched as $value) {
                $query->where('title', 'like', '%'.$value.'%')->orWhere('body', 'like', '%'.$value.'%');
            }
            $posts = $query->paginate();
        }
        
        $categories = Category::getAll();
        return view('post.search', [
            'posts' => $posts,
            'categories' => $categories,
            'search' => $search,
        ]);
    }
    
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }
}
