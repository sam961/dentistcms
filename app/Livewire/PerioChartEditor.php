<?php

namespace App\Livewire;

use App\Models\PerioChart;
use Livewire\Component;

class PerioChartEditor extends Component
{
    public $perioChartId;

    public $perioChart;

    public $measurements;

    public $selectedTooth = null;

    public $quadrant = 1; // Default to quadrant 1

    protected $listeners = ['refreshChart' => '$refresh'];

    public function mount($perioChartId)
    {
        $this->perioChartId = $perioChartId;
        $this->loadChart();
    }

    public function loadChart()
    {
        $this->perioChart = PerioChart::with('measurements')->findOrFail($this->perioChartId);
        $this->measurements = $this->perioChart->measurements->keyBy('tooth_number');
    }

    public function selectTooth($toothNumber)
    {
        $this->selectedTooth = $toothNumber;
    }

    public function updateMeasurement($toothNumber, $field, $value)
    {
        $measurement = $this->measurements->get($toothNumber);

        if ($measurement) {
            // Convert empty strings to null for nullable fields
            if ($value === '' || $value === null) {
                $value = null;
            }

            // Boolean fields (BOP and Plaque)
            if (in_array($field, ['bop_mb', 'bop_b', 'bop_db', 'bop_ml', 'bop_l', 'bop_dl',
                'plaque_mb', 'plaque_b', 'plaque_db', 'plaque_ml', 'plaque_l', 'plaque_dl',
                'missing', 'implant'])) {
                $value = (bool) $value;
            }

            // Integer fields
            if (in_array($field, ['pd_mb', 'pd_b', 'pd_db', 'pd_ml', 'pd_l', 'pd_dl',
                'gm_mb', 'gm_b', 'gm_db', 'gm_ml', 'gm_l', 'gm_dl',
                'mobility', 'furcation'])) {
                $value = $value !== null ? (int) $value : null;
            }

            $measurement->update([$field => $value]);
            $this->loadChart(); // Refresh data
        }
    }

    public function toggleBoolean($toothNumber, $field)
    {
        $measurement = $this->measurements->get($toothNumber);

        if ($measurement) {
            $measurement->update([$field => ! $measurement->$field]);
            $this->loadChart();
        }
    }

    public function changeQuadrant($quadrant)
    {
        $this->quadrant = $quadrant;
        $this->selectedTooth = null;
    }

    public function getQuadrantTeeth($quadrant)
    {
        if ($this->perioChart->chart_type === 'adult') {
            return match ($quadrant) {
                1 => range(1, 8),
                2 => range(9, 16),
                3 => range(17, 24),
                4 => range(25, 32),
                default => [],
            };
        } else {
            // Primary teeth
            return match ($quadrant) {
                1 => range(1, 5),
                2 => range(6, 10),
                3 => range(11, 15),
                4 => range(16, 20),
                default => [],
            };
        }
    }

    public function render()
    {
        return view('livewire.perio-chart-editor', [
            'quadrantTeeth' => $this->getQuadrantTeeth($this->quadrant),
        ]);
    }
}
