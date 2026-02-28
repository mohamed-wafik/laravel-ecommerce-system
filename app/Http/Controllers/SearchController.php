<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class SearchController extends Controller
{
    /**
     * Safely resolve a named route. Returns null if the route doesn't exist,
     * so a single missing route can't kill the entire search response.
     */
    private function safeRoute(string $name, mixed $param): ?string
    {
        try {
            return route($name, $param);
        } catch (\Exception) {
            // Route doesn't exist, return a safe fallback URL
            return '#';
        }
    }

    public function search(Request $request)
    {
        $q = trim($request->q);

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Escape LIKE special characters (%, _, \)
        $escaped = addcslashes($q, '%_\\');

        // ─── Products ────────────────────────────────────────────
        Product::where('title', 'like', "%{$escaped}%")
            ->limit(5)
            ->each(function ($p) use (&$results) {
                $url = $this->safeRoute('products.show', $p->id);

                $results[] = [
                    'type'  => 'Product',
                    'title' => $p->title,
                    'url'   => $url,
                    'icon'  => '📦',
                ];
            });

        // ─── Orders ──────────────────────────────────────────────
        // IDs are integers — use a numeric check instead of LIKE
        if (is_numeric($q)) {
            Order::where('id', (int) $q)
                ->limit(5)
                ->each(function ($o) use (&$results) {
                    $url = $this->safeRoute('orders.show', $o->id);

                    $results[] = [
                        'type'  => 'Order',
                        'title' => 'Order #' . $o->id,
                        'url'   => $url,
                        'icon'  => '🧾',
                    ];
                });
        }

        // ─── Users ───────────────────────────────────────────────
        // Search both name and email columns
        User::where(function ($query) use ($escaped) {
                $query->where('name',  'LIKE', "%{$escaped}%")
                      ->orWhere('email', 'LIKE', "%{$escaped}%");
            })
            ->limit(5)
            ->each(function ($u) use (&$results) {
                $url = $this->safeRoute('users.show', $u->id);

                $results[] = [
                    'type'  => 'User',
                    'title' => $u->name,
                    'url'   => $url,
                    'icon'  => '👤',
                ];
            });
        // dd( $results);
        return response()->json($results);
    }
}