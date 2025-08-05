<?php

namespace App\Http\Livewire\Plan;

use App\Models\Plan;
use App\Models\PlanPersona;
use Livewire\Component;

class PlanIndex extends Component
{
    public $search;

    public function render()
    {

        if ($this->search){
            $data = PlanPersona::where('estado_id', 1)
            ->whereHas('persona', function ($query) {
                $query->where('documento', 'like', "%{$this->search}%")
                    ->orWhere('nombre', 'like', "%{$this->search}%")
                    ->orWhere('apellido', 'like', "%{$this->search}%");
            })
            ->get();
        }else{
            $data = PlanPersona::where('estado_id', 1)
            ->get();
        }

        return view('livewire.plan.plan-index', compact('data'));
    }
}
