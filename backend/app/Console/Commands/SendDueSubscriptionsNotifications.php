<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Notifications\HostSubscriptionStarting;
use App\Notifications\UserSubscriptionStarting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendDueSubscriptionsNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expense:send-subscriptions-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Subscription::query()
            ->with('office.user')
            ->where('status', Subscription::STATUS_ACTIVE)
            ->where('start_date', now()->toDateString())
            ->each(function ($subscription) {
                    Notification::send($subscription->user, new UserSubscriptionStarting($subscription));
                    Notification::send($subscription->office->user, new HostSubscriptionStarting($subscription));
            });


        return 0;
    }
}
