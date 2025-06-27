<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InterBankTransfer;
use Illuminate\Http\Request;

class AdminInterBankTransferController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InterBankTransfer $interbank)
    {
        // Delete the transfer
        $interbank->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Interbank transfer deleted successfully'
        ]);
    }
}
