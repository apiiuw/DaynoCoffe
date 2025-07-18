<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpansesCategory;

class ManageExpansesController extends Controller
{
    public function index()
    {
        $expanses = ExpansesCategory::all();
        return view('manage-expanses.index', compact('expanses'));
    }

}
