<?php

namespace App\Livewire\Partials;

use App\Models\website_settings;
use Livewire\Component;

class Footer extends Component
{
    public function render()
    {

        return view('livewire.partials.footer',[
            'websiteSetting' => website_settings::where('is_active',1)->first(),
        ]);
    }
}
