<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API endpoint for getting quotations for a lead
Route::middleware('auth:sanctum')->get('/leads/{leadId}/quotations', function (Request $request, $leadId) {
    try {
        $quotations = \App\Models\Quotation::where('client_id', $leadId)
            ->where('is_revision', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'quotations' => $quotations->map(function($q) {
                return [
                    'id' => $q->id,
                    'quotation_number' => $q->quotation_number,
                    'quotation_type' => $q->quotation_type,
                    'quotation_date' => $q->quotation_date,
                    'total_amount' => $q->total_amount,
                    'status' => $q->status,
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error fetching quotations: ' . $e->getMessage(),
            'quotations' => []
        ], 500);
    }
});
