<?php

namespace App\Helpers;

enum Scheme: string
{
    case HTTP = 'http';
    case HTTPS = 'https';
    case FTP = 'ftp';
    case DATA = 'data';
}