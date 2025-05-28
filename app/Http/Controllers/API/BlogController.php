<?php

namespace App\Http\Controllers\API;

use App\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\BlogCategoryInterface;
use App\Interfaces\BlogGalleryInterface;
use App\Interfaces\BlogInterface;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use ApiTrait;

    public function __construct(
        protected BlogCategoryInterface $blog_category,
        protected BlogInterface $blog,
        protected BlogGalleryInterface $blog_gallery) {}

    public function categories(Request $request)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;
        try {
            $data = $this->blog_category->list($request->all())->items();
            $status = 'success';
            $message = 'Successfully retrieving the blogs';
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
    
    public function blog(Request $request)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;

        try {
            $relations = [
                'blog_category:id,name,slug,color_code',
                'author:id,name,email',
                'blog_gallery:id,blog_id,document,caption'
            ];
            $data = $this->blog->list($request->all(), $relations)->items();
            $status = 'success';
            $message = 'Successfully retrieving the blogs';
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

    public function detail($id)
    {
        $status = 'failed';
        $message = '';
        $data = null;
        $status_code = HttpStatus::ERROR;

        try {
            $relations = [
                'blog_category:id,name,slug,color_code',
                'author:id,name,email',
                'blog_gallery:id,blog_id,document,caption'
            ];
            $data = $this->blog->detail($id, $relations);
            $status = 'success';
            $message = 'Successfully retrieving the blogs';
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
