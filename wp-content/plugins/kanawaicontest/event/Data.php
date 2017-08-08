<?php

/**
 * Created by PhpStorm.
 * User: master
 * Date: 11.08.2016
 * Time: 11:46
 */
class BM_Event_Data
{
    const RESERVED = 1;
    const PAID = 2;
    const PAYMENT_RETURN = 3;
    const DISCLAIMED = 4;
    const CHANGED = 5;

    public $type;
    public $datetime;
    public $affected_reserve_id;
    public $initiator_id;
    public $initiator_name;

    public function __construct(
        $type,
        $datetime,
        $affected_reserve_id,
        $initiator_id,
        $initiator_name
    ){
        $this->type = $type;
        $this->datetime = $datetime;
        $this->affected_reserve_id = $affected_reserve_id;
        $this->initiator_id = $initiator_id;
        $this->initiator_name = $initiator_name;
    }
}