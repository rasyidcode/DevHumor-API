<?php

namespace DevHumor\Model;

class User {

    /**
     * @var string
     */
    private $avatar_url;

    /**
     * @var string
     */
    private $profile_url;

    /**
     * @var string
     */
    private $nickname;

    public function __construct($avatar_url, $profile_url, $nickname) {
        $this->avatar_url = $avatar_url;
        $this->profile_url = $profile_url;
        $this->nickname = $nickname;
    }

    /**
     * @param string
     */
    public function setAvatarUrl(string $avatar_url) {
        $this->avatar_url = trim($avatar_url);
    }

    /**
     * @return string
     */
    public function getAvatarUrl() : string {
        return $this->avatar_url;
    }

    /**
     * @param string
     */
    public function setProfileUrl(string $profile_url) : string {
        $this->profile_url = trim($profile_url);
    }

    /**
     * @return string
     */
    public function getProfileUrl() : string {
        return $this->profile_url;
    }

    /**
     * @param string
     */
    public function setNickname(string $nickname) : string {
        $this->nickname = trim($nickname);
    }

    /**
     * @return string
     */
    public function getNickname() : string {
        return $this->nickname;
    }

    /**
     * @return array
     */
    public function output() : array {
        return array(
            'avatar_url' => $this->avatar_url,
            'profile_url' => $this->profile_url,
            'nickname' => $this->nickname
        );
    }

}