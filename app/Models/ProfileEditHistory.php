<?php

namespace App\Models;

use App\Core\Model;
use App\Core\UserAgent;

final class ProfileEditHistory extends Model
{
    protected static string $table = 'profile_edit_history';

    /**
     * One append-only row per changed field. `version` is a simple
     * per-user incrementing counter across all fields (not per-field) —
     * enough for an admin to browse/compare a client's edit history in
     * order without needing per-field version branching.
     */
    public static function record(
        int $userId,
        int $changedByUserId,
        string $fieldName,
        mixed $oldValue,
        mixed $newValue,
        string $userAgent,
        string $timezone,
        ?string $note = null
    ): void {
        $version = self::count(['user_id' => $userId]) + 1;

        self::insert([
            'user_id' => $userId,
            'field_name' => $fieldName,
            'old_value' => is_array($oldValue) ? json_encode($oldValue) : (string) ($oldValue ?? ''),
            'new_value' => is_array($newValue) ? json_encode($newValue) : (string) ($newValue ?? ''),
            'changed_by_user_id' => $changedByUserId,
            'version' => $version,
            'browser' => UserAgent::browser($userAgent),
            'device' => UserAgent::device($userAgent),
            'timezone' => $timezone,
            'note' => $note,
        ]);
    }

    public static function forUser(int $userId): array
    {
        return self::where(['user_id' => $userId], 'version DESC');
    }
}
