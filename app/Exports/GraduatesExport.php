<?php

namespace App\Exports;

use App\Models\Graduate;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GraduatesExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    protected array $filters = [];

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Graduate::query();

        // Apply search filter
        if (isset($this->filters['search']) && ! empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('carnet', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply year filter
        if (isset($this->filters['year']) && ! empty($this->filters['year'])) {
            $query->where('graduation_year', $this->filters['year']);
        }

        // Apply career filter
        if (isset($this->filters['career_id']) && ! empty($this->filters['career_id'])) {
            $query->where('career_id', $this->filters['career_id']);
        }

        // Apply gender filter
        if (isset($this->filters['gender']) && ! empty($this->filters['gender'])) {
            $query->where('gender', $this->filters['gender']);
        }

        return $query->orderBy('graduation_year', 'desc')->orderBy('last_name', 'asc');
    }

    public function headings(): array
    {
        return [
            'Carnet',
            'Nombre',
            'Apellido',
            'Correo',
            'Teléfono',
            'Género',
            'Carrera',
            'Año de Graduación',
        ];
    }

    public function map($row): array
    {
        return [
            $row->carnet,
            $row->first_name,
            $row->last_name,
            $row->email,
            $row->phone ?? '',
            $this->formatGender($row->gender),
            $row->career->name ?? 'N/A',
            $row->graduation_year,
        ];
    }

    private function formatGender(?string $gender): string
    {
        return match ($gender) {
            'male' => 'Masculino',
            'female' => 'Femenino',
            default => 'No especificado',
        };
    }
}
