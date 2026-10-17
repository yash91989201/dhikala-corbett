FROM nginx:alpine

# nginx ignores .htaccess entirely, which sidesteps the permission trouble
# those files caused under Apache.
RUN rm -f /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Bake the site in so the image is self-contained.
# docker-compose bind-mounts the same folder over this for live editing.
COPY . /usr/share/nginx/html/

EXPOSE 80
