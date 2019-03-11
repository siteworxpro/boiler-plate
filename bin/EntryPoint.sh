#!/usr/bin/env bash

RUNDIR='/var/www/html'
ESCRUNDIR=$(echo "$RUNDIR" | sed 's/\//\\\//g')

# if you need migrations
## $RUNDIR/vendor/bin/phinx migrate

apache2-foreground