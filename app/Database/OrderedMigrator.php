<?php

namespace App\Database;

use Illuminate\Database\Migrations\Migrator;

// Runs the migration files (named like "users_DB.php") in table order instead of alphabetical order.
class OrderedMigrator extends Migrator
{
    // Tables must be created before the tables that link to them.
    public const ORDER = [
        'users_DB',
        'cache_DB',
        'jobs_DB',
        'packages_DB',
        'blocked_dates_DB',
        'bookings_DB',
        'visit_schedules_DB',
        'payments_DB',
        'notifications_DB',
        'conversations_DB',
        'messages_DB',
        'email_otps_DB',
        'password_reset_otps_DB',
    ];

    public function getMigrationFiles($paths)
    {
        $files = parent::getMigrationFiles($paths);
        $position = array_flip(self::ORDER);

        // Files not in the list run afterwards, in name order.
        uksort($files, fn ($a, $b) => [$position[$a] ?? PHP_INT_MAX, $a] <=> [$position[$b] ?? PHP_INT_MAX, $b]);

        return $files;
    }
}
