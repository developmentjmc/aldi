<?php

namespace App\Backend;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JsonController extends Controller
{
    public function wilayah(Request $request)
    {
        $wilayah = DataHelper::getWilayah($request->tipe, $request->search, $request->parent_id);
        return response()->json([
            'success' => true,
            'data' => $wilayah
        ]);
    }
}
