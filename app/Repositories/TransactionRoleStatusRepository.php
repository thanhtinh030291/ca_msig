<?php

namespace App\Repositories;

use App\TransactionRoleStatus;
use App\Repositories\BaseRepository;

/**
 * Class TransactionRoleStatusRepository
 * @package App\Repositories
 * @version February 24, 2020, 8:52 pm +07
*/

class TransactionRoleStatusRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'level_role_status_id',
        'current_status',
        'role',
        'to_status',
        'is_deleted',
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
        return TransactionRoleStatus::class;
    }
}
