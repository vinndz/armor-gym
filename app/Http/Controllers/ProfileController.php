<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexAdmin()
    {
        $user = User::where('id', auth()->user()->id)->first();
        return view('admin.profile', compact('user'));
    }

    public function updateAdmin(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'name'  => 'required',
            'username' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
        ]);
    
        $user = User::findOrFail($id);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        }
    
        $user->fill($validatedData);
        $user->save();
    
        if ($user) {
            Alert::success('Success', 'Successfully updated admin');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated admin');
            return redirect()->back();
        }
    }

    public function indexInstructor()
    {
        $user = User::where('id', auth()->user()->id)->first();
        return view('instructor.profile', compact('user'));
    }

    public function updateProfile(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
        ]);

        $user = User::findOrFail($id);

        // Enkripsi password baru jika ada
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        }

        $user->fill($validatedData);
        $user->save();

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully updated user profile'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user profile'
            ], 500);
        }
    }

    public function uploadProfilePictureInstructor(Request $request, $id)
    {
        $validatedData = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $user = User::findOrFail($id);
        $imageUser = $user->image;

        if ($request->hasFile("image")) {
            if ($imageUser) {
                Storage::delete($imageUser);
            }
            $imagePath = $request->file("image")->store("img");
        } else {
            $imagePath = $imageUser;
        }

        $validatedData['image'] = $imagePath;
        $user->update($validatedData);

        if ($user) {
            Alert::success('Success', 'Successfully updated profile picture instructor');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated profile picture instructor');
            return redirect()->back();
        }

    }

    public function uploadProfilePictureAdmin(Request $request, $id)
    {
        $validatedData = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $user = User::findOrFail($id);


        $user = User::findOrFail($id);
        $imageUser = $user->image;

        if ($request->hasFile("image")) {
            if ($imageUser) {
                Storage::delete($imageUser);
            }
            $imagePath = $request->file("image")->store("img");
        } else {
            $imagePath = $imageUser;
        }

        $validatedData['image'] = $imagePath;
        $user->update($validatedData);

        if ($user) {
            Alert::success('Success', 'Successfully updated profile picture admin');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated profile picture admin');
            return redirect()->back();
        }

    }

    public function uploadProfilePictureMember(Request $request, $id)
    {
        $validatedData = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $user = User::findOrFail($id);

        $imageUser = $user->image;

        if ($request->hasFile("image")) {
            if ($imageUser) {
                Storage::delete($imageUser);
            }
            $imagePath = $request->file("image")->store("img");
        } else {
            $imagePath = $imageUser;
        }

        $validatedData['image'] = $imagePath;
        $user->update($validatedData);

        if ($user) {
            Alert::success('Success', 'Successfully updated profile picture member');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated profile picture member');
            return redirect()->back();
        }

    }

    public function uploadProfilePictureOwner(Request $request, $id)
    {
        $validatedData = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $user = User::findOrFail($id);


        $imageUser = $user->image;

        if ($request->hasFile("image")) {
            if ($imageUser) {
                Storage::delete($imageUser);
            }
            $imagePath = $request->file("image")->store("img");
        } else {
            $imagePath = $imageUser;
        }

        $validatedData['image'] = $imagePath;
        $user->update($validatedData);

        if ($user) {
            Alert::success('Success', 'Successfully updated profile picture owner');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated profile picture owner');
            return redirect()->back();
        }

    }

    public function uploadProfilePictureGuest(Request $request, $id)
    {
        $validatedData = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $user = User::findOrFail($id);
        $imageUser = $user->image;

        if ($request->hasFile("image")) {
            if ($imageUser) {
                Storage::delete($imageUser);
            }
            $imagePath = $request->file("image")->store("img");
        } else {
            $imagePath = $imageUser;
        }

        $validatedData['image'] = $imagePath;
        $user->update($validatedData);

        if ($user) {
            Alert::success('Success', 'Successfully updated profile picture guest');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated profile picture guest');
            return redirect()->back();
        }

    }


    public function indexMember()
    {
        $user = User::where('id', auth()->user()->id)->first();
        return view('member.profile', compact('user'));
    }

    public function updateMember(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'name'  => 'required',
            'username' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
        ]);
    
        $user = User::findOrFail($id);


        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        }
    
        $user->fill($validatedData);
        $user->save();
    
        if ($user) {
            Alert::success('Success', 'Successfully updated member');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated member');
            return redirect()->back();
        }
    }

    public function indexOwner()
    {
        $user = User::where('id', auth()->user()->id)->first();
        return view('owner.profile', compact('user'));
    }

    public function updateOwner(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'name'  => 'required',
            'username' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
        ]);
    
        $user = User::findOrFail($id);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        }
    
        $user->fill($validatedData);
        $user->save();
    
        if ($user) {
            Alert::success('Success', 'Successfully updated owner');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated owner');
            return redirect()->back();
        }
    }

    public function indexGuest()
    {
        $user = User::where('id', auth()->user()->id)->first();
        return view('guest.profile', compact('user'));
    }

    public function updateGuest(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'name'  => 'required',
            'username' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
        ]);
    
        $user = User::findOrFail($id);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        }
    
        $user->fill($validatedData);
        $user->save();
    
        if ($user) {
            Alert::success('Success', 'Successfully updated guest');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated guest');
            return redirect()->back();
        }
    }
}
