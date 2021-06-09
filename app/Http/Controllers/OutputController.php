<?php

namespace App\Http\Controllers;

use App\Output;
use Illuminate\Http\Request;

class OutputController extends Controller
{
    public function index()
    {
        //
    }

    public function createOutputRequest()
    {
        return view('output.create_output_request');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Output $output)
    {
        //
    }

    public function edit(Output $output)
    {
        //
    }

    public function update(Request $request, Output $output)
    {
        //
    }

    public function destroy(Output $output)
    {
        //
    }
}
