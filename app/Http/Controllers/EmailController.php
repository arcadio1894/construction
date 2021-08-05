<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Http\Requests\SendEmailRequest;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{


	public function sendEmailContact(SendEmailRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

                $name = $request->get('name');
                $email = $request->get('email');
                $subject = $request->get('subject');
                $content = $request->get('message');
                Mail::to(' jmauricio@sermeind.com')->send( new ContactMail($name, $email, $subject, $content) );

                DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Mensaje enviado correctamente.'], 200);
    }
   

}
