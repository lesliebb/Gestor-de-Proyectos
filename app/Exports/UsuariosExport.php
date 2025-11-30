<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsuariosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Obtener la colección de usuarios (con los mismos filtros del index)
     */
    public function collection()
    {
        $query = User::with(['roles', 'participante.carrera']);

        // Aplicar filtro de búsqueda (igual que en el controlador)
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Aplicar filtro de rol (igual que en el controlador)
        if (!empty($this->filters['role'])) {
            $roleName = $this->filters['role'];
            $query->whereHas('roles', function ($q) use ($roleName) {
                $q->where('nombre', $roleName);
            });
        }

        return $query->latest()->get();
    }

    /**
     * Encabezados de las columnas
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Email',
            'Rol(es)',
            'Email Verificado',
            'Fecha de Registro',
            'No. Control',
            'Carrera',
            'Teléfono',
        ];
    }

    /**
     * Mapear cada usuario a una fila del Excel
     */
    public function map($usuario): array
    {
        return [
            $usuario->id,
            $usuario->name,
            $usuario->email,
            $usuario->roles->pluck('nombre')->join(', '),
            $usuario->email_verified_at ? 'Sí' : 'No',
            $usuario->created_at->format('d/m/Y H:i'),
            $usuario->participante->no_control ?? 'N/A',
            $usuario->participante->carrera->nombre ?? 'N/A',
            $usuario->participante->telefono ?? 'N/A',
        ];
    }

    /**
     * Estilos para el encabezado
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'], // Indigo-600
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
