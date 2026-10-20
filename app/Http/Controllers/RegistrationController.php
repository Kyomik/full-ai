<?php
namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function create() { return view('auth.agency-register'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'agency_name'=>'required|string|max:150','agency_type'=>'required|string|max:100','agency_email'=>'required|email|max:150|unique:agencies,email',
            'phone'=>'nullable|string|max:30','address'=>'required|string|max:1000','name'=>'required|string|max:100',
            'email'=>'required|email|max:150|unique:users,email','password'=>'required|string|min:10|confirmed',
        ]);
        DB::transaction(function () use ($data) {
            $agency = Agency::create(['name'=>$data['agency_name'],'type'=>$data['agency_type'],'email'=>$data['agency_email'],'phone'=>$data['phone'] ?? null,'address'=>$data['address'],'status'=>'active']);
            User::create(['agency_id'=>$agency->id,'name'=>$data['name'],'email'=>$data['email'],'password'=>$data['password'],'role'=>'agency_admin','status'=>'active']);
        });
        return redirect('/admin/login')->with('status','Akun instansi berhasil dibuat. Silakan masuk untuk mengajukan acara.');
    }
}
