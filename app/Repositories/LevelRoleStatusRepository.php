<?php

namespace App\Repositories;

use App\LevelRoleStatus;
use App\Repositories\BaseRepository;

/**
 * Class LevelRoleStatusRepository
 * @package App\Repositories
 * @version February 24, 2020, 5:34 pm +07
*/

class LevelRoleStatusRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'min_amount',
        'max_amount',
        'begin_status',
        'end_status',
        'created_at',
        'updated_at',
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
        return LevelRoleStatus::class;
    }
}
