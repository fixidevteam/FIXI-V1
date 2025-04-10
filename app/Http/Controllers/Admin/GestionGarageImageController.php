<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\garage;
use App\Models\PhotoGarage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GestionGarageImageController extends Controller
{
    // Show the image management page
    public function index($garageId)
    {
        $garage = garage::findOrFail($garageId);
        $images = $garage->photos;
        return view('admin.gestionGarageImage.index', compact('garage', 'images'));
    }

    // Store new images
    public function store(Request $request, $garageId)
    {
        $garage = garage::findOrFail($garageId);

        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ]);

        // Check if total images won't exceed 10
        if ($garage->photos()->count() + count($request->file('images')) > 10) {
            return back()->with('error', 'Maximum 10 images allowed per garage');
        }

        // Store each image
        foreach ($request->file('images') as $image) {
            $path = $image->store('garage', 'public');
            $garage->photos()->create(['photo' => $path]);
        }

        return back()->with('success', 'Images téléchargées avec succès');
    }

    // Delete an image
    public function destroy($garageId, $imageId)
    {
        $image = PhotoGarage::where('garage_id', $garageId)->findOrFail($imageId);

        // Delete from storage
        Storage::disk('public')->delete($image->photo);

        // Delete from database
        $image->delete();

        return back()->with('success', 'Image supprimée avec succès');
    }
}