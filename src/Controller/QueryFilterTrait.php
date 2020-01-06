<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * 控制器查询trait
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
trait QueryFilterTrait
{
    /**
     * 处理查询
     *
     * @param  Request $request
     * @return $this
     */
    protected function handleFilter(Request $request)
    {
        $filterSessionKey = $this->getFilterSessionKey();

        $action = $request->get('action');

        switch ($action) {
            case 'resetFilter':
                $request->getSession()->remove($filterSessionKey);
                break;

            case 'changeLimit':
                $limit = $request->get('limit', 20);
                $request->getSession()->set($this->getLimitSessionKey(), $limit);
                break;

            default:
                $query = $request->get('filters', []);
                
                if ($query = array_filter($query, function($v, $k){
                    if (is_array($v)) {
                        foreach ($v as $value) {
                            if (!empty($value)) {
                                return true;
                            }
                        }

                        return false;
                    }
                    
                    return !empty($v) || $v == '0';
                }, ARRAY_FILTER_USE_BOTH)) {
                    $request->getSession()->set($filterSessionKey, $query);
                }
                break;
        }

        return $this;
    }

    /**
     * 从session中获取查询参数
     *
     * @param  Request $request
     * @return mixed
     */
    protected function getSessionFilterQuery(Request $request)
    {
        $query = $request->getSession()->get($this->getFilterSessionKey(), []);

        array_walk_recursive($query, function(&$item, $key) {
            $item = trim($item);
        });

        return $query;
    }

    /**
     * 从session中获取分页大小
     *
     * @param  Request $request
     * @return integer
     */
    protected function getSessionLimit(Request $request)
    {
        return $request->getSession()->get($this->getLimitSessionKey(), 20);
    }

    /**
     * 获取查询session key名
     *
     * @return string
     */
    protected function getFilterSessionKey()
    {
        return str_replace('\\', '.', get_class($this)) . '.Filters';
    }

    /**
     * 获取分页session key名
     *
     * @return string
     */
    protected function getLimitSessionKey()
    {
        return 'Page.Limit';
    }
}
