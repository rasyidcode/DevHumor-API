<?php

namespace DevHumor\Model;

class Humor {

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $detail_url;

    /**
     * @var string
     */
    private $published_at;

    /**
     * @var int
     */
    private $like_count;

    /**
     * @var int
     */
    private $comment_count;

    /**
     * @var int
     */
    private $view_count;

    /**
     * @var Content
     */
    private Content $content;

    /**
     * @var string
     */
    private $source_name;

    /**
     * @var string
     */
    private $source_url;

    /**
     * @var string
     */
    private $desc;

    /**
     * @var User
     */
    private User $user;

    public function __construct() {}

    /**
     * @param string
     */
    public function setTitle(string $title) {
        $this->title = trim($title);
    }

    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->title;
    }

    /**
     * @param string
     */
    public function setDetailUrl(string $detail_url) {
        $this->detail_url = trim($detail_url);
    }

    /**
     * @return string
     */
    public function getDetailUrl() : string {
        return $this->detail_url;
    }

    /**
     * @param string
     */
    public function setPublishedAt(string $published_at) {
        $this->published_at = str_replace('on ', '', strstr(trim($published_at), 'on'));
    }

    /**
     * @return string
     */
    public function getPublishedAt() : string {
        return $this->published_at;
    }

    /**
     * @param int
     */
    public function setLikeCount($like_count) {
        $this->like_count = intval($like_count);
    }

    /**
     * @return int
     */
    public function getLikeCount() : int {
        return $this->like_count;
    }

    /**
     * @param int
     */
    public function setCommentCount($comment_count) {
        $this->comment_count = intval($comment_count);
    }

    /**
     * @return int
     */
    public function getCommentCount() : int {
        return $this->comment_count;
    }

    /**
     * @param int
     */
    public function setViewCount($view_count) {
        $this->view_count = intval($view_count);
    }

    /**
     * @return int
     */
    public function getViewCount() : int {
        return $this->view_count;
    }

    /**
     * @param Content
     */
    public function setContent(Content $content) {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent() : Content {
        return $this->content;
    }

    /**
     * @param string
     */
    public function setSourceName(string $source_name) {
        $this->source_name = trim($source_name);
    }

    /**
     * @return string
     */
    public function getSourceName() : string {
        return $this->source_name;
    }

    /**
     * @param string
     */
    public function setSourceUrl(string $source_url) {
        $this->source_url = trim($source_url);
    }

    /**
     * @return string
     */
    public function getSourceUrl() : string {
        return $this->source_url;
    }

    /**
     * @param string
     */
    public function setDesc(string $desc) {
        $this->desc = strip_tags(trim($desc));
    }

    /**
     * @return string
     */
    public function getDesc() : string {
        return $this->desc;
    }

    /**
     * @param User
     */
    public function setUser(User $user) {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser() : User {
        return $this->user;
    }

    /**
     * @return array
     */
    public function output() : array {
        return array(
            'title' => $this->title,
            'detail_url' => $this->detail_url,
            'published_at' => $this->published_at,
            'like_count' => $this->like_count,
            'comment_count' => $this->comment_count,
            'view_count' => $this->view_count,
            'content' => $this->content->output(),
            'source_name' => $this->source_name,
            'source_url' => $this->source_url,
            'desc' => $this->desc,
            'user_info' => $this->user->output()
        );
    }

}