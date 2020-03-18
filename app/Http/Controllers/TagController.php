<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TagController extends Controller
{
    /**
     * Get Collection of tags
     *
     * @param   Request  $request
     *
     * @return  LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 15);
        $q = $request->get('q');

        return Tag::where('name', 'like', "%{$q}%")->select('name')->get();
    }
}
