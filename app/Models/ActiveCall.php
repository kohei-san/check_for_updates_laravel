<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ActiveCall extends Model
{
    use HasFactory, sortable;

    protected $fillable = [
        'active_call_flg',
        'customer_id',
        'user_id'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function customer_page(){
        return $this->hasOne(CustomerPage::class, 'customer_id', 'customer_id')->ofMany('top_page_flg', 'max');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    // ソート可能なカラム
    public $sortable = [
        'active_call_flg',
        'user_id'
    ];
}
