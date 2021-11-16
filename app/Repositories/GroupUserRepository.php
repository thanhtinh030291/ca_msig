<?php

namespace App\Repositories;

use App\GroupUser;
use App\Repositories\BaseRepository;

/**
 * Class GroupUserRepository
 * @package App\Repositories
 * @version September 9, 2020, 11:11 am +07
*/

class GroupUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'lead',
        'active_leader',
        'supper',
        'active_supper',
        'assistant_manager',
        'active_assistant_manager',
        'manager',
        'active_manager',
        'header',
        'active_header',
        'created_user',
        'updated_user',
        'is_deleted'
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
        return GroupUser::class;
    }
}
