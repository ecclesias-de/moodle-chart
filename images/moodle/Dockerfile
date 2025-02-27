ARG PHP_FPM_NGINX_TAG=8.3-ubuntu24.04-mw25.1.2
FROM dockerregistry.metaways.net/se/php-fpm-nginx-docker/php-fpm-nginx:${PHP_FPM_NGINX_TAG} as moodle
ARG MOODLE_ROOT=/var/www/moodle
ARG MOODLE_DATA=/var/lib/moodle

USER root

ARG PHPVERSION=8.3
RUN apt-get update \
 && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    php${PHPVERSION}-iconv \
    php${PHPVERSION}-mbstring \
    php${PHPVERSION}-curl \
    php${PHPVERSION}-tokenizer \
    php${PHPVERSION}-soap \
    php${PHPVERSION}-ctype \
    php${PHPVERSION}-zip \
    php${PHPVERSION}-gd \
    php${PHPVERSION}-simplexml \
    php${PHPVERSION}-dom \
    php${PHPVERSION}-xml \
    php${PHPVERSION}-xmlreader \
    php${PHPVERSION}-intl \
    php${PHPVERSION}-fileinfo \
    php${PHPVERSION}-exif \
    graphviz \
    aspell \
    ghostscript
    
RUN sed -i 's/.*max_input_vars =.*/max_input_vars = 5000/' /etc/php/8.3/fpm/php.ini \
 && sed -i 's/.*max_input_vars =.*/max_input_vars = 5000/' /etc/php/8.3/cli/php.ini

COPY images/moodle/etc/nginx/site.conf /etc/nginx/sites-available/default

# see https://download.moodle.org/releases/latest/
ARG APP_VERSION
RUN export MOODLE_BRANCH=$(printf "%d%02d" $(echo ${APP_VERSION} | cut -d '.' -f 1)  $(echo ${APP_VERSION} | cut -d '.' -f 2)) \
 && wget https://packaging.moodle.org/stable${MOODLE_BRANCH}/moodle-${APP_VERSION}.tgz -O /tmp/moodle.tgz \
 && echo "$(wget  https://packaging.moodle.org/stable${MOODLE_BRANCH}/moodle-${APP_VERSION}.tgz.sha256 -O - | cut -d ' ' -f 2)  /tmp/moodle.tgz" | sha256sum -c \
 && tar --no-same-owner -xzf /tmp/moodle.tgz -C $(dirname ${MOODLE_ROOT}) \
 && rm /tmp/moodle.tgz

VOLUME [ ${MOODLE_DATA} ]
USER www-data

COPY images/moodle/etc/moodle/config.php /var/www/moodle/config.php

FROM moodle as moodle-with-plugins

USER root

# https://moodle.org/plugins/mod_checklist
RUN wget https://moodle.org/plugins/download.php/33532/mod_checklist_moodle45_2024101900.zip -O /tmp/mod_checklist.zip \
 && unzip /tmp/mod_checklist.zip -d ${MOODLE_ROOT}/mod/ \
 && rm /tmp/mod_checklist.zip

# https://moodle.org/plugins/mod_customcert
RUN wget https://moodle.org/plugins/download.php/34993/mod_customcert_moodle45_2024042208.zip -O /tmp/mod_customcert.zip \
 && unzip /tmp/mod_customcert.zip -d ${MOODLE_ROOT}/mod/ \
 && rm /tmp/mod_customcert.zip

# https://moodle.org/plugins/mod_kanban
RUN wget https://moodle.org/plugins/download.php/34968/mod_kanban_moodle45_2025022001.zip -O /tmp/mod_kanban.zip \
 && unzip /tmp/mod_kanban.zip -d ${MOODLE_ROOT}/mod/ \
 && rm /tmp/mod_kanban.zip

# https://moodle.org/plugins/mod_onlyofficeeditor
RUN wget https://moodle.org/plugins/download.php/33886/mod_onlyofficeeditor_moodle45_2024111301.zip -O /tmp/mod_onlyofficeeditor.zip \
 && unzip /tmp/mod_onlyofficeeditor.zip -d ${MOODLE_ROOT}/mod/ \
 && rm /tmp/mod_onlyofficeeditor.zip

# https://moodle.org/plugins/assignsubmission_onlyoffice
RUN wget https://moodle.org/plugins/download.php/33887/assignsubmission_onlyoffice_moodle45_2024111300.zip -O /tmp/assignsubmission_onlyoffice.zip \
 && unzip /tmp/assignsubmission_onlyoffice.zip -d ${MOODLE_ROOT}/mod/assign/submission/ \
 && rm /tmp/assignsubmission_onlyoffice.zip

# https://moodle.org/plugins/filter_filtercodes
RUN wget https://moodle.org/plugins/download.php/33419/filter_filtercodes_moodle45_2024100701.zip -O /tmp/filter_filtercodes.zip \
 && unzip /tmp/filter_filtercodes.zip -d ${MOODLE_ROOT}/filter/ \
 && rm /tmp/filter_filtercodes.zip

# https://moodle.org/plugins/format_tiles
RUN wget https://moodle.org/plugins/download.php/34231/format_tiles_moodle45_2024121252.zip -O /tmp/format_tiles.zip \
 && unzip /tmp/format_tiles.zip -d ${MOODLE_ROOT}/course/format/ \
 && rm /tmp/format_tiles.zip

USER www-data