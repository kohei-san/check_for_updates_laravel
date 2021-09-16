<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CustomerPage extends Model
{
    use HasFactory, sortable;
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'customer_page';

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

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function page_html() {
        return $this->hasOne(PageHtml::class, 'html_id', 'page_id');
    }

    public $sortable = [
        'page_id',
        'customer_id'
    ];
}
