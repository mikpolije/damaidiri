<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\ScreeningInterface;

class CheckScreeningController extends Controller
{
    private $param;
    private $screening;

    public function __construct(ScreeningInterface $screening)
    {
        $this->screening = $screening;
    }

    public function form() : View
    {
        return view('pages.check-screening.form');
    }

    public function result(Request $request)
    {
        $request->validate([
            'screening_code' => 'required',
         ], [
             'required' => ':attribute harus diisi',
             'unique' => ':attribute sudah ada',
         ], [
             'screening_code' => 'Kode Tes',
         ]);

        try {
            $this->param['detail_screening'] = $this->screening->detail_by_code($request->screening_code);

            if ($this->param['detail_screening'] == null) {
                return redirect()->back()->withError('Kode Tes tidak ditemukan');
            }

            return view('pages.check-screening.result', $this->param);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
