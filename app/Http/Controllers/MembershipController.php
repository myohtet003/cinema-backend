<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MembershipController extends Controller
{
    public function index(): View
    {
        return view('membership', [
            'membershipLevels' => User::membershipLevelsForDisplay(),
        ]);
    }

    public function join(): RedirectResponse
    {
        $user = auth()->user();

        if ($user->is_club_member) {
            return redirect()->route('dashboard')->with('success', 'You are already a club member.');
        }

        $user->forceFill([
            'is_club_member' => true,
            'membership_joined_at' => now(),
            ...User::initialMembershipAttributes(),
        ])->save();

        return redirect()->route('dashboard')->with('success', 'Welcome to CineMax Club! Your membership is now active.');
    }
}
