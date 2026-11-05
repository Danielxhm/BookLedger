<?php

namespace App\Livewire; 

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Categoria;

class ShowCategoriaModal extends Component
{
    public $isOpen = false;
    public $categoria = null;

    // Escucha el evento lanzado desde el botón
    #[On('showCategoryModal')]
    public function openModal($id)
    {
        $this->categoria = Categoria::with('categoriaPadre')->find($id);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->categoria = null;
    }

    public function render()
    {
        return view('livewire.show-categoria-modal');
    }
}