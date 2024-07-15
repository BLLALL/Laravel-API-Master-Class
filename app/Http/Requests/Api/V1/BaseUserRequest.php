<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseUserRequest extends FormRequest
{

    public function mappedAttributes(array $otherAttributes = []): array
    {
        $mappedAttributes = array_merge([
            'data.attributes.name' => 'name',
            'data.attributes.email' => 'email',
            'data.attributes.isManager' => 'isManager',
            'data.attributes.password' => 'password',
            ], $otherAttributes);

        $attributesToUpdate = [];
        foreach ($mappedAttributes as $key => $attribute) {

            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }
        return $attributesToUpdate;
    }


}
