<?php
namespace App\Helpers;

use jeemce\helpers\DBHelper;

class OtpHelper
{
    public static function sendOtpForUser($userId, $length = 6, $expiryMinutes = 1)
    {
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= random_int(0, 9);
        }

        // simpen
        DBHelper::insert(<<<SQL
            INSERT INTO otps (id_user, code, expired_at, is_used, created_at)
            VALUES (:id_user, :code, :expired_at, false, :created_at)
        SQL, [
            'id_user' => $userId,
            'code' => $code,
            'expired_at' => now()->addMinutes($expiryMinutes),
            'created_at' => now(),
        ]);

        // kirim email
        $otp = DBHelper::selectOne(<<<SQL
            SELECT otps.*, users.name, users.email FROM otps
            LEFT JOIN users ON users.id = otps.id_user
            WHERE id_user = :id_user
            AND code = :code
            ORDER BY created_at DESC
            LIMIT 1
        SQL, [
            'id_user' => $userId,
            'code' => $code,
        ]);

        if (empty($otp->email)) {
            return false;
        }

        // \Mail::to($otp->email)->send(new \App\Mail\Mail($otp));
        return true;
    }

    public static function isValidOtp($userId, $code)
    {
        return DBHelper::selectOne(<<<SQL
            SELECT * FROM otps
            WHERE id_user = :id_user
            AND code = :code
            AND is_used = false
            AND expired_at > :now
            ORDER BY created_at DESC
            LIMIT 1
        SQL, [
            'id_user' => $userId,
            'code' => $code,
            'now' => now(),
        ]);
    }

    public static function markOtpAsUsed($userId, $code)
    {
        DBHelper::update(<<<SQL
            UPDATE otps
            SET is_used = true
            WHERE id_user = :id_user
            AND code = :code
        SQL, [
            'id_user' => $userId,
            'code' => $code,
        ]);
    }

    public static function isAcceptedOtp($userId)
    {
        $otp = DBHelper::selectOne(<<<SQL
            SELECT count(*) as jumlah FROM otps
            WHERE id_user = :id_user
            AND is_used = false
            AND expired_at > :now
        SQL, [
            'id_user' => $userId,
            'now' => now(),
        ]);
        return $otp?->jumlah === 0;
    }
}