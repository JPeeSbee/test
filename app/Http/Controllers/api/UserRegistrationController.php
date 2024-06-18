<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\UserRegisteredMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserRegistrationController extends Controller
{

    public function index() {

    }

    public function store(Request $request) {
        try {
            //code...
            $validated = $request->validate([
                'username' => 'required',
                'first_name' => 'required',
                'email' => 'email:rfc,dns'
            ]);

            $user = new User;
            $user->name             =   $request->first_name;
            $user->username         =   $request->username;
            $user->password         =   Hash::make($request->username);
            $user->email            =   $request->email;
            $user->voucher_code     =   User::voucher(5);
            
            if($user->save()) {
                Mail::to($request->email)->send(new UserRegisteredMail($user));
                // $mail = (new UserRegisteredMail($user));
                // Mail::to($request->email)
                //     ->queue($mail);
            }

            return $response = [
                'data' => $user,
                'message' => 'Successfully Registered',
                'status' => 200,
            ];

        } catch (\Throwable $th) {
            //throw $th;

            return $response = [
                'data' => $user,
                'message' => $th->getMessage(),
                'status' => 500,
            ];
        }
    }
}
