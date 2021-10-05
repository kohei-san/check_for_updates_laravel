<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LongDifference extends Model
{
    use HasFactory, sortable;
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'difference_bet_longterm';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'page_id';

    /**
     * モデルにタイムスタンプを付けるか
     *
     * @var bool
     */
    public $timestamps = false;

    public function customer_page() {
        return $this->hasOne(CustomerPage::class, 'page_id');
    }

    
    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // ソート可能なカラム
    public $sortable = [
        'time_stamp_dif_long',
        'difference_flg'
    ];
}
