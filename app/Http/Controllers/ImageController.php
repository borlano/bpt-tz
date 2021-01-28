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
        try {
            $image = new ImageService($request->file('image')->getContent());
            $image->modifyImage()->getImage();
        } catch (\Throwable $exception) {
            return redirect()->back()->with(['errors' => $exception->getMessage()]);
        }
        return $image->render();
    }
}
