<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function index()
    {
        return Mail::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'message_id' => ['required'],
            'subject' => ['required'],
            'body' => ['required'],
            'from' => ['required'],
            'processed' => ['boolean'],
        ]);

        return Mail::create($data);
    }

    public function show(Mail $mail)
    {
        return $mail;
    }

    public function update(Request $request, Mail $mail)
    {
        $data = $request->validate([
            'message_id' => ['required'],
            'subject' => ['required'],
            'body' => ['required'],
            'from' => ['required'],
            'processed' => ['boolean'],
        ]);

        $mail->update($data);

        return $mail;
    }

    public function destroy(Mail $mail)
    {
        $mail->delete();

        return response()->json();
    }
}
