<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PortalFileUpload
{
    /** @return array<int, mixed> */
    public static function imageRules(bool $required = false): array
    {
        $rules = [
            $required ? 'required' : 'nullable',
            'file',
            'image',
            'mimes:jpeg,jpg,png,gif,webp',
            'max:5120',
        ];

        return $rules;
    }

    /** @return array<int, mixed> */
    public static function receiptRules(): array
    {
        return ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'];
    }

    public static function storeProfileImage(?UploadedFile $file, ?string $deleteOldPath = null): ?string
    {
        if ($file === null) {
            return null;
        }

        if (! $file->isValid()) {
            throw ValidationException::withMessages([
                'profile_picture' => self::uploadErrorMessage($file->getErrorMessage()),
            ]);
        }

        Storage::disk('public')->makeDirectory('avatars');

        if ($deleteOldPath) {
            Storage::disk('public')->delete($deleteOldPath);
        }

        try {
            return self::saveSquareAvatar($file);
        } catch (\Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                'profile_picture' => 'We could not save your photo. Use JPG or PNG under 5MB, or continue without a picture.',
            ]);
        }
    }

    protected static function saveSquareAvatar(UploadedFile $file): string
    {
        if (! function_exists('imagecreatetruecolor')) {
            return $file->store('avatars', 'public');
        }

        $path = $file->getRealPath();
        $image = match ($file->getMimeType()) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };

        if ($image === false) {
            return $file->store('avatars', 'public');
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $side = min($width, $height);
        $srcX = (int) max(0, ($width - $side) / 2);
        $srcY = (int) max(0, ($height - $side) / 2);
        $targetSize = 512;

        $square = imagecreatetruecolor($targetSize, $targetSize);
        imagecopyresampled($square, $image, 0, 0, $srcX, $srcY, $targetSize, $targetSize, $side, $side);
        imagedestroy($image);

        $relativePath = 'avatars/'.uniqid('avatar_', true).'.jpg';
        $fullPath = Storage::disk('public')->path($relativePath);

        if (! imagejpeg($square, $fullPath, 90)) {
            imagedestroy($square);

            return $file->store('avatars', 'public');
        }

        imagedestroy($square);

        return $relativePath;
    }

    public static function storeReceipt(?UploadedFile $file): ?string
    {
        if ($file === null) {
            return null;
        }

        if (! $file->isValid()) {
            throw ValidationException::withMessages([
                'receipt' => self::uploadErrorMessage($file->getErrorMessage()),
            ]);
        }

        Storage::disk('public')->makeDirectory('receipts');

        try {
            return $file->store('receipts', 'public');
        } catch (\Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                'receipt' => 'We could not save the receipt. Use JPG, PNG, or PDF under 5MB, or submit without a file.',
            ]);
        }
    }

    protected static function uploadErrorMessage(string $phpMessage): string
    {
        return 'Upload failed ('.$phpMessage.'). Try a smaller JPG/PNG (max 5MB) or skip the file for now.';
    }
}