<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Or whatever you want to search

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        $results = Employee::where('first_name', 'like', "%$query%")
            ->orWhere('last_name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->paginate(10);

        return view('search.results', compact('results', 'query'));
    }
}
