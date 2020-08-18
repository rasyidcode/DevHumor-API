<?php

namespace DevHumor\Model;

class Content {

    /**
     * @option => 'image', 'gif', 'video'
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $thumbnail;

    public function __construct(string $type, string $url, string $thumbnail) {
        $this->type = $type;
        $this->url = $url;
        $this->thumbnail = $thumbnail;
    }

    public function setType(string $type) {
        $this->type = $type;
    }

    public function getType() : string {
        return $this->type;
    }

    public function setUrl(string $url) {
        $this->url = $url;
    }

    public function getUrl() : string {
        return $this->url;
    }

    public function setThumbnail(string $thumbnail) : string {
        $this->thumbnail = $thumbnail;
    }

    public function getThumbnail() : string {
        return $this->thumbnail;
    }

    public function output() : array {
        return array(
            'type' => $this->type,
            'url' => $this->url,
            'thumbnail' => $this->thumbnail
        );
    }

}