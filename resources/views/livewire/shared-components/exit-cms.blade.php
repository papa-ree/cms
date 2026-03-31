<?php

use Livewire\Component;
use Livewire\Attributes\{Layout, Title};
use Bale\Cms\Services\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

new #[Layout('cms::layouts.app')]
    #[Title('Bale | Exit CMS')]
    class extends Component {
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

        // Redirect to dashboard selector
        return redirect('/dashboard')->with('message', 'You have exited the CMS.');
    }
};
?>

<div>
</div>