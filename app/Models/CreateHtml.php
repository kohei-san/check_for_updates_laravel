<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CreateHtml extends Model
{
    use HasFactory, sortable;
        /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'create_html';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'create_html_id';

}
