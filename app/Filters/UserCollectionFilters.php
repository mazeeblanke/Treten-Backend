<?php

namespace App\Filters;
;

class UserCollectionFilters extends Filters {

 protected $filters = [
     'q',
     'status',
     'with_muuid'
 ];

 protected function filterByQ ()
 {
    $this->builder = $this
        ->builder
        ->where(function ($query) {
            return $query
                ->orWhere(
                    'first_name',
                    'like',
                    '%' . $this->request->q . '%'
                )->orWhere(
                    'last_name',
                    'like',
                    '%' . $this->request->q . '%'
                );
        });
 }

 protected function filterByStatus ()
 {
    $this->builder = $this
        ->builder
        ->whereStatus('active');
 }

 protected function filterByWith_muuid ()
 {
    if ($this->request->with_muuid == true) {
        $this->builder = $this
            ->builder
            ->with([
                'msuuid:id,sender_id,message_uuid',
                'mruuid:id,receiver_id,message_uuid'
            ]);
    }
 }
}
