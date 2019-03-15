<?php
/*
|--------------------------------------------------------------------------
| Helper Function
|--------------------------------------------------------------------------
*/

/**
 * 返回带协议的域名
 * @return string
 */
function sp_get_host()
{
    if (substr(PHP_SAPI, 0, 3) == 'cli') {
        $host = getenv('HTTP_HOST');
    } else {
        $host=$_SERVER['HTTP_HOST'];
    }
    $protocol=is_ssl()?"https://":"http://";
    return $protocol.$host;
}

/**
 * 判断是否SSL协议
 * @return boolean
 */
function is_ssl()
{
    if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
        return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
        return true;
    }
    return false;
}

/**
 * 获取所有子分类
 * @param  array    $categorys  分类数组
 * @param  integer  $catId      分类id
 * @return array
 */
function getSubs($categorys, $catId=0)
{
    $subs = [];
    foreach ($categorys as $item) {
        if ($item['pid']==$catId) {
            $subs[]=$item;
            $subs=array_merge($subs, getSubs($categorys, $item['id']));
        }
    }
    return $subs;
}

/**
 * 获取所有父级分类
 * @param  array    $categorys  分类数组
 * @param  integer  $catId      分类id
 * @return array
 */
function getParents($categorys, $catId=0)
{
    $tree=[];
    foreach ($categorys as $item) {
        if ($item['id']==$catId) {
            if ($item['pid']>0) {
                $tree=array_merge($tree, getParents($categorys, $item['pid']));
            }
            $tree[]=$item;
            break;
        }
    }
    return $tree;
}

/**
 * 获取树状分类
 * @param  array   $categorys 分类数组
 * @param  integer $pid       父级分类id
 * @return array
 */
function getTree($categorys, $pid=0)
{
    $child = [];
    foreach ($categorys as $val) {
        if ($val['pid'] == $pid) {
            $val['child'] = getTree($categorys, $val['id']);
            if (empty($val['child'])) {
                unset($val['child']);
            }
            $child[] = $val;
        }
    }
    return $child;
}
