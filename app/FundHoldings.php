<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FundHoldings extends Model
{
    protected $table="fund_holdings";
    protected $guarded = ['id'];
}
