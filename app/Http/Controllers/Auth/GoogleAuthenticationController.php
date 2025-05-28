<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Interfaces\UserInterface;

class GoogleAuthenticationController extends Controller
{
    private $param;
    private $user_account;

    public function __construct(UserInterface $user_account)
    {
        $this->user_account = $user_account;
    }

    public function redirect_to_google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback_from_google()
    {
        DB::beginTransaction();
        try {
            $google_user = Socialite::driver('google')->stateless()->user();
            $account = $this->user_account->detail_by_email($google_user->email);

            if (!$account) {
                $data = [
                    'name' => $google_user->name,
                    'email' => $google_user->email,
                    'password' => Str::random(16),
                    'google_id' => $google_user->id,
                    'email_verified_at' => now(),
                    'is_actived' => 1,
                    'role' => 'patient',
                ];

                $account = $this->user_account->store($data);
                DB::commit();

                Auth::login($account);
            } else {
                Auth::loginUsingId($account->id);
                return redirect()->route('dashboard.patient');
            }
            
            return redirect()->route('redirect.auth');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}