<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Mail\UserRegisteredMail;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\VoucherController;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            //code...
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'username' => ['required', 'string', 'max:255'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->first_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            if($user) {
                $voucher = Voucher::create([
                    'voucher_code' => VoucherController::generate(5),
                    'user_id' => $user->id,
                ]);

                Mail::to($user->email)->send(new UserRegisteredMail($voucher));

                // return $response = [
                //     'data' => $user,
                //     'message' => 'Successfully Registered',
                //     'status' => 200,
                // ];
            }

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
            
        } catch (\Throwable $th) {
            //throw $th;
            
            // return $response = [
            //     // 'data' => $user,
            //     'message' => $th->getMessage(),
            //     'status' => 500,
            // ];
                // dd($th->getMessage());
            return back();
        }
    }
}
