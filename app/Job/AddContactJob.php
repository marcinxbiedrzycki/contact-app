<?php declare(strict_types=1);

namespace App\Job;

use App\Contact;
use Illuminate\Queue\InteractsWithQueue;
use Klaviyo\Model\ProfileModel;

final class AddContactJob extends AbstractJob
{
    use InteractsWithQueue;

    private Contact $contact;

    /**
     * Create a new job instance.
     *
     * @param Contact $contact
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
        parent::__construct();
    }

    /**
     * Execute job
     *
     * @throws \Klaviyo\Exception\KlaviyoException
     */
    public function handle(): void
    {
        $profile = new ProfileModel([
            '$id' => $this->contact->id,
            '$name' => $this->contact->first_name,
            '$email' => $this->contact->email,
            '$phoneNumber' => $this->contact->phone_number,
            ]);
        $this->client->publicAPI->identify($profile);
    }
}
