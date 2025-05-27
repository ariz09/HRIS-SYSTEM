<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\PersonalInfo;
use Illuminate\Support\Facades\Auth;

class ProfilePictureController extends Controller
{
    // Handle profile picture upload
    public function upload(Request $request)
    {
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $personalInfo = PersonalInfo::where('user_id', $user->id)->firstOrFail();

        try {
            $profilePicPath = $personalInfo->profile_picture ?? null;

            if ($request->hasFile('profile_picture')) {
                // Delete old profile pic if exists
                if ($profilePicPath && Storage::disk('public')->exists($profilePicPath)) {
                    Storage::disk('public')->delete($profilePicPath);
                }

                // Store new profile pic
                $newPath = $request->file('profile_picture')->store('profile_pictures', 'public');

                // Update the personal_info record
                $personalInfo->update([
                    'profile_picture' => $newPath,
                ]);

                return back()->with('success', 'Profile picture updated successfully!');
            }

            return back()->with('error', 'No image was uploaded.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile picture: ' . $e->getMessage());
        }
    }


    // Remove profile picture
    public function destroy()
    {
        $user = Auth::user();
        $personalInfo = PersonalInfo::where('user_id', $user->id)->firstOrFail();

        try {
            if ($personalInfo->profile_picture) {
                $this->deleteExistingPictures($personalInfo);
                
                $personalInfo->update([
                    'profile_picture' => null,
                ]);

                return back()->with('success', 'Profile picture removed successfully!');
            }

            return back()->with('error', 'No profile picture to remove.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error removing profile picture: ' . $e->getMessage());
        }
    }

    protected function deleteExistingPictures($personalInfo)
    {
        if ($personalInfo->profile_picture) {
            Storage::disk('public')->delete($personalInfo->profile_picture);
        }
    }

    protected function storeProfilePicture($file)
    {
        $path = $file->store('profile-pictures', 'public');
        
        // Create and store thumbnail
        $thumbnail = Image::make($file)
            ->fit(200, 200)
            ->save(storage_path("app/public/profile-pictures/thumbs/" . $file->hashName()));
            
        return $path;
    }
}