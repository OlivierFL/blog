<?php

namespace App\Core\Validation;

final class PostUpdateValidator extends Validator implements ValidatorInterface
{
    public function getValidator(): self
    {
        foreach ($this->data as $key => $value) {
            if (!\in_array($key, ['cover_img', 'alt_cover_img'], true)) {
                $this->required($key)
                    ->notBlank($key)
                ;
            }
        }

        return $this;
    }
}
