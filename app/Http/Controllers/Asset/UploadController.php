<?php

namespace App\Http\Controllers\Asset;

use Storage;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    protected $allow_model;

    public function __construct()
    {
        $this->allow_model = $this->getAllowModel();
    }

    private function getAllowModel()
    {
        $uploadpath = config('filesystems.uploadpath');
        if (!is_array($uploadpath) || !$uploadpath) {
            //如果没有配置上传目录项，抛出http500异常
            abort(500, 'No configuration uploadpath');
        }
        return join(',', array_keys(config('filesystems.uploadpath')));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file'=>'bail|required|file',
            'model'=>'bail|required|in:'.$this->allow_model,
            'disk'=>'bail|nullable|in:local,oss',
        ]);
        if ($validator->fails()) {
            return $this->returnJson(1, [], $validator->errors()->first());
        }
        $file = $request->file('file');
        if ($request->disk == null) {
            $request->disk = 'local';
        }
        switch ($request->disk) {
            case 'local':
                $savepath = config("filesystems.uploadpath.{$request->model}.savepath");
                $accesspath = config("filesystems.uploadpath.{$request->model}.accesspath");
                if ($res = $file->store($savepath)) {
                    $data=['url'=>asset($accesspath.'/'.basename($res))];
                }
                break;
            case 'oss':
                $savepath = config("filesystems.uploadpath.{$request->model}.osspath");
                if ($res = Storage::disk('oss')->put($savepath, $file)) {
                    $data = ['url'=>Storage::disk('oss')->url($res)];
                }
                break;
        }
        if (isset($data['url']) && $data['url']) {
            return $this->returnJson(0, '上传成功', $data);
        }
        return $this->returnJson(1, '上传失败', '');
    }
}
