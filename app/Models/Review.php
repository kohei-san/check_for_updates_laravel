<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Review extends Model
{
    use HasFactory, sortable;

    protected $fillable = [
        'review_flg',
        'customer_id',
        'user_id'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function customer_page(){
        return $this->hasMany(CustomerPage::class, 'customer_id', 'customer_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    // ソート可能なカラム
    public $sortable = [
        'review_flg',
        'user_id'
    ];
}
