# CLI Web parser
[<sup>1</sup>](#wrong-arguments)
Read a web page by URL and find there all of linkable attributes.

## Table of contents
1. [Task description](#task-description)
2. [Additional tasks](#additional-tasks)
3. [Usage](#Usage)

## Task description

Use URL as input parameter of the script.  
The script must find content of all attributes **href/src** for the following tags:
* &lt;а href&gt; _links_
* &lt;img href&gt; _images_
* &lt;script src&gt; _scripts_
* &lt;link href&gt; _styles_

The output is JSON file, where each tag will contain a list of all founded values.  
Empty values must not be included in lists.  
Script must be run from command line.  
Thus project must be placed in GIT repository.
  
Input (example):  
`php index.php https://www.php.net/manual/en/pdo.drivers.php`

Output (example):
```
{
  "a": [
    "/",
    "/downloads”,
  ],
  "img": [
    "/images/logos/php-logo.svg",
    "/images/php8/logo_php8.svg",
  ],
  "script": [
    "//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js",
    "/cached.php?t=1421837618&f=/js/ext/modernizr.js",
  ],
  "link": [
    "https://www.php.net/favicon.ico",
    "https://php.net/phpnetimprovedsearch.src",
  ]
}
```

## Additional tasks
* Add testing
* Save locally statics of &lt;script/img/link&gt; (css,js,jpeg,png,jpg,…)

## Requirements
* PHP 8.1
* ext-curl
* ext-dom
* ext-libxml 

## Usage

`php index.php <URL of web page to analyse>`    
or  
`php index.php --test <path to the test HTML file>`

<sup><a id="wrong-arguments">1</a></sup> If the argument is omitted or count of arguments is more than one, script will be interrupted with an exception.







