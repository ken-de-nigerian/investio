<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InterBankTransfer;

class AdminInterBankTransferController extends Controller
{
    public function index()
    {
        $transfers = InterBankTransfer::with('user', 'recipient')->latest()->paginate(10)->withQueryString();

        return view('admin.interbank.index', [
            'title' => 'Account - Interbank Transfers',
            'transfers' => $transfers
        ]);
    }

    public function show(InterBankTransfer $interbank)
    {
        return view('admin.interbank.show', [
            'title' => 'Account - Interbank Transfers',
            'transfer' => $interbank->load('user', 'recipient')
        ]);
    }

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
