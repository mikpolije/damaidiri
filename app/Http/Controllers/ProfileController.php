<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\PsychologistInterface;
use App\Interfaces\PatientInterface;
use App\Interfaces\ProfileInterface;
use App\Interfaces\RegencyInterface;
use App\Interfaces\UserInterface;

class ProfileController extends Controller
{
    private $param;
    private $profile, $user, $patient, $psychologist, $regency;

    public function __construct(ProfileInterface $profile, UserInterface $user, PatientInterface $patient, PsychologistInterface $psychologist, RegencyInterface $regency)
    {
        $this->profile = $profile;
        $this->user = $user;
        $this->patient = $patient;
        $this->psychologist = $psychologist;
        $this->regency = $regency;
    }

    public function my_account(): View
    {
        $this->param['detail_account'] = $this->profile->my_account();

        return view('pages.profile.my-account', $this->param);
    }

    public function update_my_account(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required', 
            'email' => 'required|unique:users,email,'.auth()->user()->id, 
            // 'password' => 'required'
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
        ], [
            'name' => 'Nama Lengkap',
            'email' => 'Email',
            // 'password' => 'Kata Sandi',
        ]);

        // if (!$this->profile->check_password($request->password)) {
        //     return redirect()->back()->withError('Kata sandi yang anda masukkan salah');
        // }

        DB::beginTransaction();
        try {
            $this->profile->my_account_update($request->all());

            DB::commit();
            return redirect()->route('profile.my-account')->with('success', 'Data berhasil diperbarui');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function my_profile(): View
    {
        $this->param['data_regency'] = $this->regency->all();

        return view('pages.profile.my-profile', $this->param);
    }

    public function update_my_profile(Request $request): RedirectResponse
    {
        $request->validate([
            'nin' => 'required|numeric|digits:16|unique:profiles,nin,'.auth()->user()->profile->id,
            'gender' => 'required',
            'address' => 'required',
            'job' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'place_of_birth' => 'required',
            'date_of_birth' => 'required|date|before:today',
            'phone_number' => 'required|numeric|max_digits:12',
            'regency' => 'required',
            // 'password' => 'required'
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
            'digits' => ':attribute harus berjumlah 16 karakter',
            'numeric' => ':attribute harus berupa angka',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berformat jpeg,png,jpg',
            'max' => ':attribute maksimal 2MB',
            'before' => ':attribute harus sebelum tanggal hari ini',
            'date' => ':attribute harus berupa tanggal',
            'max_digits' => ':attribute harus berupa angka',
        ], [
            'nin' => 'Nomor Induk Kependudukan (NIK)',
            'gender' => 'Jenis Kelamin',
            'address' => 'Alamat Lengkap',
            'job' => 'Pekerjaan',
            'photo' => 'Foto',
            'place_of_birth' => 'Tempat Lahir',
            'date_of_birth' => 'Tanggal Lahir',
            'phone_number' => 'Nomor Telepon',
            'regency' => 'Kabupaten / Kota',
            // 'password' => 'Kata Sandi',
        ]);

        // if (!$this->profile->check_password($request->password)) {
        //     return redirect()->back()->withError('Kata sandi yang anda masukkan salah');
        // }
        
        DB::beginTransaction();
        try {
            $date = date('Y-m-d H-i-s');
            $random = Str::random(5);
            $new_file_name = null;

            if ($request->file('photo')) {
                $new_file_name = 'uploads/avatar/avatar-'.Str::slug($request->name).$random.'-'.$date.'.'.$request->photo->extension();
                $request->file('photo')->move('uploads/avatar/', $new_file_name);
            } else {
                $new_file_name = $this->user->detail(auth()->user()->id)->profile->photo;
            }

            $request->merge([
                'photo_rename' => $new_file_name,
                'regency_id' => $request->regency,
                'phone_number' => $request->phone_number
            ]);
            $this->patient->update(auth()->user()->id, $request->all());

            DB::commit();
            return redirect()->route('profile.my-profile')->with('success', 'Data berhasil diperbarui');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function store_my_profile(Request $request): RedirectResponse
    {
        $request->validate([
            'nin' => 'required|numeric|digits:16|unique:profiles,nin',
            'gender' => 'required',
            'address' => 'required',
            'job' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'place_of_birth' => 'required',
            'date_of_birth' => 'required|date|before:today',
            'phone_number' => 'required|numeric|max_digits:12',
            'regency' => 'required',
            // 'password' => 'required'
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
            'digits' => ':attribute harus berjumlah 16 karakter',
            'numeric' => ':attribute harus berupa angka',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berformat jpeg,png,jpg',
            'max' => ':attribute maksimal 2MB',
            'before' => ':attribute harus sebelum tanggal hari ini',
            'date' => ':attribute harus berupa tanggal',
            'max_digits' => ':attribute harus berupa angka',
        ], [
            'nin' => 'Nomor Induk Kependudukan (NIK)',
            'gender' => 'Jenis Kelamin',
            'address' => 'Alamat Lengkap',
            'job' => 'Pekerjaan',
            'photo' => 'Foto',
            'place_of_birth' => 'Tempat Lahir',
            'date_of_birth' => 'Tanggal Lahir',
            'phone_number' => 'Nomor Telepon',
            'regency' => 'Kabupaten / Kota',
            // 'password' => 'Kata Sandi',
        ]);

        // if (!$this->profile->check_password($request->password)) {
        //     return redirect()->back()->withError('Kata sandi yang anda masukkan salah');
        // }

        DB::beginTransaction();
        try {
            
            $date = date('Y-m-d H-i-s');
            $random = Str::random(5);
            $new_file_name = null;

            if ($request->file('photo')) {
                $new_file_name = 'uploads/avatar/avatar-'.Str::slug($request->name).$random.'-'.$date.'.'.$request->photo->extension();
                $request->file('photo')->move('uploads/avatar/', $new_file_name);
            }

            $request->merge([
                'user_id' => auth()->user()->id,
                'photo_rename' => $new_file_name,
                'regency_id' => $request->regency,
                'phone_number' => "62".$request->phone_number
            ]);

            $this->patient->store($request->all());

            DB::commit();
            return redirect()->route('profile.my-profile')->with('success', 'Data berhasil ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function my_password(): View
    {
        return view('pages.profile.my-password');
    }

    public function update_my_password(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required', 
            'new_password' => 'required|min:8|required_with:new_password_confirm|same:new_password_confirm',
            'new_password_confirm' => 'required'
        ], [
            'required' => ':attribute harus diisi',
            'min' => ':attribute minimal :min karakter',
            'same' => ':attribute tidak sama',
        ], [
            'password' => 'Kata Sandi Saat Ini',
            'new_password' => 'Kata Sandi Baru',
            'new_password_confirm' => 'Konfirmasi Kata Sandi Baru',
        ]);

        if (!$this->profile->check_password($request->password)) {
            return redirect()->back()->withError('Kata sandi yang anda masukkan salah');
        }

        DB::beginTransaction();
        try {
            $this->profile->my_password_update($request->all());

            DB::commit();
            return redirect()->route('profile.my-password')->with('success', 'Data berhasil diperbarui');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function my_profile_psychologist(): View
    {
        return view('pages.profile.my-profile-psychologist');
    }

    public function update_my_profile_psychologist(Request $request): RedirectResponse
    {
        $request->validate([
            'nin' => 'required|numeric|digits:16|unique:psychologists,nin,'.auth()->user()->psychologist->id,
            'gender' => 'required',
            'address' => 'required',
            'password' => 'required',
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
            'digits' => ':attribute harus berjumlah 16 karakter',
            'numeric' => ':attribute harus berupa angka',
        ], [
            'nin' => 'Nomor Induk Kependudukan (NIK)',
            'gender' => 'Jenis Kelamin',
            'address' => 'Alamat Lengkap',
            'password' => 'Kata Sandi',
        ]);

        if (!$this->profile->check_password($request->password)) {
            return redirect()->back()->withError('Kata sandi yang anda masukkan salah');
        }
        
        DB::beginTransaction();
        try {
            $this->psychologist->update(auth()->user()->id, $request->all());

            DB::commit();
            return redirect()->route('profile.my-profile-psychologist')->with('success', 'Data berhasil diperbarui');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function store_my_profile_psychologist(Request $request): RedirectResponse
    {
        $request->validate([
            'nin' => 'required|numeric|digits:16|unique:psychologists,nin',
            'gender' => 'required',
            'address' => 'required',
            'password' => 'required'
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
            'digits' => ':attribute harus berjumlah 16 karakter',
            'numeric' => ':attribute harus berupa angka',
        ], [
            'nin' => 'Nomor Induk Kependudukan (NIK)',
            'gender' => 'Jenis Kelamin',
            'address' => 'Alamat Lengkap',
            'password' => 'Kata Sandi',
        ]);

        if (!$this->profile->check_password($request->password)) {
            return redirect()->back()->withError('Kata sandi yang anda masukkan salah');
        }

        DB::beginTransaction();
        try {
            $request->merge(['user_id' => auth()->user()->id]);
            $this->psychologist->store($request->all());

            DB::commit();
            return redirect()->route('profile.my-profile-psychologist')->with('success', 'Data berhasil ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
