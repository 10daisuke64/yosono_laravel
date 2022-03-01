<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\Post;
use App\Models\User;
use Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
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
    
    public function upload(Request $request)
    {
        $image_base64 = explode(";base64,", $request);
        
        $image = base64_decode($image_base64[1]); // 画像データとして取り出す
        $path = uniqid() . '.png'; // 保存に使うファイル名
        $save_path = 'public/profiles/'.$path;
        
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $posts = User::find($id)->myposts;
        
        //dd($user);
        
        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //バリデーション
        $validator = Validator::make($request->all(), [
            'profile_image'=>'required',
            'profile_name' => 'max:30',
            'profile_text' => 'max:140',
        ]);
        //バリデーション:エラー
        if ($validator->fails()) {
            return redirect()
            ->route('profile.edit', $user->id)
            ->withInput()
            ->withErrors($validator);
        }
        
        $result = $user->update([
            'profile_image' => $request->profile_image,
            'profile_name' => $request->profile_name,
            'profile_text' => $request->profile_text,
        ]);
        
        return redirect()->route('post.mypage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
