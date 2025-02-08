<?php

namespace App\Repositories;

use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Arafat\LaravelRepository\Repository;

class LanguageRepository extends Repository
{
    public static function model()
    {
        return Language::class;
    }

    public static function checkFileExitsOrNot(array $fileNames)
    {
        foreach ($fileNames as $name) {
            if (!self::isNameExists($name)) {
                self::create([
                    'title' => $name,
                    'name' => $name,
                ]);
            }
        }
    }

    public static function storeByRequest(LanguageRequest $request)
    {
        $filePath = base_path("lang/$request->name.json");

        $jsonData = file_get_contents(public_path('defualt/emptyLanguage.json'));

        file_put_contents($filePath, $jsonData, JSON_PRETTY_PRINT);

        return self::create([
            'title' => $request->title,
            'name' => $request->name,
        ]);
    }

    public static function updateByRequest(Language $language, LanguageRequest $request, $filePath): Language
    {
        $processedData = [];

        foreach ($request->data as $entry) {
            $key = $entry['key'];
            $value = array_key_exists('value', $entry) ? $entry['value'] : "";
            $processedData[$key] = $value;
        }

        $existingData = json_decode(file_get_contents($filePath), true);

        $updatedData = array_merge($existingData, $processedData);

        file_put_contents($filePath, json_encode($updatedData, JSON_PRETTY_PRINT));

        $language->update([
            'title' => $request->title,
        ]);

        return $language;
    }

    public static function isNameExists($name)
    {
        return self::query()->where('name', $name)->exists();
    }
}
