<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Qualification;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'receive_email_notification' => 'required|boolean',
            'receive_sms_notification' => 'required|boolean',
            //'registration_number' => 'required|string',
            //'phone' => 'required|string',
            'gender' => 'required|in:Male,Female,Other',
            'birthday' => 'required|date',
            //'year_of_passed_out' => 'required|integer',
            'address' => 'required|string',
            'pincode' => 'required|string',
            //'school' => 'required|string',
            'city' => 'required|string',
            'qualifications' => 'required|array',
            'state_id' => 'required|integer',
            'aadhaar_number' => 'required|integer|digits:12',
            'linkedin_profile' => 'nullable|url',
            'upload_resume' => 'required|file|mimes:pdf,doc,docx',
            'upload_aadhar' => 'required|file|mimes:pdf,doc,docx',
            'avatar_url' => 'nullable|file|mimes:jpeg,jpg,png,gif',
            'parent_name'=> 'nullable|string|max:191',
            'parent_email'=> 'nullable|email|max:191',
            'parent_aadhar'=> 'nullable|integer|digits:12',
            'parent_occupation'=> 'nullable|string|max:191',
            'residential_address'=> 'nullable|string|max:191'
        ]);

        $upload_aadhar = $upload_resume = $upload_avatar_url = "";
        // Handle the file upload logic
        $file = $request->file('upload_aadhar');
        if ($file)
            $upload_aadhar = $file->store('public'); // Store the file in the 'uploads' directory

        $file = $request->file('upload_resume');
        if ($file)
            $upload_resume = $file->store('public'); // Store the file in the 'uploads' directory

        $file = $request->file('avatar_url');
        if ($file)
            $upload_avatar_url = $file->store('public'); // Store the file in the 'uploads' directory


        // Update user
        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->receive_email_notification = $request->receive_email_notification;
        $user->receive_sms_notification = $request->receive_sms_notification;
        //$user->registration_number = $request->registration_number;
        //$user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->birthday = $request->birthday;
        //$user->year_of_passed_out = $request->year_of_passed_out;
        $user->address = $request->address;
        $user->pincode = $request->pincode;
        //$user->school = $request->school;
        $user->city = $request->city;
        //$user->qualification_id = $request->qualification_id;

        //dd($request->qualifications);
//        foreach ($request->qualifications as $qualification) {
//
//            $qualificationsWithName = array_map(function($qualification) {
//                $qualificationDetail = Qualification::find($qualification['qualification_id']);
//                $qualification['name'] = $qualificationDetail ? $qualificationDetail->name : 'Unknown';
//                return $qualification;
//            }, $qualifications);
//
//        }

        //$qualifications = json_encode($request->qualifications);
        $arr_qualifications = [];
        foreach ($request->qualifications as $qualification)
        {
            $arr_qualifications = json_decode(trim(stripslashes($qualification),'"'), true);
        }


        //dd(trim($qualifications,'"'));
        // Decode the incorrectly encoded JSON string
        //$qualifications = json_decode(trim(stripslashes($qualifications),'"'), true);




        // Encode it properly
        //$qualifications = $qualifications;
//dd($qualifications);

        $user->qualification = $arr_qualifications;


        $user->state_id = $request->state_id;
        $user->aadhaar_number = $request->aadhaar_number;
        $user->linkedin_profile = $request->linkedin_profile;

        if ($upload_resume)
            $user->upload_resume = basename($upload_resume);
        if ($upload_aadhar)
            $user->upload_aadhar = basename($upload_aadhar);

        if ($upload_avatar_url)
            $user->avatar_url = basename($upload_avatar_url);

        $user->save();
//        dd($user);

        return response()->json(['message' => 'User updated successfully', 'user' => new UserResource($user)]);
    }

    public function find(Request $request)
    {
    }

    public function tutors(Request $request)
    {
        $users = User::where('role_id', 7)->get();

        return UserResource::collection($users);
    }
}
