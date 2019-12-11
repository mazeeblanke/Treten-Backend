<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'invoiceId' => $this->invoice_id,
            'name' => $this->name,
            'email' => $this->email,
            'description' => $this->name,
            'amount' => $this->amount,
            'transactionId' => $this->transaction_id,
            'userId' => $this->user_id,
            'courseId' => $this->course_id,
            'courseBatchId' => $this->course_batch_id,
            'status' => $this->status
        ];
    }
}
