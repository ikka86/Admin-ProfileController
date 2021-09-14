<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Profile;
use Carbon\Carbon;
use App\ProfileHistory;

class ProfileController extends Controller
{
    public function add()
    {
        return view('admin.profile.create');
    }
    
    public function create(Request $request)
    {
        $this->validate($request, Profile::$rules);

        $profile = new Profile;
        $form = $request->all();
  
        //formに画像があれば、保存する
        // if (isset($form['image'])) {
        //   $path = $request->file('image')->store('public/image');
        //   $profile->image_path = basename($path);
        // } else {
        //     $profile->image_path = null;
        // }
  
        unset($form['_token']);
        // unset($form['image']);

        $profile->fill($form);
        $profile->save();
  
        return redirect('admin/profile/create');
    }
    public function index(Request $request)
  {
      $cond_title = $request->cond_title;
      if ($cond_title != '') {
          $posts = Profile::where('title', $cond_title)->get();
      } else {
          $posts = Profile::all();
      }
      return view('admin.profile.index', ['posts' => $posts, 'cond_title' => $cond_title]);
  }
    public function edit(Request $request)
    {
        // Profile Modelからデータを取得する
        $profile = Profile::find($request->id);
  
        return view('admin.profile.edit', ['profile_form' => $profile]);
    }
  
  
    public function update(Request $request)
    {
        // Validationをかける
        $this->validate($request, Profile::$rules);
        // Profile Modelからデータを取得する
        $profile = Profile::find($request->id);
        // 送信されてきたフォームデータを格納する
        $profile_form = $request->all();
        // if (isset($profile_form['image'])) {
        //   $path = $request->file('image')->store('public/image');
        //   $profile->image_path = basename($path);
        //   unset($profile_form['image']);
        // } elseif (0 == strcmp($request->remove, 'true')) {
        //   $profile->image_path = null;
        // }

        unset($profile_form['_token']);
        //画像に関する記述なのでなし
        //unset($profile_form['remove']);

        $history = new ProfileHistory;
        $history->profile_id = $profile->id;
        $history->edited_at = Carbon::now();
        $history->save();
  
        // 該当するデータを上書きして保存する
        $profile->fill($profile_form);
        $profile->save();
  
        return redirect('admin/profile/');
    }
  
    // 以下を追記　　
    public function delete(Request $request)
    {
        // 該当するProfile Modelを取得
        $profile = Profile::find($request->id);
        // 削除する
        $profile->delete();
        return redirect('admin/profile');
    }  
  
  
  }
