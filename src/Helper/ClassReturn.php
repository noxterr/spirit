<?php

namespace Noxterr\Spirit\Helper;


class ClassReturn
{
    public $errcode = 0;    // Error code: 1 = error, 0 = success; Anything greater than 1 is a custom error
    public $message;        // Message
    public $data;           // Data retrieved
}
