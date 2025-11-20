<?php

namespace Bale\Cms\Livewire\SharedComponents;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Bale\Cms\Services\TenantManager;

class ExitCms extends Component
{
    #[Layout('cms::layouts.app')]

    public function mount(Request $request)
    {
        // Clear session
        $request->session()->forget('bale_active_uuid');

        // Clear active tenant connection
        TenantManager::clear();

        // Optionally purge the tenant connection if exists
        if ($active = TenantManager::getActiveConnection()) {
            DB::purge($active);
        }

        // Redirect to guest/home page
        return redirect('/guest')->with('message', 'You have exited the CMS.');
    }

    public function render()
    {
        return view('cms::livewire.shared-components/exit-cms');
    }
}