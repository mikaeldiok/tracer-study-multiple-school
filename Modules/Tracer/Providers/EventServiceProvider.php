<?php

namespace Modules\Tracer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

//Events
Use Modules\Tracer\Events\RecordRegistered;

//Listeners
Use Modules\Tracer\Listeners\NotifyRecord;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RecordRegistered::class => [
            NotifyRecord::class,
        ],
    ];
}
