<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user' => new UserResource($this->user),
            'payment_type' => $this->payment_type,
            'sum_amount' => $this->sum_amount,
            'payment' => new PaymentResource($this->payment),
            'travel_payments' => new TravelPaymentResource($this->travel_payments),
        ];
    }
}
