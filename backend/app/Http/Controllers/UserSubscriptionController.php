<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
use App\Models\Company;
use App\Models\Subscription;
use App\Notifications\NewHostSubscription;
use App\Notifications\NewUserSubscription;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserSubscriptionController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->tokenCan('subscription.show'),
            Response::HTTP_FORBIDDEN
        );

        validator(request()->all(), [
            'status' => [Rule::in([Subscription::STATUS_ACTIVE, Subscription::STATUS_CANCELLED])],
            'company_id' => ['integer'],
            'from_date' => ['date', 'required_with:to_date'],
            'to_date' => ['date', 'required_with:from_date', 'after:from_date'],
        ])->validate();

        $subscriptions = Subscription::query()
            ->where('user_id', auth()->id())
            ->when(request('company_id'),
                function ($query) {
                    return $query->where('company_id', request('company_id'));
                }
            )->when(request('status'),
                function ($query) {
                    return $query->where('status', request('status'));
                }
            )->when(request('from_date') && request('to_date'),
                function ($query) {
                    return $query->betweenDates(request('from_date'), request('to_date'));
                }
            )
            ->with(['company.featuredImage'])
            ->paginate(20);

        return SubscriptionResource::collection(
            $subscriptions
        );
    }

    public function create()
    {
        abort_unless(auth()->user()->tokenCan('subscriptions.make'),
            Response::HTTP_FORBIDDEN
        );

        $data = validator(request()->all(), [
            'company_id' => ['required', 'integer'],
            'start_date' => ['required', 'date:Y-m-d', 'after:today'],
            'end_date' => ['required', 'date:Y-m-d', 'after:start_date'],
        ])->validate();

        try {
            $company = Company::findOrFail($data['company_id']);
        } catch (ModelNotFoundException $e) {
            throw ValidationException::withMessages([
                'company_id' => 'Invalid company_id'
            ]);
        }

//        if ($company->user_id == auth()->id()) {
//            throw ValidationException::withMessages([
//                'company_id' => 'You cannot make a subscription on your own company'
//            ]);
//        }
//
//        if ($company->hidden || $company->approval_status == Company::APPROVAL_PENDING) {
//            throw ValidationException::withMessages([
//                'company_id' => 'You cannot make a reservation on a hidden company'
//            ]);
//        }

        $subscription = Cache::lock('subscriptions_company_'.$company->id, 10)->block(3, function () use ($data, $company) {
            $numberOfDays = Carbon::parse($data['end_date'])->endOfDay()->diffInDays(
                    Carbon::parse($data['start_date'])->startOfDay()
                ) + 1;

            if ($company->subscription()->activeBetween($data['start_date'], $data['end_date'])->exists()) {
                throw ValidationException::withMessages([
                    'company_id' => 'You cannot make a subscription during this time'
                ]);
            }

            $price = $numberOfDays * $company->price_per_day;

            if ($numberOfDays >= 28 && $company->monthly_discount) {
                $price = $price - ($price * $company->monthly_discount / 100);
            }

            return Subscription::create([
                'user_id' => auth()->id(),
                'company_id' => $company->id,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => Subscription::STATUS_ACTIVE,
                'price' => $price,
                'wifi_password' => Str::random()
            ]);
        });

        Notification::send(auth()->user(), new NewUserSubscription($subscription));
        Notification::send($company->user, new NewHostSubscription($subscription));

        return SubscriptionResource::make(
            $subscription->load('company')
        );
    }

    public function cancel(Subscription $subscription)
    {
        abort_unless(auth()->user()->tokenCan('reservations.cancel'),
            Response::HTTP_FORBIDDEN
        );

        if ($subscription->user_id != auth()->id() ||
            $subscription->status == Subscription::STATUS_CANCELLED ||
            $subscription->start_date < now()->toDateString()) {
            throw ValidationException::withMessages([
                'reservation' => 'You cannot cancel this reservation'
            ]);
        }

        $subscription->update([
            'status' => Subscription::STATUS_CANCELLED
        ]);

        return SubscriptionResource::make(
            $subscription->load('company')
        );
    }
}
