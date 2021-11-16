<?php

namespace App\Repositories;

use App\Provider;
use App\Repositories\BaseRepository;

/**
 * Class ProviderRepository
 * @package App\Repositories
 * @version June 26, 2020, 11:20 am +07
*/

class ProviderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'PROV_CODE',
        'EFF_DATE',
        'TERM_DATE',
        'PROV_NAME',
        'ADDR',
        'SCMA_OID_COUNTRY',
        'PAYEE',
        'BANK_NAME',
        'CL_PAY_ACCT_NO',
        'BANK_ADDR'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Provider::class;
    }
}
