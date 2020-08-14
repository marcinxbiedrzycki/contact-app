<?php declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $fillable = ['first_name', 'email', 'phone_number', 'owner_id'];

    public static function createContact(string $firstName, string $email, string $phoneNumber, int $ownerId): self
    {
        $contact = new self();
        $contact->first_name = $firstName;
        $contact->email = $email;
        $contact->phone_number = $phoneNumber;
        $contact->owner_id = $ownerId;

        return $contact;
    }

    public function scopeOwner(Builder $query, User $user): Builder
    {
        return $query->where('owner_id', $user->id);
    }
}
