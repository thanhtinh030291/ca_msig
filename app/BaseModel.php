<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\FindByFieldType;

class BaseModel extends Model
{
    use SoftDeletes;
    use FindByFieldType;
    /**
     * Overwrite method delete of query builder
     * 
     * @return $query
     */
    protected function runSoftDelete()
    {
        $query = $this->newQuery()->where($this->getKeyName(), $this->getKey());

        $is_deleted = 1;
        $query->update(
            [
                $this->getDeletedAtColumn() => date("Y-m-d H:i:s"),
                'is_deleted'                => $is_deleted
            ]
        );
    }

    /**
     * Check with change list from model have update really data
     *
     * @param array $changes_list [->getChanges(); after running update function]
     *
     * @return boolean
     */
    public static function isChangeData($changes_list = [])
    {
        if (array_key_exists('updated_at', $changes_list)) {
            unset($changes_list['updated_at']);
        }
        if (empty($changes_list)) {
            return false;
        }
        if (count($changes_list) > 0) {
            return true;
        }
        return false;
    }
}
