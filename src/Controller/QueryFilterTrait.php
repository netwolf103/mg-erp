<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Trait class of QueryFilter
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
trait QueryFilterTrait
{
    /**
     * Handle filter
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
     * Return filter value from session.
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
     * Return page limit from session.
     *
     * @param  Request $request
     * @return integer
     */
    protected function getSessionLimit(Request $request)
    {
        return $request->getSession()->get($this->getLimitSessionKey(), 20);
    }

    /**
     * Build filter session key.
     *
     * @return string
     */
    protected function getFilterSessionKey()
    {
        return str_replace('\\', '.', get_class($this)) . '.Filters';
    }

    /**
     * Return key name of page limit.
     *
     * @return string
     */
    protected function getLimitSessionKey()
    {
        return 'Page.Limit';
    }
}
