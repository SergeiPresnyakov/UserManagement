<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    const DEFAULT_AVATAR = 'uploads/avatars/default.png';
    const AVATARS_PATH = 'uploads/avatars';

    /**
     * Update user avatar. 
     * Delete the old one, except default avatar.
     * 
     * @param UploadedFile $file New avatar
     * @param int $id User ID
     * 
     * @return string New avatar name
     */
    public static function updateAvatar($file, $id)
    {
        $fileToDelete = (new DBService)->getUserAvatar($id);
        self::delete($fileToDelete);

        return $file->store(self::AVATARS_PATH);
    }

    /**
     * Upload new user avatar
     * 
     * @param UploadedFile $file New avatar
     * 
     * @return string New avatar name
     */
    public static function uploadAvatar($file)
    {
        return $file->store(self::AVATARS_PATH);
    }

    /**
     * Delete file by filename.
     * Default avatar is preserved.
     * 
     * @param string $filename
     * 
     * @return bool Is delete successful
     */
    public static function delete($filename)
    {
        if ($filename != self::DEFAULT_AVATAR) {
            return Storage::delete($filename);
        }

        return false;
    }
}