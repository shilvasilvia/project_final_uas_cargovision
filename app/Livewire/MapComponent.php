<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Port;
use App\Models\WeatherAlert;

class MapComponent extends Component
{
    public $selectedCountryId = null;

    public function render()
    {
        $portsQuery = Port::with(['country']);
        
        if ($this->selectedCountryId) {
            $portsQuery->where('country_id', $this->selectedCountryId);
        }

        $ports = $portsQuery->where('status', 'active')->get();
        $weatherAlerts = WeatherAlert::where('status', 'active')->get();

        return view('livewire.map-component', [
            'ports' => $ports,
            'weatherAlerts' => $weatherAlerts,
        ]);
    }
}
