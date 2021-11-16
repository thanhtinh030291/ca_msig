<?php

namespace App\Repositories;

use App\RoleChangeStatus;
use App\Repositories\BaseRepository;

/**
 * Class RoleChangeStatusRepository
 * @package App\Repositories
 * @version February 24, 2020, 11:55 am +07
*/

class RoleChangeStatusRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'begin',
        'end',
        'created_user',
        'updated_user'
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
        return RoleChangeStatus::class;
    }
}
