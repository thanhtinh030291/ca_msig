<?php

namespace App\Repositories;

use App\ClaimWordSheet;
use App\Repositories\BaseRepository;

/**
 * Class ClaimWordSheetRepository
 * @package App\Repositories
 * @version March 11, 2020, 2:42 pm +07
*/

class ClaimWordSheetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'claim_id',
        'mem_ref_no',
        'visit',
        'assessment',
        'medical',
        'claim_resuft',
        'note',
        'notification',
        'dischage_summary',
        'vat',
        'copy_of',
        'medical_report',
        'breakdown',
        'discharge_letter',
        'treatment_plant',
        'incident_report',
        'prescription',
        'lab_test',
        'police_report',
        'created_user',
        'updated_user',
        'deleted_at'
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
        return ClaimWordSheet::class;
    }
}
