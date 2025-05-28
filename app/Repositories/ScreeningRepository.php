<?php

namespace App\Repositories;

use App\Interfaces\ScreeningInterface;
use App\Models\Screening;

use Illuminate\Support\Facades\DB;

class ScreeningRepository implements ScreeningInterface
{
    private $screening;

    public function __construct(Screening $screening)
    {
        $this->screening = $screening;
    }

    public function list($request, $questionnaire_id)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = Screening::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->whereRelation('user', 'name', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->where('questionnaire_id', $questionnaire_id)->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function list_by_user($request, $user_id, array $relations = [])
    {
        $paginate = isset($request['paginate']) ? $request['paginate'] == '1' : true;
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';
        $orderBy = $request['order_by'] ?? 'desc';
        $date = $request['date'] ?? null; // Y-m

        $qr = Screening::query();
        if (!empty($relations)) {
            $qr->with($relations);
        }
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
                $subQuery->where('screening_code', 'like', '%'.$search.'%')
                        ->orWhereRelation('questionnaire', 'title', 'like', '%'.$search.'%');
            });
        })
        ->when($date, function ($query) use ($date) {
            return $query->whereYear('created_at', date('Y', strtotime($date)))
                ->whereMonth('created_at', date('m', strtotime($date)));
        })
        ->where('user_id', $user_id)
        ->orderBy('created_at', $orderBy);

        $data = $paginate ? $qr->paginate($per_page) : $qr->get(); 
        
        return $data;
    }

    public function detail($id, array $relations = [])
    {
        return $this->screening->with($relations)->find($id);
    }

    public function detail_by_code($code)
    {
        return $this->screening->where('screening_code', $code)->first();
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $data_screening = $this->screening->create([
                'user_id' => $data['user_id'] ?? auth()->user()->id,
                'screening_code' => $data['screening_code'] ?? generate_screening_code(),
                'questionnaire_id' => $data['questionnaire_id'] ?? '-',
                'score_accumulate' => $data['score_accumulate'] ?? '-',
                'is_simulate' => $data['is_simulate'] ?? 0,
            ]);

            DB::commit();
            return $data_screening;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function all_by_user($user_id)
    {
        return $this->screening->where('user_id', $user_id)->get();
    }

    public function availability_check($user_id, $questionnaire_id): object
    {
        $current_date = date('Y-m-d');
        $lastScreening = $this->screening
                        ->select('created_at')
                        ->where('user_id', $user_id)
                        ->where('questionnaire_id', $questionnaire_id)
                        ->latest()
                        ->first();
        if (!$lastScreening) {
            return (object)[
                'available' => true,
                'message' => 'You are eligible to take the test.',
                'available_date' => $current_date,
                'last_test_date' => null
            ];
        }

        $lastScreeningDate = date('Y-m-d', strtotime($lastScreening->created_at));
        $availableDate = date('Y-m-d', strtotime($lastScreeningDate . ' +30 days'));
        $daysDifference = (strtotime($current_date) - strtotime($lastScreeningDate)) / (60 * 60 * 24);

        $isAvailable = $daysDifference >= 30;

        return (object)[
            'available' => $isAvailable,
            'message' => $isAvailable 
                        ? 'You are eligible to take the test.'
                        : 'You are not eligible to take the test yet. Please wait until ' . $availableDate . '.',
            'available_date' => $availableDate,
            'last_test_date' => $lastScreeningDate
        ];
    }
}
