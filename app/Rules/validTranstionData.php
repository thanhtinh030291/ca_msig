<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class validTranstionData implements Rule {
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $range_curent_status = null;
    protected $range_role   = null;
    protected $range_to_status = null;
    protected $message = null;

    /**
     * Init params for compare
     * @param string $range_start_date [format Y-m-d]
     * @param string $range_end_date   [format Y-m-d]
     * @param array  $compare_end_date [format element Y-m-d]
     * @param string $object           [object element label name export error]
     *
     */
    public function __construct($range_curent_status, $range_role, $range_to_status) {
        $this->range_curent_status = $range_curent_status;
        $this->range_role   = $range_role;
        $this->range_to_status = $range_to_status;
    }

    /**
     * Determine if the validation rule passes.
     *   FALSE When:
     *       + NOT right format require of rule
     *       + Have one day in $compare_end_date or $value not between $range_start_date & $range_end_date
     *   TRUE When:
     *       + NOT have value submit or have range compare => not yet check this rule
     *       + running pass rule check
     *
     * @param  string $attribute
     * @param  array  $value
     * @return bool
     */
    public function passes($attribute, $value) {
        $map_array = [];
        if ($this->range_curent_status) {
            foreach ($this->range_curent_status  as $key => $value) {
                if ($value == $this->range_to_status[$key]) {
                    $this->message[] = 'From status & To Status not the same';
                }
                if (in_array([$value, $this->range_role[$key], $this->range_to_status[$key]], $map_array)) {
                    $this->message[] = 'there is data duplication';
                }
                $map_array[] = [$value, $this->range_role[$key], $this->range_to_status[$key]];
            }
        }

        if ($this->message != null) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return $this->message;
    }
}
