<?php

function throw404() {
        header('HTTP/1.0 404 Not Found');
        header('Content-Type: text/plain');
        echo 'Article not found';
        exit(0);
}

