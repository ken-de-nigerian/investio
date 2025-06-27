<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserInvestment;
use Illuminate\Http\Request;

class AdminInvestmentController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserInvestment $investment)
    {
        // Delete the investment
        $investment->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Investment deleted successfully'
        ]);
    }
}
