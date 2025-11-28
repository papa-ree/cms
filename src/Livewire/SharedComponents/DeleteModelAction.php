<?php
namespace Bale\Cms\Livewire\SharedComponents;

use Bale\Cms\Services\TenantConnectionService;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Exception;

class DeleteModelAction extends Component
{
    #[Locked]
    public string $modelClass;

    #[Locked]
    public int|string $recordId;

    public function mount(string $model, int|string $id)
    {
        // Pastikan tenant aktif
        TenantConnectionService::ensureActive();

        $this->modelClass = $model;
        $this->recordId = $id;
    }

    public function delete()
    {
        // Pastikan koneksi tenant aktif
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        /** @var Model $model */
        $model = App::make($this->modelClass);

        try {
            // Gunakan koneksi tenant
            $model = $model->setConnection($connection)
                ->newQuery()
                ->findOrFail($this->recordId);

            $model->delete();
        } catch (Exception $e) {
            info('Navigation Deletion failed: ' . $e);
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
            return;
        }

        $this->dispatch('toast', message: 'Navigation Item Deleted!', type: 'success');
    }

    public function render()
    {
        return view('cms::livewire.shared-components.delete-model-action');
    }
}