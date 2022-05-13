<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\Admin;
use Image;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function updateAdminPassword(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

            // Check if Current Password Entered by Admin is Correct
            if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
                if($data['confirm_password']==$data['new_password']){
                    Admin::where('id',Auth::guard('admin')->user()->id)->update(['password'=>bcrypt($data['new_password'])]);
                    return redirect()->back()->with('success_message', 'Password has been updated successfully!');
                } else{
                    return redirect()->back()->with('error_message', 'New Password And Confirm Password Does Not Match!');
                } 
            }else {
                return redirect()->back()->with('error_message', 'Your Current Password Is Incorrect!');
            }
        }

        //echo "<pre>"; print_r(Auth::guard('admin')->user()); die;
        $adminDetails = Admin::where('email', Auth::guard('admin')->user()->email)->first()->toArray();
        return view('admin.settings.update_admin_password')->with(compact('adminDetails'));
    }

    public function checkCurrentPassword(Request $request)
    {
        $data = $request->all();
        //echo "<pre>"; print_r($data); die;
        if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
            return "true";
        }else{
            return "false"; 
        }
    }

    public function updateAdminDetails(Request $request)
    {   
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $rules = [
                'admin_name' => 'required|regex:/^[\pL\s\-]+$/u',
                'admin_mobile' => 'required|numeric'
            ];
            $customMessages = [
                //Add Custom Messages Here
                'admin_name.required' => 'Name is Required',
                'admin_name.regex' => 'Valid Name is Required',
                'admin_mobile.required' => 'Mobile Number is Required',
                'admin_mobile.numeric' => 'Valid Mobile Number is Required',
            ];           

            $this->validate($request, $rules, $customMessages);

            //Upload Admin Photo
            if($request->hasFile('admin_image')){
                $image_tmp = $request->file('admin_image');
                if($image_tmp->isValid()){
                    //Get Image Extention
                    $extention = $image_tmp->getClientOriginalExtension();
                    //Generate New Image 
                    $imageName = rand(111, 99999).'.'.$extention;
                    $imagePath = 'admin/images/photos/'.$imageName;
                    Image::make($image_tmp)->save($imagePath);
                }
            } elseif(!empty($data['current_admin_image'])){
                $imageName = $data['current_admin_image'];
            }else{
                $imageName = "";
            }
            //Update Admin Details
            Admin::where('id', Auth::guard('admin')->user()->id)->update(['name'=>$data['admin_name'],'mobile'=>$data['admin_mobile'],'image'=>$imageName]);
            return redirect()->back()->with('success_message', 'Admin Details Updated Successfully');
        }

        return view('admin.settings.update_admin_details');
    }

    public function login(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required',
            ];

            $customMessages = [
                //Add Custom Messages Here
                'email.required' => 'Email is Required',
                'email.email' => 'Valid Email is Required',
                'password.required' => 'Password is Required',
            ];

            $this->validate($request, $rules, $customMessages);

            if(Auth::guard('admin')->attempt(['email'=>$data['email'], 'password'=>$data['password'], 'status'=>1])){
                return redirect('admin/dashboard');
            }else{
                return redirect()->back()->with('error_message', 'Invalid Email or Password');
            }
        }
        //echo $password = Hash::make('shahidullahpiaro'); die;
        return view('admin.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }
}
