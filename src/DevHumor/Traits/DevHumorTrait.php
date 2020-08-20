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

    private function createHumor($humor_raw) {
        return $this->is_using_nodes ? 
            $this->decodeHumorWithNodes($humor_raw) : 
            $this->decodeHumorWithSelector($humor_raw);
    }

    private function decodeHumorWithNodes($humor_node) {
        $item_large_node    = $humor_node->children(1)->class == 'item-large' ? $humor_node->children(1) : $humor_node->children(3);
        $single_title_node  = $item_large_node->children(0);
        $content_node       = $item_large_node->children(2);
        
        $em_node            = $item_large_node->children(4);
        $desc_1_node        = $item_large_node->children(5);
        $desc_2_node        = $item_large_node->children(6);
        $desc_3_node        = $item_large_node->children(7);

        $desc               = $desc_1_node != null ? $desc_1_node->plaintext : '';
        $desc               .= $desc_2_node != null ? $desc_2_node->plaintext : '';
        $desc               .= $desc_3_node != null ? $desc_3_node->plaintext : '';

        $humor  = new Humor;
        $humor->setTitle($single_title_node->children(1)->children(0)->plaintext);
        $humor->setDetailUrl($single_title_node->children(1)->children(0)->href);
        $humor->setPublishedAt($single_title_node->children(2)->children(0)->plaintext);
        $humor->setLikeCount($single_title_node->children(2)->children(1)->children(1)->plaintext);
        $humor->setCommentCount($single_title_node->children(2)->children(2)->plaintext);
        $humor->setViewCount($single_title_node->children(2)->children(3)->plaintext);
        $humor->setContent($this->generateContent($content_node));
        $em_child_node = $em_node != NULL ? $em_node->children(0) : NULL;
        $humor->setSourceName($em_child_node != NULL ? $em_child_node->plaintext : '');
        $humor->setSourceUrl($em_child_node != NULL ? $em_child_node->href : '');
        $humor->setDesc($desc);
        $humor->setUser(new User(
            $single_title_node->children(0)->children(0)->src,
            $single_title_node->children(0)->href,
            $single_title_node->children(2)->children(0)->children(0)->plaintext
        ));

        return $humor;
    }

    private function decodeHumorWithSelector($el_humor) {

    }

    private function generateContent($content_node) {
        $content = new Content;

        if ($content_node->children(0) != NULL) {
            if ($content_node->children(1) != NULL && $content_node->children(1)->class == 'youtube-player') {
                $video_url   = $content_node->children(1)->src;
                $content->setType('video');
                $content->setUrl($video_url);
                $content->setThumbnail($video_url);
            } else if ($content_node->children(0)->{'data-animation'} != NULL) {
                $content->setType('gif');
                $content->setUrl($content_node->children(0)->{'data-animation'});
                $content->setThumbnail($content_node->children(0)->src);
            } else {
                $content->setType('image');
                $content->setUrl($content_node->children(0)->src);
                $content->setThumbnail($content_node->children(0)->src);
            }
        } else {
            if ($content_node->class == 'single-media margin-bottom') {
                $content->setType('image');
                $content->setUrl($content_node->src);
                $content->setThumbnail($content_node->src);
            } else {
                $content->setType(null);
                $content->setUrl(null);
                $content->setThumbnail(null);
            }
        }

        return $content;
    }

}