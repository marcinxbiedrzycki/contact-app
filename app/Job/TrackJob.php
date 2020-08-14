<?php declare(strict_types=1);

namespace App\Job;

use Klaviyo\Model\EventModel;
use Monolog\DateTimeImmutable;

final class TrackJob extends AbstractJob
{
    public function handle(): void
    {
        $event = new EventModel([
            'event' => 'Clicked tracker button',
            'customer_properties' => ['$email' => auth()->user()->email],
            'properties' => ['time' => new DateTimeImmutable(false)],
        ]);
        $this->client->PublicAPI->track($event);
    }
}
