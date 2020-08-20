<?php

namespace DevHumor\Traits;

use DevHumor\Exception\NotFoundElementException;
use DevHumor\Helper\Response;
use DevHumor\Model\Content;
use DevHumor\Model\User;
use DevHumor\Model\Humor;

trait DevHumorTrait {

    private function debugElement($element, $is_loop = true) {
        echo $element . ($is_loop ? "\n" : "");
    }

    private function createHumor($el_humor) {
        return $this->is_using_nodes ? 
            $this->decodeHumorWithNodes($el_humor) : 
            $this->decodeHumorWithSelector($el_humor);
    }

    private function decodeHumorWithNodes($el_humor) {
        $el_single_title    = $el_humor->children(1)->children(0);
        $el_content_part    = $el_humor->children(1)->children(2);
        $el_em              = $el_humor->children(1)->children(4);
        $el_desc1           = $el_humor->children(1)->children(5);
        $el_desc2           = $el_humor->children(1)->children(6);
        $el_desc3           = $el_humor->children(1)->children(7);
        $desc               = $el_desc1 != null ? $el_desc1->plaintext : '';
        $desc               .= $el_desc2 != null ? $el_desc2->plaintext : '';
        $desc               .= $el_desc3 != null ? $el_desc3->plaintext : '';

        $humor  = new Humor;
        $humor->setTitle($el_single_title->children(1)->children(0)->plaintext);
        $humor->setDetailUrl($el_single_title->children(1)->children(0)->href);
        $humor->setPublishedAt($el_single_title->children(2)->children(0)->plaintext);
        $humor->setLikeCount($el_single_title->children(2)->children(1)->children(1)->plaintext);
        $humor->setCommentCount($el_single_title->children(2)->children(2)->plaintext);
        $humor->setViewCount($el_single_title->children(2)->children(3)->plaintext);
        $humor->setContent($this->generateContent($el_content_part));
        $el_em_child = $el_em != NULL ? $el_em->children(0) : NULL;
        $humor->setSourceName($el_em_child != NULL ? $el_em_child->plaintext : '');
        $humor->setSourceUrl($el_em_child != NULL ? $el_em_child->href : '');
        $humor->setDesc($desc);
        $humor->setUser(new User(
            $el_single_title->children(0)->children(0)->src,
            $el_single_title->children(0)->href,
            $el_single_title->children(2)->children(0)->children(0)->plaintext
        ));

        return $humor;
    }

    private function decodeHumorWithSelector($el_humor) {

    }

    private function curlGet($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $res = curl_exec($ch);
        
        curl_close($ch);

        return $res;
    }

    private function curlPost() {

    }

    private function generateContent($el_content_part) {
        $content = new Content;

        if ($el_content_part->children(0) != NULL) {
            if ($el_content_part->children(1) != NULL && $el_content_part->children(1)->class == 'youtube-player') {
                $video_url   = $el_content_part->children(1)->src;
                $content->setType('video');
                $content->setUrl($video_url);
                $content->setThumbnail($video_url);
            } else if ($el_content_part->children(0)->{'data-animation'} != NULL) {
                $content->setType('gif');
                $content->setUrl($el_content_part->children(0)->{'data-animation'});
                $content->setThumbnail($el_content_part->children(0)->src);
            } else {
                $content->setType('image');
                $content->setUrl($el_content_part->children(0)->src);
                $content->setThumbnail($el_content_part->children(0)->src);
            }
        } else {
            $content->setType(null);
            $content->setUrl(null);
            $content->setThumbnail(null);
        }

        return $content;
    }

}