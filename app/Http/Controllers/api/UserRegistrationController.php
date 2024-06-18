<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Mail\UserRegisteredMail;
use App\Http\Controllers\VoucherController;
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
                'first_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'username' => ['required', 'string', 'max:50'],
                'password' => ['required']
            ]);

            $user = User::create([
                'name'             =>   $request->first_name,
                'username'         =>   $request->username,
                'password'         =>   Hash::make($request->password),
                'email'            =>   $request->email,
                'voucher_code'     =>   VoucherController::generate(5),
            ]);
            
            if($user) {
                $voucher = Voucher::create([
                    'voucher_code' => VoucherController::generate(5),
                    'user_id' => $user->id,
                ]);

                Mail::to($request->email)->send(new UserRegisteredMail($voucher));
                
                return $response = [
                    'data' => $user,
                    'message' => 'Successfully Registered',
                    'status' => 200,
                ];
            }

            return $response = [
                'data' => $user,
                'message' => 'Bad Request',
                'status' => 400,
            ];

        } catch (\Throwable $th) {
            //throw $th;

            return $response = [
                // 'data' => $user,
                'message' => $th->getMessage(),
                'status' => 500,
            ];
        }
    }
}
