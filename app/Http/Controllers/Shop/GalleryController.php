<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Repositories\GalleryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;
        $directory = 'gallery/shop'.$shop->id;

        $currentFolder = request('folder');

        $galleries = [];
        $folderFiles = [];

        if ($currentFolder && Storage::disk('public')->exists($directory.'/'.$currentFolder)) {

            $folderFiles = File::files(Storage::disk('public')->path($directory.'/'.$currentFolder));

        } else {
            $galleries = $shop->galleries()->latest('id')->paginate(40);
        }

        return view('shop.gallery.index', compact('galleries', 'folderFiles'));
    }

    public function create()
    {
        return view('shop.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip',
        ]);

        $zipFile = $request->file('zip_file');

        $extractPath = 'newGallery/'.uniqid();

        $zip = new \ZipArchive();
        $res = $zip->open($zipFile->getRealPath());
        if ($res === true) {
            $zip->extractTo(storage_path($extractPath));
            $zip->close();
        }

        $folderName = pathinfo($zipFile->getClientOriginalName(), PATHINFO_FILENAME);

        if (! File::exists(storage_path($extractPath))) {

            File::deleteDirectory(storage_path($extractPath));

            return back()->with('error', __('Invalid zip file'));
        }

        $path = 'gallery/shop'.auth()->user()->shop->id.'/'.$folderName;

        if (Storage::disk('public')->exists($path)) {

            File::deleteDirectory(storage_path($extractPath));

            return back()->with('error', __('Folder already exists'));
        }

        $upload = GalleryRepository::uploadByRequest($extractPath, $folderName);

        return back()->with([
            'success' =>  __('Gallery uploaded successfully'),
            'total' => $upload['total'],
            'folder_name' => $folderName,
        ]);
    }
}
