<?php

namespace App\Http\Controllers;

use App\Models\DeletionRequest;
use App\Models\User;
use App\Notifications\DataDeletionNotification;
use Illuminate\Http\Request;

class DataDeletionController extends Controller
{
    public function sendEmail(Request $request)
    {
        /** @var User $user */
        $user = User::firstWhere('email', $request->email);

        if(!$user) {
            return redirect('delete-account/confirm')->with('not_found', true);
        }

        $deletion_request = DeletionRequest::updateOrCreate(
            ['email' => $user->email],
            ['code' => rand(111111, 999999)]
        );

        $user->notify(new DataDeletionNotification($deletion_request));

        return redirect('delete-account/confirm?email='.$request->email);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|exists:deletion_requests,code,status,0,email,'.$request->email
        ]);

        $deletion_request = DeletionRequest::where('email', $request->email)->where('code', $request->code)->first();

        $deletion_request->user->delete();
        $deletion_request->update(['status' => 1]);

        return redirect('delete-account/confirm')->with('success', true);
    }
}
