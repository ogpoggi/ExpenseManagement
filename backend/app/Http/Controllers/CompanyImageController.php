<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Company;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CompanyImageController extends Controller
{
    public function store(Company $company): JsonResource
    {
        abort_unless(auth()->user()->tokenCan('company.update'),
            Response::HTTP_FORBIDDEN
        );

        $this->authorize('update', $company);

        request()->validate([
            'image' => ['file', 'max:5000', 'mimes:jpg,png']
        ]);

        $path = request()->file('image')->storePublicly('/');

        $image = $company->images()->create([
            'path' => $path
        ]);

        return ImageResource::make($image);
    }

    public function delete(Company $company, Image $image)
    {
        abort_unless(auth()->user()->tokenCan('company.update'),
            Response::HTTP_FORBIDDEN
        );

        $this->authorize('update', $company);

        throw_if($company->images()->count() == 1,
            ValidationException::withMessages(['image' => 'Cannot delete the only image.'])
        );

        throw_if($company->featured_image_id == $image->id,
            ValidationException::withMessages(['image' => 'Cannot delete the featured image.'])
        );

        Storage::delete($image->path);

        $image->delete();
    }
}
