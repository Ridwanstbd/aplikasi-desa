<?php

namespace App\Exports;

use App\Models\UserClaim;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserClaimsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Start with base query
        $query = UserClaim::with('voucher');

        // Apply search filter if exists
        if ($this->request->has('search')) {
            $searchValue = $this->request->input('search')['value'];
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q
                        ->where('user_name', 'like', "%{$searchValue}%")
                        ->orWhere('user_whatsapp', 'like', "%{$searchValue}%")
                        ->orWhereHas('voucher', function ($vq) use ($searchValue) {
                            $vq->where('name', 'like', "%{$searchValue}%");
                        });
                });
            }
        }

        // Apply column-specific filtering if exists
        $columns = $this->request->input('columns', []);
        foreach ($columns as $column) {
            if (!empty($column['search']['value'])) {
                $searchValue = $column['search']['value'];
                $searchableColumn = $column['data'];

                switch ($searchableColumn) {
                    case 'voucher_name':
                        $query->whereHas('voucher', function ($q) use ($searchValue) {
                            $q->where('name', 'like', "%{$searchValue}%");
                        });
                        break;
                    case 'user_name':
                        $query->where('user_name', 'like', "%{$searchValue}%");
                        break;
                    case 'user_whatsapp':
                        $query->where('user_whatsapp', 'like', "%{$searchValue}%");
                        break;
                }
            }
        }

        // Apply ordering if exists
        $order = $this->request->input('order', []);
        if (!empty($order)) {
            $orderColumn = $columns[$order[0]['column']]['data'];
            $orderDirection = $order[0]['dir'];

            switch ($orderColumn) {
                case 'voucher_name':
                    $query->whereHas('voucher', function ($q) use ($orderDirection) {
                        $q->orderBy('name', $orderDirection);
                    });
                    break;
                case 'user_name':
                    $query->orderBy('user_name', $orderDirection);
                    break;
                case 'user_whatsapp':
                    $query->orderBy('user_whatsapp', $orderDirection);
                    break;
                case 'created_at':
                    $query->orderBy('created_at', $orderDirection);
                    break;
            }
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Voucher Name',
            'User Name',
            'User WhatsApp',
            'Created At',
        ];
    }

    public function map($userClaim): array
    {
        return [
            $userClaim->id,
            $userClaim->voucher ? $userClaim->voucher->name : 'N/A',
            $userClaim->user_name,
            $userClaim->user_whatsapp,
            $userClaim->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
