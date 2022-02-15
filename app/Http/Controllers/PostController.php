<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Auth;
use Image;

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
        return view('post.index', [
            'posts' => $posts
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
            'main_image'=>'image',
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
        
        // 画像のアップロード
        if($request->hasFile('main_image')){
            // 画像のリサイズ
            $file = $request->file('main_image');
            $path = $file->hashName('public');

            $image = \Image::make($file);
            $image->orientate();
            $image->resize(1280, null,
                function ($constraint) {
                    // 縦横比を保持
                    $constraint->aspectRatio();
                    // 小さい画像は大きくしない
                    $constraint->upsize();
                }
            );
            \Storage::put($path, (string) $image->encode());
            $path = explode('/', $path);
        }else{
            $path = null;
        }
        $data['main_image'] = $path[1];
        
        //dd($data);
        
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
    public function show($id)
    {
        $post = Post::find($id);
        return view('post.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::getAll();
        $post = Post::find($id);
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
    public function update(Request $request, $id)
    {
        //バリデーション
        $validator = Validator::make($request->all(), [
            'title' => 'required | max:191',
            'body' => 'required',
        ]);
        //バリデーション:エラー
        if ($validator->fails()) {
            return redirect()
            ->route('post.edit', $id)
            ->withInput()
            ->withErrors($validator);
        }
        //データ更新処理
        $result = Post::find($id);
        $result->update($request->all());
        $result->categories()->sync($request->get('category_ids', []));
        return redirect()->route('post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Post::find($id)->delete();
        return redirect()->route('post.index');
    }
    
    public function mydata()
    {
        // Userモデルに定義した関数を実行する．
        $posts = User::find(Auth::user()->id)->myposts;
        return view('post.index', [
            'posts' => $posts
        ]);
    }
}
