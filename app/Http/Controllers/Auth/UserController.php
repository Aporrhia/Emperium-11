<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = auth()->user();
        $user->load(
            'stats', 
            'ownedProperties.ownable',
            'bids.race',
            'bids.horse',
        );

        return view('user.profile', compact('user'));
    }

    public function updateAvatar(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $user = auth()->user();

        if ($user->stats->avatar) {
            Storage::disk('public')->delete($user->stats->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->stats()->update([
            'avatar' => $path,
        ]);

        return redirect()->route('profile')->with('success', 'Avatar updated successfully!');
    }

    public function updateBanner(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($request->hasFile('banner')) {
            // Delete old banner if it exists
            if ($user->stats->banner && Storage::disk('public')->exists(str_replace('storage/', '', $user->stats->banner))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->stats->banner));
            }

            // Store new banner
            $path = $request->file('banner')->store('banner', 'public');
            $user->stats->update(['banner' => 'storage/' . $path]);
        }

        return redirect()->route('profile')->with('success', 'Profile banner updated successfully!');
    }

    public function privacySettings()
    {
        return view('user.privacy-settings');
    }

    public function updatePrivacySettings(Request $request)
    {
        $user = Auth::user();
        $privacySetting = $user->privacySetting ?? new \App\Models\PrivacySetting(['user_id' => $user->id]);
        $privacySetting->show_in_tier_list = $request->has('show_in_tier_list');
        $privacySetting->hide_personal_info = $request->has('hide_personal_info');
        $privacySetting->save();

        $validated = $request->validate([
            'timezone' => 'required|timezone'
        ]);

        $user->update([
            'timezone' => $validated['timezone'],
        ]);

        return redirect()->route('privacy.settings')->with('success', 'Privacy settings updated successfully!');
    }
}
