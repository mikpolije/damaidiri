<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function list($request)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = User::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhereRelation('roles', 'name', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function detail($id)
    {
        return $this->user->with(['profile', 'profile.regency', 'roles'])->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $account = $this->user->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'email_verified_at' => $data['email_verified_at'] ?? null,
                'password' => Hash::make($data['password']),
                'is_actived' => 1,
                'google_id' => $data['google_id'] ?? null,
            ]);

            if ($data['role'] == 'superadmin') {
                $account->assignRole($this->user::SUPERADMIN_ROLE);
            } elseif ($data['role'] == 'admin') {
                $account->assignRole($this->user::ADMIN_ROLE);
            } elseif ($data['role'] == 'psychologist') {
                $account->assignRole($this->user::PSYCHOLOGIST_ROLE);
            } else {
                $account->assignRole($this->user::PATIENT_ROLE);
            }

            DB::commit();
            return $account;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $account = $this->user->find($id);
            $account->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->user->find($id)->delete();
    }

    public function activated_deactivated($id, $data)
    {
        DB::beginTransaction();
        try {
            $account = $this->user->find($id);
            $account->update([
                'is_actived' => $data['is_actived'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function all()
    {
        return $this->user->orderBy('created_at', 'desc')->get();
    }

    public function detail_by_email($email, array $relations = [])
    {
        return !empty($relations)
            ? $this->user->with($relations)->where('email', $email)->first()
            : $this->user->where('email', $email)->first();
    }
}
