<?php

namespace Bale\Cms\Livewire\Pages\SelectPage;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Bale\Cms\Models\BaleList;
use Bale\Cms\Models\BaleUser;

class Index extends Component
{
    #[Layout('rakaca::layouts.app')]
    // public $bales = [];

    // public function mount()
    // {
    //     $userUuid = Auth::user()?->uuid;

    //     $baleIds = BaleUser::where('user_uuid', $userUuid)
    //         ->pluck('bale_id');

    //     $this->bales = BaleList::whereIn('id', $baleIds)->get();
    // }

    // public function selectBale(string $id)
    // {
    //     session(['bale_active_uuid' => $id]);

    //     $selected_bale = BaleList::find($id);
    //     session(['bale_active_slug' => $selected_bale->slug]);

    //     return redirect()->route('bale.cms.overview');
    // }

    public function render()
    {
        return view('cms::livewire.pages.select-page.index', [
            'bales' => $this->bales,
        ]);
    }
    // public function render()
    // {
    //     return view('cms::livewire.pages/select-page/index');
    // }
}