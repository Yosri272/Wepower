<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Media::class;
    }

    /**
     * Store a file from a request.
     *
     * @param  UploadedFile  $file  The file to store
     * @param  string  $path  The path to store the file
     * @param  string|null  $type  The type of the file
     */
    public static function storeByRequest(UploadedFile $file, string $path, ?string $type = null): Media
    {
        $path = Storage::disk('public')->put('/'.trim($path, '/'), $file);

        $extension = $file->extension();

        if (! $type) {
            $type = in_array($extension, ['jpg', 'png', 'jpeg', 'gif']) ? 'image' : $extension;
        }

        $media = self::create([
            'type' => $type,
            'name' => $file->getClientOriginalName(),
            'src' => $path,
            'extension' => $extension,
        ]);

        return $media;
    }

    /**
     * Update a media file based on the request.
     *
     * @param  UploadedFile  $file  The file to be uploaded
     * @param  string  $path  The path for the file
     * @param  ?string  $type  The type of the file
     * @param  Media  $media  The media object to be updated
     * @return Media The updated media object
     */
    public static function updateByRequest(UploadedFile $file, string $path, ?string $type, Media $media): Media
    {
        $path = Storage::disk('public')->put('/'.trim($path, '/'), $file);

        $extension = $file->extension();

        if (! $type) {
            $type = in_array($extension, ['jpg', 'png', 'jpeg', 'gif']) ? 'image' : $extension;
        }

        if (Storage::exists($media->src)) {
            Storage::delete($media->src);
        }

        $media->update([
            'type' => $type,
            'name' => $file->getClientOriginalName(),
            'src' => $path,
            'extension' => $extension,
        ]);

        return $media;
    }
}
