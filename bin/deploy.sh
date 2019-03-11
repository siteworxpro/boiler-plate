#!/bin/bash

NODEV=""
USER="ubuntu"
GROUP="www-data"

if [[ -e $1 ]] ; then
    echo "Available environments are 'production', 'docker' or 'vagrant'"
    exit 1
fi

if ! [[ $1 = "production" || $1 = 'vagrant' || $1 = 'docker' ]] ; then
    echo "Available environments are 'production', 'development' or 'vagrant'"
    exit 1
fi

echo "Deploying to the $1 environment"

if  [[ $1 = "production" ]] ; then
    RUNDIR="/var/www/html"
    NODEV="--no-dev"
fi

if [[ $1 = "vagrant" ]] ; then
    RUNDIR="/home/vagrant/Code"
    USER="vagrant"
    GROUP="www-data"
fi

if [[ $1 = 'docker' ]] ; then
    USER="www-data"
    GROUP="www-data"
    RUNDIR='/var/www/html'
    NODEV="--no-dev"
fi

composer install ${NODEV}

cd ${RUNDIR}

if [[ ! -d ./var/logs ]] ; then
    mkdir ./var/logs
fi

if [[ ! -d ./var/cache ]] ; then
    mkdir ./var/cache
fi

cp var/config/$1.php var/config/config.php

echo $(date +%s) > .epoch

chown -R ${USER}:${GROUP} ./*
chmod -R 750 ./*
chmod 770 -R ./var/logs
chmod 770 -R ./var/cache

if [[ ! -e ./cli ]] ; then
    ln -s ./App/Cli/bin/cli
fi

ESCRUNDIR=$(echo "$RUNDIR" | sed 's/\//\\\//g')

if  [[ $1 = "production" ]] ; then
    sudo cp ${RUNDIR}/var/etc/cron.disabled /etc/cron.d/emailapi
    sudo sed -i -e "s/__RUNDIR__/$ESCRUNDIR/g" /etc/cron.d/emailapi

    build=`cat build.txt`
    sed -i -e "s/__DEPLOYMENT__/$build/g" var/config/config.php
    rm -Rf ${RUNDIR}/*
    cp -Rp ./* ${RUNDIR}
    sudo sed -i -e "s/__USER__/$GROUP/g" /etc/supervisor/conf.d/emailapi.conf
fi

if [[ $1 = "vagrant" ]] ; then
    sed -i -e "s/__DEPLOYMENT__/Vagrant Deployment/g" var/config/config.php
fi

if  [[ $1 = "docker" ]] ; then
    build=`cat build.txt`
    sed -i -e "s/__DEPLOYMENT__/$build/g" var/config/config.php
fi

#vendor/bin/phinx migrate

