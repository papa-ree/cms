<?php

namespace Bale\Cms\Traits;

use Bale\Cms\Services\TenantConnectionService;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Model;

trait HasSafeDelete
{
    #[Locked]
    public $deleteId;

    #[On('deleteItem')]
    public function deleteItem($id)
    {
        $this->deleteId = $id;
        $this->performDelete();
    }

    /**
     * Komponen WAJIB mengisi:
     * protected string $modelClass = Model::class;
     */
    protected function getModelClass(): string
    {
        if (!property_exists($this, 'modelClass')) {
            throw new \Exception("Property \$modelClass belum didefinisikan pada komponen Livewire.");
        }

        return $this->modelClass;
    }

    public function performDelete(string $message = 'Item deleted successfully!')
    {
        $modelClass = $this->getModelClass();

        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        /** @var Model $model */
        $model = (new $modelClass)->setConnection($connection);

        $item = $model->find($this->deleteId);

        if (!$item) {
            $this->dispatch('toast', message: 'Item not found', type: 'error');
            return;
        }

        $item->delete();

        $this->dispatch('toast', message: $message, type: 'success');
    }
}
