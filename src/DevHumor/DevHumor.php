<?php

require_once("../vendor/simple_html_dom.php");
require_once('Model/User.php');
require_once('Model/Content.php');
require_once('Model/Humor.php');
require_once('Exception/NotFoundElementException.php');
require_once('Exception/InvalidPopularParamException.php');

class DevHumor {

    const BASE_URL = 'https://devhumor.com/';
    const CODE_SUCCESS = 200;
    const CODE_NOT_FOUND = 401;
    const CODE_INTERNAL_SERVER_ERROR = 500;

    private $_dom;

    public function __construct() {
        $this->_dom = new simple_html_dom();
    }

    public function main() : Response {
        try {
            $response = $this->_curlGet(self::BASE_URL);
            $all_humor = array();

            $this->_dom->load($response);

            $humors = $this->_dom->find('div[data-href^="https://devhumor.com/media/"]');

            if (count($humors) > 0) {
                foreach($humors as $humor) {
                    $created_humor = $this->_createHumor($humor);
                    array_push($all_humor, $created_humor->output());
                }
            } else {
                throw new NotFoundElementException('Something wrong occured. Patch needed for this.');
            }

            return $this->_toJson(
                array(
                    'code' => self::CODE_SUCCESS,
                    'total_data' => count($all_humor),
                    'data' => $all_humor
                )
            );
        } catch(NotFoundElementException $e) {
            return $this->_toJson(
                array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                )
            );
        } catch(Exception $e) {
            return $this->_toJson(
                array(
                    'code' => self::CODE_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage()
                )
            );
        }
        
    }

    /**
     * @option_param => 'all', 'week', 'month', 'year'
     * @param string
     * @param_default => 'all'
     */
    public function popular($type = 'all') {
        try {
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
                    throw new InvalidPopularParamException('Param you input is not valid');
            }

            $response = $this->_curlGet(self::BASE_URL . $path);
            $all_humor = array();

            $this->_dom->load($response);

            $humors = $this->_dom->find('div[data-href^="https://devhumor.com/media/"]');

            if (count($humors) > 0) {
                foreach($humors as $humor) {
                    // echo $humor->children(1)->children(2)->children(0)->{'data-animation'} == NULL ? 'true' : 'false' . "<br>";
                    // echo "================== <br>";
                    $created_humor = $this->_createHumor($humor);
                    array_push($all_humor, $created_humor->output());
                }
                // die();
            } else {
                throw new NotFoundElementException('Something wrong occured. Patch needed for this.');
            }

            return $this->_toJson(
                array(
                    'code' => self::CODE_SUCCESS,
                    'total_data' => count($all_humor),
                    'data' => $all_humor
                )
            );
        } catch(NotFoundElementException $e) {
            return $this->_toJson(
                array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                )
            );
        } catch(Exception $e) {
            return $this->_toJson(
                array(
                    'code' => self::CODE_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage()
                )
            );
        }
    }

    private function _toJson($data) {
        header('Content-Type: application/json');
        return json_encode($data);
    }

    private function _debugElement($element, $is_loop = true) {
        echo $element . ($is_loop ? "\n" : "");
    }

    private function _createHumor($el_humor, $use_nodes = true) {
        return $use_nodes ? 
            $this->_decodeHumorWithNodes($el_humor) : 
            $this->_decodeHumorWithSelector($el_humor);
    }

    private function _decodeHumorWithNodes($el_humor) {
        $el_single_title    = $el_humor->children(1)->children(0);
        $el_content_part      = $el_humor->children(1)->children(2);
        $el_em              = $el_humor->children(1)->children(4);
        $el_desc1           = $el_humor->children(1)->children(5);
        $el_desc2           = $el_humor->children(1)->children(6);
        $el_desc3           = $el_humor->children(1)->children(7);
        $desc               = $el_desc1 != null ? $el_desc1->plaintext : '';
        $desc               .= $el_desc2 != null ? $el_desc2->plaintext : '';
        $desc               .= $el_desc3 != null ? $el_desc3->plaintext : '';

        $humor = new Humor;
        $humor->setTitle($el_single_title->children(1)->children(0)->plaintext);
        $humor->setDetailUrl($el_single_title->children(1)->children(0)->href);
        $humor->setPublishedAt($el_single_title->children(2)->children(0)->plaintext);
        $humor->setLikeCount($el_single_title->children(2)->children(1)->children(1)->plaintext);
        $humor->setCommentCount($el_single_title->children(2)->children(2)->plaintext);
        $humor->setViewCount($el_single_title->children(2)->children(3)->plaintext);
        if ($el_content_part->children(0)->{'data-animation'} != NULL) {
            $humor->setContent(new Content(
                'gif',
                $el_content_part->children(0)->{'data-animation'},
                $el_content_part->children(0)->src
            ));
        } else {
            $humor->setContent(new Content(
                'image',
                $el_content_part->children(0)->src,
                $el_content_part->children(0)->src
            ));
        }
        // $humor->setSourceName($el_em != null ? $el_em->children(0)->plaintext : '');
        // $humor->setSourceUrl($el_em != null ? $el_em->children(0)->href : '');
        $humor->setSourceName('');
        $humor->setSourceUrl('');
        $humor->setDesc($desc);
        $humor->setUser(new User(
            $el_single_title->children(0)->children(0)->src,
            $el_single_title->children(0)->href,
            $el_single_title->children(2)->children(0)->children(0)->plaintext
        ));

        return $humor;
    }

    private function _decodeHumorWithSelector($el_humor) {

    }

    private function _curlGet($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $res = curl_exec($ch);
        
        curl_close($ch);

        return $res;
    }

    private function _curlPost() {

    }

}