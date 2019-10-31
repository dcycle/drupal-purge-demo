#!/bin/bash
#
# Tests meant to be run on Circle CI.
#
set -e

echo "Test deployment"
./scripts/deploy.sh
echo "Fetch the self-test page"
curl -I http://0.0.0.0:7003/selftest.php
echo "Fetch the Drupal page"
curl -I http://0.0.0.0:7001/username/5
echo "Fetch the Varnish page"
curl -I http://0.0.0.0:7002/username/5
echo "Fetch the dummy frontend page"
curl -I http://0.0.0.0:7003/username/5
