<?php

namespace service\component;

class api
{
    private $table;

    /**
     * api constructor.
     * @param $table
     */
    public function __construct($table) {
        $this->table = $table;
    }

    /**
     * Func: status
     * User: Force
     * Date: 2022/6/18
     * Time: 16:33
     * Desc: 状态切换 0-无效 1-正常 2-暂停
     */
    public function status() {

        $ids = $_REQUEST['ids'];
        $status = intval($_REQUEST['status']);

        if(empty($ids)) {
            ajax(0, '请选择记录');
        }
        if(!in_array($status, [0, 1, 2])) {
            ajax(0, '状态切换失败，不允许的状态值');
        }

        foreach ($ids as $key => $id) {
            $result = m($this->table)
                ->where([
                    'id' => $id
                ])
                ->update([
                    'status' => $status
                ]);
        }

        if($result) {
            ajax(1, '修改完成');
        }else {
            ajax(0, '修改失败');
        }
    }

    /**
     * Func: lists
     * User: Force
     * Date: 2022/6/18
     * Time: 16:35
     * Desc: 列表加载
     */
    public function lists($url = '', $page_size = 15, $data_callback = NULL) {

        $load = m($this->table)
            ->where([
                'status' => ['!=', 0],
            ])
            ->order('id desc');

        $page_info = \poem\more\page::run($load, $url, $page_size);
        $lists = $page_info['list'];

        foreach ($lists as $key => $item) {
            if($item['ctime']) {
                $lists[$key]['ctime'] = date('Y-m-d H:i:s', $item['ctime']);
            }
            if($item['utime']) {
                $lists[$key]['utime'] = date('Y-m-d H:i:s', $item['utime']);
            }
        }

        if($data_callback) {
            $lists = $data_callback($lists);
        }

        if($load) {
            ajax(1, '加载完成', [
                'lists' => $lists,
                'page_html' => $page_info['html'],
                'column' => array_keys($lists[0]),
            ]);
        }else {
            ajax(0, '加载失败');
        }
        return true;
    }

    /**
     * Func: save
     * User: Force
     * Date: 2022/6/18
     * Time: 16:32
     * Desc: 新增、更新
     */
    public function save() {

        $id = $_REQUEST['id'];
        $data = $_REQUEST;
        unset($data['id']);

        if($id) {

            $has = m($this->table)
                ->where([
                    'id' => $id
                ])
                ->find();
            if(!$has) {
                ajax(0, '无法保存，词条数据不存在');
            }

            $data['utime'] = time();
            $result = m($this->table)
                ->where([
                    'id' => $id,
                ])
                ->update($data);
        }else {

            $data['ctime'] = time();
            $result = m($this->table)
                ->insert($data);
        }

        if($result) {
            ajax(1, '保存成功');
        }else {
            ajax(0, '保存失败');
        }
    }
}
