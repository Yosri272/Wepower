<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use App\Repositories\LanguageRepository;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    public function index()
    {
        $folderPath = base_path('lang'); // actual folder path

        if (File::isDirectory($folderPath)) {
            $files = File::allFiles($folderPath);

            $fileNames = [];
            foreach ($files as $file) {
                $fileName = $file->getFilenameWithoutExtension();
                if ($fileName != 'installer_messages') {
                    $fileNames[] = $fileName;
                }
            }

            LanguageRepository::checkFileExitsOrNot($fileNames);

            $languages = LanguageRepository::getAll();

            return view('admin.language.index', compact('languages'));
        }

        return back()->withError(__('Base folder lang not found'));
    }

    public function create()
    {
        return view('admin.language.create');
    }

    public function store(LanguageRequest $request)
    {
        $newFile = base_path('lang/').$request->name.'.json';

        if (! file_exists($newFile)) {

            LanguageRepository::storeByRequest($request);

            return to_route('admin.language.index')->withSuccess(__('Created Successfully'));
        }

        return back()->withError(__('File already exists'));
    }

    public function edit(Language $language)
    {
        $filePath = base_path('lang/').$language->name.'.json'; // directory

        if (file_exists($filePath)) {
            $languageData = json_decode(file_get_contents($filePath)) ?? [];

            return view('admin.language.edit', compact('languageData', 'language'));
        }

        return back()->withError(__('File does not exist'));
    }

    public function update(LanguageRequest $request, Language $language)
    {
        $filePath = base_path('lang/').$language->name.'.json'; // directory

        if (file_exists($filePath)) {
            LanguageRepository::updateByRequest($language, $request, $filePath);

            return to_route('admin.language.index')->withSuccess(__('Updated Successfully'));
        }

        return back()->withError(__('File does not exist'));
    }

    public function delete(Language $language)
    {
        $langName = $language->name;
        $filePath = base_path('lang/').$langName.'.json'; // directory

        if ($language) {
            LanguageRepository::query()->where('name', $langName)->delete();
        }else{
            return back()->withError(__('Language not found'));
        }

        if (file_exists($filePath) && $langName != 'en') {
            unlink($filePath);
        }

        return to_route('admin.language.index')->withSuccess(__('Deleted Successfully'));
    }
}
