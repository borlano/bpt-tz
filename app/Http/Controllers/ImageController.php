<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Services\ImageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ImageController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function changeImage(ImageRequest $request)
    {
        if ($request->has('image')) {
            $image = new ImageService($request->file('image')->getContent());
            try {
                $image->modifyImage()->getImage();
            } catch (\Throwable $exception) {
                return $exception->getMessage();
            }
            return $image->render();
        }

        return 'No image';
    }
}
