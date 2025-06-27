<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomesticTransfer;
use Illuminate\Http\Request;

class AdminDomesticTransferController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DomesticTransfer $domestic)
    {
        // Delete the transfer
        $domestic->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Domestic transfer deleted successfully'
        ]);
    }
}
