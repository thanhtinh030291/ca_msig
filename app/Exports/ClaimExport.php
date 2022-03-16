<?php

namespace App\Exports;

use App\Claim;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;



class ClaimExport implements FromQuery,WithHeadings,WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    private  $created_at_from ;
    private  $created_at_to ;
    private  $admin_list;
    public function __construct($created_at_from, $created_at_to)
    {
        $this->created_at_from = $created_at_from . " 00:00:00";
        $this->created_at_to = $created_at_to . " 23:59:59";
        $this->admin_list = \App\User::getListIncharge();
    }

    public function headings(): array {
        return [
            'Claim_no',  
            "Created by",
            "Updated by",
            "Created at",
            "Updated at"
        ];
    }

    public function map($claim): array {
        return [
            $claim->code_claim_show,
            data_get($this->admin_list ,$claim->created_user),
            data_get($this->admin_list, $claim->updated_user),
            $claim->created_at,
            $claim->updated_at
        ];
    }

    /**
     * Returns headers for report
     * @return array
     */
    public function query()
    {
        $rp = Claim::query()->whereDate('created_at',">=",$this->created_at_from)->whereDate('created_at',"<=",$this->created_at_to);
        
        return $rp;
    }

}
