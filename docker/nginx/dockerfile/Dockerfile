FROM nginx:1.19-alpine

ARG STAGE=dev

ENV TZ=Asia/Tokyo

COPY docker/nginx/config/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/config/default.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/config/fastcgi_params /etc/nginx/
COPY docker/nginx/config/security.conf /etc/nginx/
COPY --chown=nginx:0 public /var/www/html/public

RUN chown -R nginx:0 /etc/nginx /var/cache/nginx \
    && chmod -R g+w /etc/nginx /var/cache/nginx

USER nginx
ENTRYPOINT []
# non root userなのでwell-known portは割り当てない
EXPOSE 8080
STOPSIGNAL SIGTERM
CMD ["nginx", "-g", "daemon off;"]