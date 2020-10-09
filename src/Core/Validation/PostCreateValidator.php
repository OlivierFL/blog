<?php

namespace App\Core\Validation;

use Exception;

class PostCreateValidator extends Validator implements ValidatorInterface
{
    /**
     * @throws Exception
     *
     * @return $this
     */
    public function getValidator(): self
    {
        foreach ($this->data as $key => $value) {
            $this->required($key)
                ->notBlank($key)
            ;
        }

        return $this;
    }
}
