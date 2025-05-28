<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use App\Interfaces\ProfileInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\PatientInterface;

class ProfileController extends Controller
{
    private $param;
    private $profile, $user, $patient;

    public function __construct(ProfileInterface $profile, UserInterface $user, PatientInterface $patient)
    {
        $this->profile = $profile;
        $this->user = $user;
        $this->patient = $patient;
    }

    public function update_account(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required', 
                'email' => 'required|unique:users,email,'.auth()->user()->id, 
                'password' => 'required'
            ], [
                'required' => ':attribute harus diisi',
                'unique' => ':attribute sudah ada',
            ], [
                'name' => 'Nama Lengkap',
                'email' => 'Email',
                'password' => 'Kata Sandi',
            ]);

            if ($this->profile->check_password($request->password)) {
                $this->profile->my_account_update($request->all());
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Validasi gagal.',
                    'data' => 'Kata sandi lama tidak sesuai.',
                ], 401);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil.',
                'data' => $this->user->detail(auth()->user()->id),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $firstError = array_values($e->errors())[0][0];
            return response()->json([
                'status' => 'failed',
                'message' => 'Validasi gagal. '.$firstError,
                'data' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan pada sistem.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function store_profile(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'nin' => 'required|numeric|digits:16|unique:profiles,nin',
                'gender' => 'required',
                'address' => 'required',
                'job' => 'required',
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ];
            $user = auth()->user();
            if ($user->google_id == null) {
                $rules['password'] = 'required';
            }
            $request->validate($rules, [
                'required' => ':attribute harus diisi',
                'unique' => ':attribute sudah ada',
                'digits' => ':attribute harus berjumlah 16 karakter',
                'numeric' => ':attribute harus berupa angka',
                'image' => ':attribute harus berupa gambar',
                'mimes' => ':attribute harus berformat jpeg,png,jpg',
                'max' => ':attribute maksimal 2MB',
            ], [
                'nin' => 'Nomor Induk Kependudukan (NIK)',
                'gender' => 'Jenis Kelamin',
                'address' => 'Alamat Lengkap',
                'job' => 'Pekerjaan',
                'photo' => 'Foto',
            ]);
    
            if ($user->google_id == null) {
                if (!$this->profile->check_password($request->password)) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Validasi gagal.',
                        'data' => 'Kata sandi lama tidak sesuai.',
                    ], 401);
                }
            }
            
            $date = date('Y-m-d H-i-s');
            $random = Str::random(5);
            $new_file_name = null;

            if ($request->file('photo')) {
                $new_file_name = 'uploads/avatar/avatar-'.Str::slug($request->name).$random.'-'.$date.'.'.$request->photo->extension();
                $request->file('photo')->move('uploads/avatar/', $new_file_name);
            }

            $request->merge([
                'user_id' => auth()->user()->id,
                'photo_rename' => $new_file_name
            ]);

            $this->patient->store($request->all());

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Akun berhasil diperbarui.',
                'data' => $this->user->detail(auth()->user()->id),
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $firstError = array_values($e->errors())[0][0];
            return response()->json([
                'status' => 'failed',
                'message' => 'Validasi gagal. '.$firstError,
                'data' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan pada sistem.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function update_profile(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $rules = [
                'nin' => 'required|numeric|digits:16|unique:profiles,nin,'.auth()->user()->profile->id,
                'gender' => 'required',
                'address' => 'required',
                'job' => 'required',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ];
            if ($user->google_id == null) {
                $rules['password'] = 'required';
            }
            $request->validate($rules, [
                'required' => ':attribute harus diisi',
                'unique' => ':attribute sudah ada',
                'digits' => ':attribute harus berjumlah 16 karakter',
                'numeric' => ':attribute harus berupa angka',
                'image' => ':attribute harus berupa gambar',
                'mimes' => ':attribute harus berformat jpeg,png,jpg',
                'max' => ':attribute maksimal 2MB',
            ], [
                'nin' => 'Nomor Induk Kependudukan (NIK)',
                'gender' => 'Jenis Kelamin',
                'address' => 'Alamat Lengkap',
                'job' => 'Pekerjaan',
                'photo' => 'Foto',
                'password' => 'Kata Sandi',
            ]);
    
            if ($user->google_id == null) {
                if (!$this->profile->check_password($request->password)) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Validasi gagal.',
                        'data' => 'Kata sandi lama tidak sesuai.',
                    ], 401);
                }
            }
            
            $date = date('Y-m-d H-i-s');
            $random = Str::random(5);
            $new_file_name = null;

            if ($request->file('photo')) {
                $new_file_name = 'uploads/avatar/avatar-'.Str::slug($request->name).$random.'-'.$date.'.'.$request->photo->extension();
                $request->file('photo')->move('uploads/avatar/', $new_file_name);
            } else {
                $new_file_name = $this->user->detail(auth()->user()->id)->profile->photo;
            }

            $request->merge(['photo_rename' => $new_file_name]);
            $this->patient->update(auth()->user()->id, $request->all());

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Profile berhasil diperbarui',
                'data' => $this->user->detail(auth()->user()->id),
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $firstError = array_values($e->errors())[0][0];
            return response()->json([
                'status' => 'failed',
                'message' => 'Validasi gagal. '.$firstError,
                'data' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan pada sistem.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function update_password(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'password' => 'required', 
                'new_password' => 'required|min:8|required_with:new_password_confirm|same:new_password_confirm',
                'new_password_confirm' => 'required'
            ], [
                'required' => ':attribute harus diisi',
                'min' => ':attribute minimal :min karakter',
                'same' => ':attribute tidak sesuai',
            ], [
                'password' => 'Kata Sandi Saat Ini',
                'new_password' => 'Kata Sandi Baru',
                'new_password_confirm' => 'Konfirmasi Kata Sandi Baru',
            ]);

            if ($this->profile->check_password($request->password)) {
                $this->profile->my_password_update($request->all());
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validasi gagal.',
                    'data' => 'Kata sandi lama tidak sesuai.',
                ], 401);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil.',
                'data' => 'Akun berhasil diperbarui.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $firstError = array_values($e->errors())[0][0];
            return response()->json([
                'status' => 'failed',
                'message' => 'Validasi gagal. '.$firstError,
                'data' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan pada sistem.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    
}
