#!/bin/bash

# When behind a proxy you should call behat from this script.
# This will unset temporary the proxy environment variables.
# To execute it run like this:
# ./behat_no_proxy.sh behat <param>

function run_behat_no_proxy {
  local http_proxy_backup=$http_proxy
  export http_proxy=;
  local HTTP_PROXY_BACKUP=$HTTP_PROXY
  export HTTP_PROXY=;

  ./behat $@ # passing all args to behat
  # if you use phar version of behat, it will be like `php behat.phar $@`

  export http_proxy=$http_proxy_backup
  export HTTP_PROXY=$HTTP_PROXY_BACKUP
}

if [[ $1 == "behat" ]]; then
  run_behat_no_proxy ${*:2} --format pretty; # pass all args but 1st
fi
