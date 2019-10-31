#!/bin/bash
#
# Tests meant to be run on Circle CI.
#
set -e

echo "Fast tests and linting"
./scripts/test.sh
echo "Test initial deployment"
./scripts/deploy.sh
echo "Test incremental deployment"
./scripts/deploy.sh
echo "End-to-end tests"
./scripts/end-to-end-tests.sh
echo "Accessibility tests"
./scripts/a11y-tests.sh
