<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Concerns\RemovesPaymentFields;

class CategoryResource extends JsonResource
{
    use RemovesPaymentFields;
    public function toArray($request)
    {
        return $this->hidePaymentFields(parent::toArray($request));
    }
}
