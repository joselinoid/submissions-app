<?php

namespace App\Http\Controllers;

use App\Models\SubmissionDiscussion;
use Illuminate\Http\Request;

class SubmissionDiscussionController extends Controller
{
    public function store(Request $request)
    {
        SubmissionDiscussion::create([
            'submission_id' => $request->submission_id,
            'user_id'       => auth()->id(),
            'message'       => $request->message,
        ]);

        return back();
    }
}
