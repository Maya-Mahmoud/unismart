<?php

namespace App\Permissions;

class RolePermissions
{
    /**
     * Define permissions for each role.
     *
     * @return array
     */
    public static function getPermissions(): array
    {
        return [
            'admin' => [
                // Admin permissions will be defined here
            ],
            'professor' => [
                // Professor permissions will be defined here
            ],
            'student' => [
                // Student permissions will be defined here
            ],
        ];
    }
}
