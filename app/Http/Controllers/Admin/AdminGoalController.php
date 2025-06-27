<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;

class AdminGoalController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Goal $goal)
    {
        // Delete the goal
        $goal->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Goal deleted successfully'
        ]);
    }
}
