<?php

namespace App\Http\Controllers\API;

use App\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\RegencyInterface;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class RegencyController extends Controller
{
    use ApiTrait;

    public function __construct(protected RegencyInterface $regencyInterface) {}

    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        
        try {
            $relations = ['province:id,region_code,name'];
            $paginate = $request->has('paginate') ? $request->get('paginate') == '1' : true;
            $data = $this->regencyInterface->list($request->all(), $relations);
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
}
