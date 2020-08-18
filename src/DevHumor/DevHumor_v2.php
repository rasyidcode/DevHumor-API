<?php

namespace DevHumor;

use DevHumor\Exception\NotFoundElementException;
use DevHumor\Helper\Response;
use DevHumor\Model\Content;
use DevHumor\Model\User;
use DevHumor\Model\Humor;

class DevHumor {

    use Traits\DevHumorTrait;

    const BASE_URL = 'https://devhumor.com/';
    const CODE_SUCCESS = 200;
    const CODE_NOT_FOUND = 401;
    const CODE_INTERNAL_SERVER_ERROR = 500;

    private $_dom;
    private $_is_using_nodes;

    public function __construct(bool $is_using_nodes = true) {
        $this->_dom = new \simple_html_dom;
        $this->_is_using_nodes = $is_using_nodes;
    }

    /**
     * Get all humors in the main page
     * 
     * @param int $page
     * 
     * @return Response
     */
    public function getMainPageHumors(int $page = 1) : Response {
        $page       = $page > 0 ? $page : 1;
        $response   = $this->_curlGet(self::BASE_URL.'?page='.$page);
        $this->_dom->load($response);

        $all_humor  = array();

        $humors = $this->_dom->find('div[data-href^="https://devhumor.com/media/"]');

        if (count($humors) > 0) {
            foreach($humors as $humor) {
                $created_humor = $this->_createHumor($humor);
                array_push($all_humor, $created_humor->output());
            }
        } else {
            throw new NotFoundElementException;
        }

        return Response::create($all_humor);
    }

    /**
     * Get all popular humor by week, month, year, or all
     * 
     * @param int $page
     * @param string $type => 'all', 'week', 'month', 'year'
     * 
     * @return Response
     */
    public function getPopularHumors($page = 1, $type = 'all') : Response {
        $page = $page > 0 ? $page : 1;
        $path = null;

        switch($type) {
            case 'all':
                $path = 'popular';
                break;
            case 'year':
                $path = 'popular/year';
                break;
            case 'month':
                $path = 'popular/month';
                break;
            case 'week':
                $path = 'popular/week';
                break;
            default:
                $path = 'popular';
        }

        $response = $this->_curlGet(self::BASE_URL.$path.'?page='.$page);
        $this->_dom->load($response);

        $all_humor = array();

        $humors = $this->_dom->find('div[data-href^="https://devhumor.com/media/"]');

        if (count($humors) > 0) {
            foreach($humors as $humor) {
                $created_humor = $this->_createHumor($humor);
                array_push($all_humor, $created_humor->output());
            }
        } else {
            throw new NotFoundElementException;
        }

        return Response::create($all_humor);
    }

    /**
     * Get all humor by it's category
     * 
     * @param int $page
     * @param string $category
     * 
     * @return Response
     */
    public function getHumorByCategory($page = 1, $category = 'uncategorized') : Response {

    }

    /**
     * Get random humor
     * 
     * @return Response
     */
    public function getRandomHumor() : Response {

    }
}