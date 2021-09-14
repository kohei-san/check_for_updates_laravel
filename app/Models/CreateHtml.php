<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreateHtml extends Model
{
    use HasFactory;

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

    /**
     * モデルにタイムスタンプを付けるか
     *
     * @var bool
     */
    public $timestamps = false;

    public function page_html() {
        return $this->hasMany(PageHtml::class, 'html_id');
    }
}
