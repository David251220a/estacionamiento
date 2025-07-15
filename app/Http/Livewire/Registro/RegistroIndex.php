<?php

namespace App\Http\Livewire\Registro;

use Livewire\WithPagination;
use App\Models\RegistroDiario;
use Carbon\Carbon;
use Livewire\Component;

class RegistroIndex extends Component
{

    public $search;

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(){
        $this->resetPage();
    }

    public function render()
    {
        $fecha = Carbon::now()->toDateString();

        $data = RegistroDiario::whereDate('fecha', $fecha)
        ->where('estado_id', 1);
        
        if ($this->search) {
            
            $data->whereHas('persona', function ($q) {
                $q->where('documento', 'like', '%' . $this->search . '%')
                ->orWhere('nombre', 'like', '%' . $this->search . '%')
                ->orWhere('apellido', 'like', '%' . $this->search . '%');
            });
        }

        $data = $data->paginate(20);
        $this->resetPage();
        
        return view('livewire.registro.registro-index', compact('data'));
    }
}
