<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\PartnerInterface;
use App\Interfaces\RegencyInterface;

class PartnerController extends Controller
{
    private $param;
    private $partner, $regency;

    public function __construct(PartnerInterface $partner, RegencyInterface $regency)
    {
        $this->partner = $partner;
        $this->regency = $regency;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_partner'] = $this->partner->list($request->all());

        return view('pages.partner.list', $this->param);
    }

    public function create() : View
    {
        $this->param['data_regency'] = $this->regency->all();

        return view('pages.partner.add', $this->param);
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
           'name' => 'required',
           'phone_number' => 'required|numeric|max_digits:12',
           'address' => 'required',
           'google_maps_url' => 'required|url',
           'regency' => 'required',
        ], [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'max' => ':attribute maksimal :max karakter',
            'url' => ':attribute harus berupa URL',
        ], [
            'name' => 'Nama Mitra',
            'phone_number' => 'Nomor Telepon',
            'address' => 'Alamat',
            'google_maps_url' => 'Google Maps URL',
            'regency' => 'Kabupaten / Kota',
        ]);

        DB::beginTransaction();
        try {
            $request->merge([
                'regency_id' => $request->regency,
                'phone_number' => '62'.$request->phone_number,
            ]);
            $this->partner->store($request->all());

            DB::commit();
            return redirect()->route('master-partner.list')->with('success', 'Data berhasil ditambahkan');
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
        $this->param['detail_partner'] = $this->partner->detail($id);

        return view('pages.partner.detail', $this->param);
    }

    public function edit($id) : View
    {
        $this->param['detail_partner'] = $this->partner->detail($id);
        $this->param['data_regency'] = $this->regency->all();

        return view('pages.partner.edit', $this->param);
    }

    public function update(Request $request, $id) : RedirectResponse
    {
        $request->validate([
           'name' => 'required',
           'phone_number' => 'required|numeric|max_digits:12',
           'address' => 'required',
           'google_maps_url' => 'required|url',
           'regency' => 'required',
        ], [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'max' => ':attribute maksimal :max karakter',
            'url' => ':attribute harus berupa URL',
        ], [
            'name' => 'Nama Mitra',
            'phone_number' => 'Nomor Telepon',
            'address' => 'Alamat',
            'google_maps_url' => 'Google Maps URL',
            'regency' => 'Kabupaten / Kota',
        ]);

        DB::beginTransaction();
        try {
            $request->merge([
                'regency_id' => $request->regency,
                'phone_number' => '62'.$request->phone_number
            ]);
            $this->partner->update($id, $request->all());

            DB::commit();
            return redirect()->route('master-partner.list')->with('success', 'Data berhasil ditambahkan');
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
            $this->partner->destroy($id);

            DB::commit();
            return redirect()->route('master-partner.list')->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
