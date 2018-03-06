#!/usr/bin/env php
# autogenerated file; do not edit
language: c
sudo: false
dist: trusty

addons:
 apt:
  packages:
   - php5-cli
   - php-pear
   - valgrind

compiler:
 - gcc
 - clang

env:
 matrix:
<?php

$cur = "7.2";
$gen = include "./travis/pecl/gen-matrix.php";
$env = $gen([
	"PHP" => ["5.4", "5.5", "5.6"],
	"enable_debug" => "yes",
	"enable_json" => "yes"
], [
	"PHP" => ["7.0", "7.1", "master"],
	"enable_debug" => "yes",
	"enable_maintainer_zts" => "yes",
	"enable_json" => "yes"
], [
	"PHP" => $cur,
	"enable_debug",
	"enable_maintainer_zts",
	"enable_json" => "yes"
], [
	"CFLAGS" => "'-O0 -g --profile'",
	"CXXFLAGS" => "'-O0 -g --profile'",
	"PHP" => $cur,
	"enable_json" => "yes"
]);
foreach ($env as $grp) {
	foreach ($grp as $e) {
		printf("  - %s\n", $e);
	}
}

?>

cache:
 directories:
  - $HOME/cache

before_cache:
 - find $HOME/cache -name '*.gcda' -o -name '*.gcno' -delete

install:
 - make -f travis/pecl/Makefile php

script:
 - make -f travis/pecl/Makefile ext PECL=json_post
 - make -f travis/pecl/Makefile test

after_success:
 - test -n "$CFLAGS" && cd src/.libs && bash <(curl -s https://codecov.io/bash) -X xcode -X coveragepy
