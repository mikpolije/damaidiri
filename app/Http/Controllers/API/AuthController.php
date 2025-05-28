<?php

namespace App\Http\Controllers\API;

use App\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\OverviewInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Interfaces\UserInterface;
use App\Models\User;
use App\Traits\ApiTrait;

class AuthController extends Controller
{
    use ApiTrait;

    public function __construct(protected UserInterface $user, protected OverviewInterface $overview) {}
    
    public function login(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'required' => ':attribute wajib diisi.',
                'email' => ' Format Email tidak sesuai.',
            ], [
                'email' => 'Email',
                'password' => 'Kata Sandi',
            ]);

            $account = User::with('profile', 'profile.regency')->where('email', $request->email)->first();

            if (!$account || !Hash::check($request->password, $account->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Validasi gagal',
                    'data' => 'Email atau password salah',
                ], 401);
            }

            if (!$account->hasRole('patient')) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Akses ditolak.',
                    'data' => 'Anda tidak memiliki izin untuk masuk.',
                ], 403);
            }

            $token = $account->createToken('authToken')->plainTextToken;
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Akses masuk berhasil.',
                'data' => [
                    'user' => $account,
                    'roles' => $account->getRoleNames(),
                    'token' => $token,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'status' => 422,
                'message' => 'Validasi gagal.',
                'data' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada sistem.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function login_with_google(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'google_id' => 'required',
            ], [
                'required' => ':attribute wajib diisi.',
                'email' => 'Format Email tidak sesuai.',
            ], [
                'name' => 'Name',
                'email' => 'Email',
                'google_id' => 'Google ID',
            ]);

            $relations = ['profile', 'profile.regency'];
            $account = $this->user->detail_by_email($request->email, $relations);

            if (!$account) {
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Str::random(16),
                    'google_id' => $request->google_id,
                    'email_verified_at' => now(),
                    'is_actived' => 1,
                    'role' => 'patient',
                ];

                $this->user->store($data);
                $account = $this->user->detail_by_email($request->email, $relations);
            }

            if ($request->google_id != $account->google_id) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Akses ditolak.',
                    'data' => 'Google ID Anda tidak sesuai',
                ], 403);
            }

            if (!$account->hasRole('patient')) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Akses ditolak.',
                    'data' => 'Anda tidak memiliki izin untuk masuk.',
                ], 403);
            }

            $token = $account->createToken('authToken')->plainTextToken;

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Akses masuk berhasil.',
                'data' => [
                    'user' => $account,
                    'roles' => $account->getRoleNames(),
                    'token' => $token,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'status' => 422,
                'message' => 'Validasi gagal.',
                'data' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada database.',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada sistem.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email', 
                'password' => 'required|same:password_confirmation|min:8',
                'password_confirmation' => 'required',
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
            ]);

            $request->merge(['role' => 'patient']);
            $account = $this->user->store($request->all());
            $token = $account->createToken('authToken')->plainTextToken;
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Registrasi berhasil.',
                'data' => [
                    'user' => $account,
                    'roles' => $account->getRoleNames(),
                    'token' => $token,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'status' => 422,
                'message' => 'Validasi gagal.',
                'data' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada sistem.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Logout berhasil.',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada sistem.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function my_profile(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = $this->user->detail(auth()->user()->id);

            if (!$account) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Pengguna tidak ditemukan.',
                    'data' => null,
                ], 404);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Profil berhasil diambil.',
                'data' => [
                    'user' => $account,
                    'profile' => $account->profile->regency,
                    'roles' => $account->getRoleNames(),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada sistem.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function my_overview()
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $data = $this->overview->overview(auth()->user()->id);
            $status = 'success';
            $message = 'Successfully retrieving the overview data';
            $status_code = HttpStatus::SUCCESS;
        }
        catch (\Exception $e) {
            $status = 'failed';
            $message = $e->getMessage();
        }
        finally {
            return $this->apiResponse($status, $message, $data, $status_code);
        }
    }
}
