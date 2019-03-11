# Siteworx Professionals Boiler Plate
[![Build Status](https://travis-ci.org/siteworxpro/boiler-plate.svg?branch=master)](https://travis-ci.org/siteworxpro/boiler-plate)

## What is this?

This is a starting point that I use for all of my projects.  It's got the basics of what I need for most projects.

## What it's not

This is not a new frame work or ment to be a one size fits all solution.  Most of this application is specificly built for what I need.

*Why not use Laravel, Cake or Symphony?*
I like laravel/lumin and have used it in several projects.  Cake and the others are also fine but I still like the basics of slim.
I have always found it more than I have always needed.  I don't really need everything plus the kitchen sink. 

*No CSS?*
No, most client are going to bring their own styles or templates.  Check these procjects out as a great place to start

[Vuetifyjs](https://vuetifyjs.com/en/)
[Vue Bootstrap](https://bootstrap-vue.js.org/)
[Vue Material](https://vuematerial.io/)

### Vagrant Development
Vagrant is used for local development and includes xdebug.  

```vagrant up``` Will start the vagrant server

Add ```192.168.33.10 vagrant.local``` to your hosts file

### NPM and VueJs

This boiler plate is bundled with VueJS as it's javascript framework

#### install
``npm install`` install node dependencies 

``npm run watch`` Build and watch for file changes

``npm run development`` Build with dev dependencies.  This mode also puts vue into development mode
making Vue dev tools available.

``npm run production`` Build for production. Minify files and put vue into production mode.

### Slim PHP

Slim is the base framework I use for all my projects.  It's simple and to the point.  It does what it needs to do and just that. 

More information can be found [here](http://www.slimframework.com/) about the slim php framework slim php.  Routes are registered in Library\App.  Controllers must extend the 
abstract base controller

### Twig

[Twig](https://twig.symfony.com/) is my templating engine of choice.  Again, no reason to re-invent the wheel. 

### Docker 

Ready to build a docker container out of the box.
If you need any customizations refer to the DockerFile and /bin/EntryPoint.sh

`docker build .` Will start the docker build process
