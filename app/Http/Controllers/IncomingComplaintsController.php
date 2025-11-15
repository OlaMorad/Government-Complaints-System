<?php

namespace App\Http\Controllers;

use App\Http\Services\IncomingComplaintsService;
use Illuminate\Http\Request;

class IncomingComplaintsController extends Controller
{
    public function __construct(protected IncomingComplaintsService $incoming_complaints) {}

    public function index()
    {
        return $this->incoming_complaints->getIncomingComplaints();
    }
}
