<?php

namespace Wink\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Wink\WinkPost;

class QuickLinksController
{
    /**
     * List quick links for a post.
     */
    public function index(string $id): JsonResponse
    {
        $links = \App\Models\BlogQuickLink::where('post_id', $id)
            ->orderBy('sort_order')
            ->get(['id', 'label', 'url', 'sort_order']);

        return response()->json(['data' => $links]);
    }

    /**
     * Store a new quick link for a post.
     */
    public function store(Request $request, string $id): JsonResponse
    {
        abort_unless(WinkPost::where('id', $id)->exists(), 404);

        $data = $request->validate([
            'label'      => ['required', 'string', 'max:120'],
            'url'        => ['required', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        \App\Models\BlogQuickLink::create([
            'post_id'    => $id,
            'label'      => $data['label'],
            'url'        => $data['url'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $links = \App\Models\BlogQuickLink::where('post_id', $id)
            ->orderBy('sort_order')
            ->get(['id', 'label', 'url', 'sort_order']);

        return response()->json(['data' => $links]);
    }

    /**
     * Remove a quick link from a post.
     */
    public function destroy(string $id, int $linkId): JsonResponse
    {
        \App\Models\BlogQuickLink::where('post_id', $id)
            ->where('id', $linkId)
            ->delete();

        $links = \App\Models\BlogQuickLink::where('post_id', $id)
            ->orderBy('sort_order')
            ->get(['id', 'label', 'url', 'sort_order']);

        return response()->json(['data' => $links]);
    }
}
