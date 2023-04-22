<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Validators\CompanyValidator;
use App\Notifications\CompanyPendingApproval;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    public function index(): JsonResource
    {
        $companies = Company::query()
            ->when(request('user_id') && auth()->user() && request('user_id') == auth()->id(),
                function ($builder) {
                    return $builder;
                },
                function ($builder) {
                    return $builder->where('approval_status', Company::APPROVAL_APPROVED)->where('hidden', "false");
                }
            )
            ->when(request('user_id'), function ($builder) {
                return $builder->whereUserId(request('user_id'));
            })
            ->when(request('visitor_id'),
                function ($builder) {
                    return $builder->whereRelation('subscriptions', 'user_id', '=', request('visitor_id'));
                }
            )
            ->when(
                request('lat') && request('lng'),
                function ($builder) {
                    return $builder->nearestTo(request('lat'), request('lng'));
                },
                function ($builder) {
                    return $builder->orderBy('id', 'ASC');
                }
            )
            ->when(request('tags'),
                function ($builder) {
                    return $builder->whereHas(
                        'tags',
                        function ($builder) {
                            return $builder->whereIn('id', request('tags'));
                        },
                        '=',
                        count(request('tags'))
                    );
                }
            )
            ->with(['images', 'tags', 'user'])
            ->withCount(['subscriptions' => function ($builder) {
                return $builder->whereStatus(Subscription::STATUS_ACTIVE);
            }])
            ->paginate(20);

        return CompanyResource::collection(
            $companies
        );
    }

    public function show(Company $company): JsonResource
    {
        $company->loadCount(['subscriptions' => function ($builder) {
            return $builder->where('status', Subscription::STATUS_ACTIVE);
        }])
            ->load(['images', 'tags', 'user']);

        return CompanyResource::make($company);
    }

    public function create(): JsonResource
    {
        abort_unless(auth()->user()->tokenCan('company.create'),
            Response::HTTP_FORBIDDEN
        );

        $attributes = (new CompanyValidator())->validate(
            $company = new Company(),
            request()->all()
        );

        $attributes['approval_status'] = Company::APPROVAL_PENDING;
        $attributes['user_id'] = auth()->id();

        $company = DB::transaction(function () use ($company, $attributes) {
            $company->fill(
                Arr::except($attributes, ['tags'])
            )->save();

            if (isset($attributes['tags'])) {
                $company->tags()->attach($attributes['tags']);
            }

            return $company;
        });

        Notification::send(User::where('is_admin', true)->get(), new CompanyPendingApproval($company));

        return CompanyResource::make(
            $company->load(['images', 'tags', 'user'])
        );
    }

    public function update(Company $company): JsonResource
    {
        abort_unless(auth()->user()->tokenCan('company.update'),
            Response::HTTP_FORBIDDEN
        );

        $this->authorize('update', $company);

        $attributes = (new CompanyValidator())->validate($company, request()->all());

        $company->fill(Arr::except($attributes, ['tags']));

        if ($requiresReview = $company->isDirty(['lat', 'lng', 'price_per_day'])) {
            $company->fill(['approval_status' => Company::APPROVAL_PENDING]);
        }

        DB::transaction(function () use ($company, $attributes) {
            $company->save();

            if (isset($attributes['tags'])) {
                $company->tags()->sync($attributes['tags']);
            }
        });

        if ($requiresReview) {
            Notification::send(User::where('is_admin', true)->get(), new CompanyPendingApproval($company));
        }

        return CompanyResource::make(
            $company->load(['images', 'tags', 'user'])
        );
    }

    public function delete(Company $company)
    {
        abort_unless(auth()->user()->tokenCan('company.delete'),
            Response::HTTP_FORBIDDEN
        );

        $this->authorize('delete', $company);

        throw_if(
            $company->subscriptions()->where('status', Subscription::STATUS_ACTIVE)->exists(),
            ValidationException::withMessages(['company' => 'Cannot delete this company!'])
        );

        $company->images()->each(function ($image) {
            Storage::delete($image->path);

            $image->delete();
        });

        $company->delete();
    }
}
