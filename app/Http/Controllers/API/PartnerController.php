<?php

namespace App\Http\Controllers\API;

use App\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\PartnerInterface;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    use ApiTrait;

    public function __construct(protected PartnerInterface $partner_interface) {}

    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $paginate = $request->has('paginate') ? $request->get('paginate') == '1' : true;
            $relations = ['regency'];
            $data = $this->partner_interface->list($request->all(), $relations);
            if ($paginate) {
                $data = $data->items();
            }
            $status = 'success';
            $message = 'Successfully retrieving the partner data';
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

    public function detail($id): \Illuminate\Http\JsonResponse
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $relations = ['regency'];
            $data = $this->partner_interface->detail($id, $relations);
            $status = 'success';
            $message = 'Successfully retrieving the partner data';
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
