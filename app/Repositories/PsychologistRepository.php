<?php

namespace App\Repositories;

use App\Interfaces\PsychologistInterface;
use App\Models\Psychologist;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PsychologistRepository implements PsychologistInterface
{
    private $psychologist;

    public function __construct(Psychologist $psychologist)
    {
        $this->psychologist = $psychologist;
    }
    public function store($data)
    {
        DB::beginTransaction();
        try {
            $profile = $this->psychologist->create([
                'user_id' => $data['user_id'],
                'nin' => $data['nin'],
                'gender' => $data['gender'],
                'address' => $data['address'],
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update($user_id, $data)
    {
        DB::beginTransaction();
        try {
            $account = $this->psychologist->where('user_id', $user_id)->first();
            $account->update([
                'nin' => $data['nin'],
                'gender' => $data['gender'],
                'address' => $data['address'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function delete_by_user($user_id)
    {
        DB::beginTransaction();
        try {
            $account = $this->psychologist->where('user_id', $user_id)->first();
            $account->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }
}
