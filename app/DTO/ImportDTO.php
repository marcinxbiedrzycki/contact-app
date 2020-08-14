<?php declare(strict_types=1);

namespace App\DTO;

use Illuminate\Support\Facades\DB;

final class ImportDTO
{
    public static function insertData(string $name, string $email, string $phone): void
    {
        $value = DB::table('contact.contacts')->where('email', $email)->get();
        if ($value->count() == 0) {
            DB::table('contacts')->updateOrInsert([
                    'first_name' => $name,
                    'email' => $email,
                    'phone_number' => $phone,
                    'owner_id' => auth()->user()->id,
                ]
            );
        }
    }
}
