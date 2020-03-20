<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;
    protected $guarded = [];
    /**
     * $table: tên bảng tham chiếu
     * $fillable: các field được cập nhật
     * $columns: các field được lựa chọn lấy thông tin
     * $folderImg: đường dẫn chứa ảnh
     */
    protected $table = 'categories';
    protected $fillable = ['title', 'parent_id', 'description', 'content', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'];
    protected $columns = ['id', 'title', 'description', 'content', 'parent_id', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    /**
     * Quan hệ với bảng sản phẩm (nhiều - nhiều)
     */
    public function products() {
        return $this->belongstoMany('App\Models\Product');
    }

    /**
     * Lưu dữ liệu
     * 
     * @param $request: thông tin requests
     * @param $options: tên task
     * @return void
     */
    public function saveItem($request, $options) {
        $params = $request->all();
        // Update status
        if($options['field'] == 'status') {
            self::where('id', $params['id'])->update(['status' => $params['status']]);
        }
        // Thêm phần tử mới
        if($options['field'] == 'add-item') {
            $this->create($params);
        }
        // Update phần tử
        if($options['field'] == 'update-item') {
            self::where('id', $params['id'])->update([
                'title'         => $params['title'],
                'description'   => $params['description'],
                'content'       => $params['content'],
                'parent_id'     => $params['parent_id'],
                'status'        => $params['status'],
                'updated_by'    => $params['updated_by'],
            ]);
        }
    }

    /**
     * Xoá phần tử
     * 
     * @param $params: thông tin requests
     * @param $options: tên task
     * @return void
     */
    public function deleteItem($request, $options) {
        if($options['task'] == 'item') {
            self::where('id', $request->id)->delete();
        }
    }

    /**
     * Lấy phần tử theo id
     * 
     * @param $params: thông tin requests
     * @return void
     */
    public function getItemById($params, $options = null) {
        if(isset($options['columns'])) {
            return self::select($options['columns'])->where('id', $params['id'])->first();
        }
        return self::select($this->columns)->where('id', $params['id'])->first();
    }
}
