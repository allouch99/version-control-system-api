FROM ubuntu:22.04

SHELL ["/bin/bash", "-c"]
ENV TERM=xterm
WORKDIR /var/www/html

RUN apt-get update && apt-get upgrade -y \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git  libcap2-bin libpng-dev  dnsutils librsvg2-bin   \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update && apt-get install -y 

RUN /bin/bash -c "$(curl -fsSL https://php.new/install/linux)" && source /root/.bashrc 
ENV PATH=${PATH}":/root/.config/herd-lite/bin"
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN  composer config -g process-timeout 1200

ENTRYPOINT ["/bin/bash","-c"]
CMD ["composer install --ignore-platform-reqs && php artisan key:generate && php artisan serve --host=0.0.0.0 --port=8000"]