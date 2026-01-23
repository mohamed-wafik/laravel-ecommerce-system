<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Concerns\RemovesPaymentFields;

class CartResource extends JsonResource
{
    use RemovesPaymentFields;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->hidePaymentFields(parent::toArray($request));
    }
}
