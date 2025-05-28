<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\PsychologistInterface;
use App\Interfaces\PatientInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\RoleInterface;
use App\Interfaces\BlogInterface;

class UserController extends Controller
{
    private $param;
    private $user, $role, $patient, $psychologist;

    public function __construct(UserInterface $user, RoleInterface $role, PatientInterface $patient, BlogInterface $blog, PsychologistInterface $psychologist)
    {
        $this->user = $user;
        $this->role = $role;
        $this->patient = $patient;
        $this->blog = $blog;
        $this->psychologist = $psychologist;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_user'] = $this->user->list($request->all());

        return view('pages.user.list', $this->param);
    }

    public function create() : View
    {
        $this->param['data_role'] = $this->role->all();

        return view('pages.user.add', $this->param);
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
           'name' => 'required',
           'email' => 'required|email|unique:users,email', 
           'password' => 'required|same:password_confirmation|min:8',
           'password_confirmation' => 'required',
           'role_id' => 'required|exists:roles,name',
        ], [
            'required' => ':attribute harus diisi',
            'email' => ':attribute harus berupa format Email',
            'unique' => ':attribute sudah ada',
            'min' => ':attribute minimal :min karakter',
            'same' => ':attribute tidak sesuai',
            'exists' => ':attribute tidak ada',
        ], [
            'name' => 'Nama Pengguna',
            'email' => 'Email',
            'password' => 'Kata Sandi',
            'password_confirmation' => 'Konfirmasi Kata Sandi',
            'role_id' => 'Hak Akses',
        ]);

        if ($request->role_id == 'patient') {
            $request->validate([
                'nin' => 'required|numeric|unique:profiles,nin|digits:16',
                'gender' => 'required',
                'address' => 'required',
                'job' => 'required',
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
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
        }

        if ($request->role_id == 'psychologist') {
            $request->validate([
                'nin' => 'required|numeric|unique:profiles,nin|digits:16',
                'gender' => 'required',
                'address' => 'required',
            ], [
                'required' => ':attribute harus diisi',
                'unique' => ':attribute sudah ada',
                'digits' => ':attribute harus berjumlah 16 karakter',
                'numeric' => ':attribute harus berupa angka',
            ], [
                'nin' => 'Nomor Induk Kependudukan (NIK)',
                'gender' => 'Jenis Kelamin',
                'address' => 'Alamat Lengkap',
            ]);
        }

        DB::beginTransaction();
        try {
            $request->merge(['role' => $request->role_id]);
            $account = $this->user->store($request->all());

            if ($request->role_id == 'patient') {
                $date = date('Y-m-d H-i-s');
                $random = Str::random(5);
                $new_file_name = null;

                if ($request->file('photo')) {
                    $new_file_name = 'uploads/avatar/avatar-'.Str::slug($request->name).$random.'-'.$date.'.'.$request->photo->extension();
                    $request->file('photo')->move('uploads/avatar/', $new_file_name);
                }

                $request->merge([
                    'user_id' => $account->id,
                    'photo_rename' => $new_file_name
                ]);

                $this->patient->store($request->all());
            }

            if ($request->role_id == 'psychologist') {
                $request->merge(['user_id' => $account->id]);
                $this->psychologist->store($request->all());
            }

            DB::commit();
            return redirect()->route('user.list')->with('success', 'Data berhasil ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function detail($id) : View
    {
        $this->param['detail_user'] = $this->user->detail($id);

        return view('pages.user.detail', $this->param);
    }

    public function edit($id) : View
    {
        $this->param['detail_user'] = $this->user->detail($id);
        $this->param['data_role'] = $this->role->all();

        return view('pages.user.edit', $this->param);
    }

    public function update(Request $request, $id) : RedirectResponse
    {
        $request->validate([
           'name' => 'required',
           'email' => 'required|email|unique:users,email,'.$id, 
           'role_id' => 'required|exists:roles,name',
        ], [
            'required' => ':attribute harus diisi',
            'email' => ':attribute harus berupa format Email',
            'unique' => ':attribute sudah ada',
            'min' => ':attribute minimal :min karakter',
            'exists' => ':attribute tidak ada',
        ], [
            'name' => 'Nama Pengguna',
            'email' => 'Email',
            'role_id' => 'Hak Akses',
        ]);

        if ($request->role_id == 'patient') {
            $request->validate([
                'nin' => 'required|numeric|digits:16|unique:profiles,nin,'.$this->user->detail($id)->profile->id,
                'gender' => 'required',
                'address' => 'required',
                'job' => 'required',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
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
        }

        if ($request->role_id == 'psychologist') {
            $request->validate([
                'nin' => 'required|numeric|digits:16|unique:psychologists,nin,'.$this->user->detail($id)->psychologist->id,
                'gender' => 'required',
                'address' => 'required',
            ], [
                'required' => ':attribute harus diisi',
                'unique' => ':attribute sudah ada',
                'digits' => ':attribute harus berjumlah 16 karakter',
                'numeric' => ':attribute harus berupa angka',
            ], [
                'nin' => 'Nomor Induk Kependudukan (NIK)',
                'gender' => 'Jenis Kelamin',
                'address' => 'Alamat Lengkap',
            ]);
        }

        DB::beginTransaction();
        try {
            $account = $this->user->update($id, $request->all());

            if ($request->role_id == 'patient') {
                $date = date('Y-m-d H-i-s');
                $random = Str::random(5);
                $new_file_name = null;

                if ($request->file('photo')) {
                    $new_file_name = 'uploads/avatar/avatar-'.Str::slug($request->name).$random.'-'.$date.'.'.$request->photo->extension();
                    $request->file('photo')->move('uploads/avatar/', $new_file_name);
                } else {
                    $new_file_name = $this->user->detail($id)->profile->photo;
                }

                $request->merge(['photo_rename' => $new_file_name]);
                $this->patient->update($id, $request->all());
            }

            if ($request->role_id == 'psychologist') {
                $this->psychologist->update($id, $request->all());
            }

            DB::commit();
            return redirect()->route('user.list')->with('success', 'Data berhasil ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function destroy($id) : RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->user->destroy($id);
            $this->patient->delete_by_user($id);
            $this->blog->delete_by_author($id);

            DB::commit();
            return redirect()->route('user.list')->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function activated_user(Request $request, $id) : RedirectResponse
    {
        DB::beginTransaction();
        try {
            $request->merge(['is_actived' => 1]);
            $this->user->activated_deactivated($id, $request->all());

            DB::commit();
            return redirect()->route('user.list')->with('success', 'Akun berhasil diaktifkan kembali.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function deactivated_user(Request $request, $id) : RedirectResponse
    {
        DB::beginTransaction();
        try {
            $request->merge(['is_actived' => 0]);
            $this->user->activated_deactivated($id, $request->all());

            DB::commit();
            return redirect()->route('user.list')->with('success', 'Akun berhasil dinonaktifkan.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
