<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Http\Request;
use Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function edit(User $user)
    {   
        $user = Auth::user();
        return view('userManagement.edit', compact('user'));
    }

    public function update(Request $request)
    { 
        $user = Auth::user();
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'file_avantar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file_signarure' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        

        $user->name = $request->name;
        if($request->file_avantar){
            
            $path = config('constants.avantarUpload');
            if($user->avantar != 'admin.png'){
                Storage::delete($path . $user->avantar);
                Storage::delete($path .'thumbnail/'. $user->avantar);
            }
            $filename = saveImage($request->file_avantar, $path, true);
            $user->avantar = $filename;
        }

        if($request->file_signarure){
            $path = config('constants.signarureUpload');
            if($user->signarure != 'nopic.png'){
                Storage::delete($path . $user->signarure);
            }
            $filename = saveImage($request->file_signarure, $path);
            $user->signarure = $filename;
        }
        if($request->image_signarure){
            
            $path = config('constants.signarureUpload');
            if (!File::exists(storage_path("app".$path)))
            {
                File::makeDirectory(storage_path("app".$path), 0777, true, true);
            }
            if (!File::exists(storage_path("app".$path."thumbnail/")))
            {
                File::makeDirectory(storage_path("app".$path."thumbnail/"), 0777, true, true);
            }
            if($user->signarure != 'nopic.png'){
                Storage::delete($path . $user->signarure);
            }
            $image = $request->image_signarure;  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $filename = Str::random(10).time().'.'.'png';
            \File::put(storage_path('app'.$path) . $filename, base64_decode($image));
            $user->signarure = $filename;
        }

        $user->save();

        return back();
    }
}