<?php

namespace App\Model;


class CashierLog extends Model
{
    protected $table='cashier_log';
    protected $primaryKey='cashier_no';
    public function __construct()
    {
        parent::__construct();
    }
}