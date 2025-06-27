<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WireTransfer;
use Illuminate\Http\Request;

class AdminWireTransferController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WireTransfer $wire)
    {
        // Delete the transfer
        $wire->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Wire transfer deleted successfully'
        ]);
    }
}
