<?php

namespace App\Http\Controllers;

use App\Http\Services\ComplaintSearchService;
use Illuminate\Http\Request;

class ComplaintSearchController extends Controller
{
    public function __construct(protected ComplaintSearchService $searchService) {}

    public function searchByReference(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string'
        ]);

        $referenceNumber = $request->input('reference_number');

        return $this->searchService->searchByReference($referenceNumber);
    }
}
