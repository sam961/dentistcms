<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    /**
     * Display a listing of error logs.
     */
    public function index(Request $request)
    {
        // Group repetitive errors by message and show occurrence count
        $query = ErrorLog::selectRaw('
                message,
                level,
                status,
                tenant_id,
                user_id,
                COUNT(*) as occurrence_count,
                MAX(id) as latest_id,
                MAX(created_at) as last_occurred,
                MIN(created_at) as first_occurred
            ')
            ->groupBy('message', 'level', 'status', 'tenant_id', 'user_id')
            ->with(['tenant', 'user'])
            ->orderBy('last_occurred', 'desc');

        // Filter by tenant
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $errorLogs = $query->paginate(20)->appends($request->query());
        $tenants = Tenant::orderBy('name')->get();

        // Get counts for badges
        $newCount = ErrorLog::where('status', 'new')->count();
        $criticalCount = ErrorLog::where('level', 'critical')->where('status', 'new')->count();

        return view('admin.error-logs.index', compact('errorLogs', 'tenants', 'newCount', 'criticalCount'));
    }

    /**
     * Display the specified error log.
     */
    public function show($id)
    {
        $errorLog = ErrorLog::with(['tenant', 'user'])->findOrFail($id);

        return view('admin.error-logs.show', compact('errorLog'));
    }

    /**
     * Update error log status.
     */
    public function updateStatus(Request $request, ErrorLog $errorLog)
    {
        $validated = $request->validate([
            'status' => 'required|in:acknowledged,resolved,ignored',
            'notes' => 'nullable|string',
        ]);

        $errorLog->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $errorLog->notes,
        ]);

        if ($validated['status'] === 'acknowledged') {
            $errorLog->acknowledge();
        } elseif ($validated['status'] === 'resolved') {
            $errorLog->resolve();
        }

        return redirect()->back()->with('success', 'Error log status updated successfully!');
    }

    /**
     * Delete an error log.
     */
    public function destroy(ErrorLog $errorLog)
    {
        $errorLog->delete();

        return redirect()->route('admin.error-logs.index')->with('success', 'Error log deleted successfully!');
    }

    /**
     * Clear all resolved error logs.
     */
    public function clearResolved()
    {
        ErrorLog::where('status', 'resolved')->delete();

        return redirect()->back()->with('success', 'All resolved error logs have been cleared!');
    }
}
