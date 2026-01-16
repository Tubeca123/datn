<?php

namespace App\Http\Controllers\account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;
class AccountController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('account.edit_profile', compact("user"));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^(0[1-9][0-9]{8,9})$/',
            'address' => 'nullable|max:255',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ], [
            'name.required' => 'Tên người dùng không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'image.image' => 'File phải là ảnh',
            'image.max' => 'Ảnh không được vượt quá 5MB'
        ]);

        $oldUser = Auth::user();
        $id = $oldUser->id;
        $update = User::find($id);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Xử lý upload image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $ext;
            
            $uploadPath = public_path('uploads/avatars');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            // Xóa ảnh cũ nếu có
            if ($oldUser->image && File::exists(public_path($oldUser->image))) {
                File::delete(public_path($oldUser->image));
            }
            
            $file->move($uploadPath, $fileName);
            $data['image'] = 'uploads/avatars/' . $fileName;
        }

        $update->update($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật hồ sơ thành công"]);
        return redirect()->route('profile');
    }

    public function editPassword()
    {
        $user = Auth::user();
        return view('account.edit_password', compact("user"));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            $request->session()->put("messenge", ["style" => "danger", "msg" => "Mật khẩu cũ không đúng"]);
            return redirect()->back();
        }
        $id = $user->id;
        $update = User::find($id);
        $update->password = Hash::make($request->new_password);
        $update->save();
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật mật khẩu thành công"]);
        return redirect()->route('profile');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
