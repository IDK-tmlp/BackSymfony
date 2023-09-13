<?php

namespace App\Dto;

class UserRelatedDto {
    public function __construct(
        public int $id,
        public $userworkers,
        public $userupgrades,
        ){
    }
}
