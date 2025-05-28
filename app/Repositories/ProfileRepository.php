<?php

namespace App\Repositories;

use App\Interfaces\ProfileInterface;

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileRepository implements ProfileInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function my_account()
    {
        $data = $this->user->find(auth()->user()->id);
        return $data;
    }

    public function my_account_update($request)
    {
        DB::beginTransaction();
        try {
            $account = $this->user->find(auth()->user()->id);
            $account->update([
                'name' => $request['name'],
                'email' => $request['email'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function my_profile()
    {
        return "Halo";
    }

    public function my_password_update($request)
    {
        DB::beginTransaction();
        try {
            $account = $this->user->find(auth()->user()->id);
            $account->update([
                'password' => Hash::make($request['new_password']),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function check_password($request)
    {
        $data = $this->user->find(auth()->user()->id);
        if (password_verify($request, $data->password)) {
            return true;
        }
        
        return false;
    }
}
