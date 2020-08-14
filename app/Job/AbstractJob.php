<?php declare(strict_types=1);

namespace App\Job;

use Klaviyo\Klaviyo;

abstract class AbstractJob
{
    protected Klaviyo $client;

    public function __construct()
    {
        $this->client = new Klaviyo(env('KLAVIYO_API_KEY'), env('KLAVIYO_API_KEY_PUBLIC'));
    }
}
