<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{

    public function index() {
        $vouchers = Voucher::where('user_id', Auth::user()?->id)->get();

        return view('pages.vouchers.index')->with(compact('vouchers'));
    }
    
    public function store(Request $request) {
        try {
            $request->validate([
                'count' => ['required', 'numeric', 'between:1,10'],
            ]);
            
            $user = User::find(auth()->user()->id);
            $count = $user->voucher()->count();
            
            if(($count + $request->count) > 10) {
                session(['fail' => 'Unsuccessful to generate, vouchers maximum limit is 10 per user!']);
            } else {
                for ($i=0; $i < $request->count ; $i++) { 
                    Voucher::create([
                        'voucher_code' => $this->generate(5),
                        'user_id' => auth()->user()->id,
                    ]);
                }
                session(['success' => $request->count.' Vouchers successfully created!']);
            }

            return redirect()->route('voucher.index');
        } catch (\Throwable $th) {
            //throw $th;
            session(['fail' => $th->getMessage()]);
            return back();
        }
    }

    public function destroy($id) {
        $voucher = Voucher::find($id);
        
        if($voucher->delete())
            session(['success' => 'Successfully Deleted.']);

        return redirect()->route('voucher.index');
    }

    public static function generate($length_of_string) {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        return substr(str_shuffle($str_result), 
            0, $length_of_string);
    }
}
