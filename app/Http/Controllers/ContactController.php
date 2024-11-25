<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable',
            'telephone' => 'nullable',
            'email' => 'nullable',
            'lien' => 'nullable',
            'reference' => 'nullable',
        ]);

        $details = [
            'name' => $validated['name'] ?? '',
            'telephone' => $validated['telephone'] ?? '',
            'email' => $validated['email'] ?? '',
            'lien' => $validated['lien'] ?? '',
            'reference' => $validated['reference'] ?? '',
        ];

        $fromAddress = 'signature@assurmabarak.com';
        Mail::to('mohamed.tajmout@gmail.com')->send(new ContactMail($details, $fromAddress));

        return response()->json(['message' => 'Email sent successfully!']);
    }
}
