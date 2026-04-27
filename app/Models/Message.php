<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory;

    // الحقول المسموح بتعبئتها بشكل جماعي
    protected $fillable = [
        'user_id',
        'subject',
        'content',
        'warranty_image',
        'status',
        'worker_name',
        'admin_reply'
    ];


    // علاقة الرسالة بالمستخدم (صاحب الرسالة)
    public function user()
    {
         return $this->belongsTo(User::class, 'user_id');

    }

    /**
     * Resolve stored warranty_image value to an absolute filesystem path, if the file exists.
     */
    public static function warrantyFilesystemPath(?string $storedPath): ?string
    {
        if ($storedPath === null || trim($storedPath) === '') {
            return null;
        }

        $storedPath = trim($storedPath);
        $normalizedPath = ltrim(str_replace('\\', '/', $storedPath), '/');

        if (Str::startsWith($normalizedPath, ['http://', 'https://'])) {
            $urlPath = parse_url($normalizedPath, PHP_URL_PATH);
            $normalizedPath = ltrim((string) $urlPath, '/');
        }

        $normalizedPath = preg_replace('#^(public/)?storage/#', '', $normalizedPath);
        $fileName = basename($normalizedPath);

        $candidates = [
            storage_path('app/public/' . $normalizedPath),
            storage_path('app/public/' . ltrim($storedPath, '/\\')),
            storage_path('app/public/warranties/' . $fileName),
            public_path('storage/' . $normalizedPath),
            public_path('storage/warranties/' . $fileName),
            public_path($normalizedPath),
        ];

        foreach ($candidates as $fullPath) {
            if ($fullPath && File::exists($fullPath)) {
                return $fullPath;
            }
        }

        return null;
    }
}
