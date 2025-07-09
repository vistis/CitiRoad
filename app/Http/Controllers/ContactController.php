<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendEmail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'detail' => 'required|string',
        ]);
        Mail::raw($data['detail'], function ($message) use ($data) {
            $message->to('pkong3@paragoniu.edu.kh')
                    ->subject($data['subject'])
                    ->replyTo($data['email']);
        });
        return back()->with('success', 'Email sent successfully!');
    }
}
