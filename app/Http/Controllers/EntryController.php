<?php

namespace App\Http\Controllers;

use App\Entry;
use Illuminate\Http\Request;

class EntryController extends Controller
{

    public function index()
    {
        //
    }

    public function createEntryPurchase()
    {
        return view('entry.create_entry_purchase');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Entry $entry)
    {
        //
    }

    public function edit(Entry $entry)
    {
        //
    }

    public function update(Request $request, Entry $entry)
    {
        //
    }

    public function destroy(Entry $entry)
    {
        //
    }
}
