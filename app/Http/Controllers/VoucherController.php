<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{

    public function index() {
        $vouchers = Voucher::where('user_id', Auth::user()?->id)->get();

        return view('pages.vouchers.index')->with(compact('vouchers'));
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
