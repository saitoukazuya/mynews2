<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile;
use App\ProfileHistory;
use Carbon\carbon;

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

        $profile->fill($form);
        $profile->save();
        
        return redirect('admin/profile/create');
    }
    
        public function index(Request $reqest)
    {
        $cont_title = $reqest->cond_title;
        if($cont_title != '') {
            $posts = Profile::where('name', $cont_title)->get();
        }else {
            $posts = Profile::all();
        }
        return view('admin.profile.index', ['posts' => $posts, 'cond_title' => $cont_title]);
        
    }
    
    public function edit(Request $request)
    {
        $profile = Profile::find($request->id);
        if(empty($profile)) {
            abort(404);
        }
        return view('admin.profile.edit', ['profile_form' => $profile]);
        
    }
    
    public function update(Request $request)
    {
        $this->validate($request, Profile::$rules);
        $profile = Profile::find($request->id);
        $profile_form = $request->all();
        unset($profile_form['_token']);
        $profile->fill($profile_form)->save();
        
        $profiles_histories = new ProfileHistory;
        $profiles_histories->profile_id = $profile->id;
        $profiles_histories->edited_at = Carbon::now();
        $profiles_histories->save();
        
        return redirect('admin/profile');
    }
    
    public function delete(Request $request)
    {
        $profile = Profile::find($request->id);
        $profile->delete();
        return redirect('admin/profile/');
    }
    
}
