<?php

namespace App\Http\Traits;

trait SanitizesInput
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $this->sanitizeInput();
        return parent::getValidatorInstance();
    }

    /**
     * Sanitize the input.
     */
    protected function sanitizeInput()
    {
        if (method_exists($this, 'sanitize')) {
            $input = $this->all();
            $sanitizedInput = $this->container->call(
                [$this, 'sanitize'], 
                ['input' => $input]
            );

            //merge element sanitized & input now
            $replace_data = array_merge($input, $sanitizedInput);
            $this->replace($replace_data);
            request()->replace($replace_data);
        }
    }
}
