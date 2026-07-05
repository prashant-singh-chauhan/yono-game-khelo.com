<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Query;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class QueryController extends Controller
{
    public function index(): View
    {
        $queries = Query::latest('received_at')->latest('id')->paginate(20);

        return view('admin.queries.index', compact('queries'));
    }

    public function show(Query $query): View
    {
        if (! $query->is_read) {
            $query->update(['is_read' => true]);
        }

        return view('admin.queries.show', compact('query'));
    }

    public function destroy(Query $query): RedirectResponse
    {
        $query->delete();

        return redirect()
            ->route('admin.queries.index')
            ->with('status', 'Query deleted.');
    }
}
