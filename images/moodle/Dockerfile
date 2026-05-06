ARG PHP_FPM_NGINX_TAG=8.3-ubuntu24.04-mw25.10.5.1

FROM dockerregistry.metaways.net/se/php-fpm-nginx-docker/php-fpm-nginx:${PHP_FPM_NGINX_TAG} as moodle
ARG MOODLE_ROOT=/var/www/moodle
ARG MOODLE_DATA=/var/lib/moodle
ARG MOODLE_LOCAL_CACHE=/run/moodle/cache

USER root

ARG PHP_VERSION=8.3
RUN apt-get update \
 && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    php${PHP_VERSION}-iconv \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-tokenizer \
    php${PHP_VERSION}-soap \
    php${PHP_VERSION}-ctype \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-simplexml \
    php${PHP_VERSION}-dom \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-xmlreader \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-fileinfo \
    php${PHP_VERSION}-exif \
    php${PHP_VERSION}-pgsql \
    graphviz \
    aspell \
    ghostscript \
    locales
    
COPY images/moodle/etc/nginx/site.conf /etc/nginx/sites-available/default
COPY images/moodle/etc/php/default/default/conf.d/30-moodle.ini etc/php/8.3/fpm/conf.d/30-moodle.ini
COPY images/moodle/etc/php/default/default/conf.d/30-moodle.ini etc/php/8.3/cli/conf.d/30-moodle.ini

ARG APP_VERSION
RUN export MOODLE_BRANCH=$(printf "%d%02d" $(echo ${APP_VERSION} | cut -d '.' -f 1)  $(echo ${APP_VERSION} | cut -d '.' -f 2)) \
 && export URL=https://packaging.moodle.org/stable${MOODLE_BRANCH}/moodle-${APP_VERSION}.tgz \
 && echo downloading moodle from ${URL} \
 && wget ${URL} -O /tmp/moodle.tgz \
 && echo "$(wget  ${URL}.sha256 -O - | cut -d ' ' -f 2)  /tmp/moodle.tgz" | sha256sum -c \
 && tar --no-same-owner -xzf /tmp/moodle.tgz -C $(dirname ${MOODLE_ROOT}) \
 && rm /tmp/moodle.tgz

COPY --chmod=0640 --chown=root:www-data images/moodle/etc/moodle/config.php ${MOODLE_ROOT}/config.php

RUN mkdir -p ${MOODLE_DATA} ${MOODLE_LOCAL_CACHE} \
 && chown www-data:www-data ${MOODLE_DATA} ${MOODLE_LOCAL_CACHE} \
 && chmod 770 ${MOODLE_DATA} ${MOODLE_LOCAL_CACHE}

ENV MOODLE_ROOT=${MOODLE_ROOT}
ENV MOODLE_DATA=${MOODLE_DATA}
ENV MOODLE_LOCAL_CACHE=${MOODLE_LOCAL_CACHE}

VOLUME [ ${MOODLE_DATA} ${MOODLE_LOCAL_CACHE} ]
WORKDIR ${MOODLE_ROOT}/

USER www-data



FROM moodle as moodle-custom

USER root

RUN locale-gen de_DE.utf8

# https://moodle.org/plugins/mod_checklist
RUN wget https://moodle.org/plugins/download.php/41491/mod_checklist_moodle52_2026042400.zip -O /tmp/mod_checklist.zip \
 && unzip /tmp/mod_checklist.zip -d ${MOODLE_ROOT}/mod/ \
 && rm /tmp/mod_checklist.zip

# https://moodle.org/plugins/mod_customcert
RUN wget https://moodle.org/plugins/download.php/40562/mod_customcert_moodle45_2024042217.zip -O /tmp/mod_customcert.zip \
 && unzip /tmp/mod_customcert.zip -d ${MOODLE_ROOT}/mod/ \
 && rm /tmp/mod_customcert.zip

# https://moodle.org/plugins/mod_kanban
RUN wget https://moodle.org/plugins/download.php/41661/mod_kanban_moodle52_2026050501.zip -O /tmp/mod_kanban.zip \
 && unzip /tmp/mod_kanban.zip -d ${MOODLE_ROOT}/mod/ \
 && rm /tmp/mod_kanban.zip

# https://moodle.org/plugins/mod_onlyofficeeditor
RUN wget https://moodle.org/plugins/download.php/37120/mod_onlyofficeeditor_moodle50_2025080402.zip -O /tmp/mod_onlyofficeeditor.zip \
 && unzip /tmp/mod_onlyofficeeditor.zip -d ${MOODLE_ROOT}/mod/ \
 && rm /tmp/mod_onlyofficeeditor.zip

# https://moodle.org/plugins/assignsubmission_onlyoffice
RUN wget https://moodle.org/plugins/download.php/37119/assignsubmission_onlyoffice_moodle50_2025080401.zip -O /tmp/assignsubmission_onlyoffice.zip \
 && unzip /tmp/assignsubmission_onlyoffice.zip -d ${MOODLE_ROOT}/mod/assign/submission/ \
 && rm /tmp/assignsubmission_onlyoffice.zip

# https://moodle.org/plugins/filter_filtercodes
RUN wget https://moodle.org/plugins/download.php/38432/filter_filtercodes_moodle51_2025102700.zip -O /tmp/filter_filtercodes.zip \
 && unzip /tmp/filter_filtercodes.zip -d ${MOODLE_ROOT}/filter/ \
 && rm /tmp/filter_filtercodes.zip

# https://moodle.org/plugins/format_tiles
RUN wget https://moodle.org/plugins/download.php/39555/format_tiles_moodle45_2025070355.zip -O /tmp/format_tiles.zip \
 && unzip /tmp/format_tiles.zip -d ${MOODLE_ROOT}/course/format/ \
 && rm /tmp/format_tiles.zip

RUN cdn_url=$(grep -R https://cdn.jsdelivr.net/npm/mathjax filter/mathjaxloader/settings.php | cut -d "'" -f 2) \
 &&  wget $cdn_url -O ${MOODLE_ROOT}/MathJax.js

USER www-data