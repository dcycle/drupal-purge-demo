#!/bin/bash
#
# Get a one-time login link to your development environment.
#

echo ''
echo ' => Drupal: '$(docker-compose exec drupal /bin/bash -c "drush -l http://$(docker-compose port drupal 80) uli")
echo " => Drupal via varnish: http://$(docker-compose port varnish 80)"
echo " => Dummy frontend: http://$(docker-compose port client 80)"
echo ''
