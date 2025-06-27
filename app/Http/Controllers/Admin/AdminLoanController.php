<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class AdminLoanController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        // Delete the loan
        $loan->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Loan deleted successfully'
        ]);
    }
}
