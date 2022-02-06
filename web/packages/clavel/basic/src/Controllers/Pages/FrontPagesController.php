<?php

namespace Clavel\Basic\Controllers\Pages;

use App\Http\Controllers\Controller;
use Clavel\Basic\Models\MenuItem;
use Clavel\Basic\Models\Page;
use Illuminate\Http\Request;

class FrontPagesController extends Controller
{
    public function index(Request $request)
    {
        $page = Page::whereTranslation('url_seo', $request->slug)
            ->where("pages.active", "=", "1")
            ->first();

        if (is_null($page)) {
            app()->abort(404);
        }

        if ($page->permission=='1') {
            if (!auth()->check()) {
                return redirect()->guest('login');
            }

            if (!auth()->user()->isAbleTo($page->permission_name)) {
                abort(403);
            }
        }

        $page_title = $page->title;

        //Meta Providers
        $a_metas_providers = $page->getArrayPageProviders();

        $menuitem = MenuItem::where('page_id', $page->id)->first();
        if (empty($menuitem)) {
            $breadcrumb = [];
        } else {
            $breadcrumb = MenuItem::withDepth()->defaultOrder()->ancestorsOf($menuitem->id);
        }

        return view('basic::pages.front_index', compact('page_title', 'page', 'a_metas_providers', 'breadcrumb'));
    }
}
