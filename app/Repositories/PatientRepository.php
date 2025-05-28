<?php

namespace App\Repositories;

use App\Interfaces\PatientInterface;
use App\Models\Profile;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientRepository implements PatientInterface
{
    private $patient;

    public function __construct(Profile $patient)
    {
        $this->patient = $patient;
    }
    public function store($data)
    {
        DB::beginTransaction();
        try {
            $profile = $this->patient->create([
                'user_id' => $data['user_id'] ?? null,
                'nin' => $data['nin'] ?? null,
                'gender' => $data['gender'] ?? null,
                'gender_other' => $data['gender_other'] ?? null,
                'place_of_birth' => $data['place_of_birth'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'regency_id' => $data['regency_id'] ?? null,
                'address' => $data['address'] ?? null,
                'job' => $data['job'] ?? null,
                'photo' => $data['photo_rename'] ?? null,
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
            $account = $this->patient->where('user_id', $user_id)->first();
            $account->update([
                'nin' => $data['nin'],
                'gender' => $data['gender'],
                'gender_other' => $data['gender_other'] ?? null,
                'place_of_birth' => $data['place_of_birth'],
                'date_of_birth' => $data['date_of_birth'],
                'phone_number' => $data['phone_number'],
                'regency_id' => $data['regency_id'],
                'address' => $data['address'],
                'job' => $data['job'],
                'photo' => $data['photo_rename'],
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
            $account = $this->patient->where('user_id', $user_id)->first();
            $account->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }
}
