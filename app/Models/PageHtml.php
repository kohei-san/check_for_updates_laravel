<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class PageHtml extends Model
{
    use HasFactory, sortable;
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'page_html';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'html_id';

    /**
     * モデルにタイムスタンプを付けるか
     *
     * @var bool
     */
    public $timestamps = false;

    public function customer_page() {
        return $this->belongsTo(CustomerPage::class, 'page_id', 'page_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function create_html() {
        return $this->belongsTo(CreateHtml::class, 'create_html_id', 'page_id');
    }

    public $sortable = [
        'time_stamp_htmlsrc'];
}
