# Build mix files
FROM node:14-alpine as build-stage
WORKDIR /app
RUN npm install
COPY ./package.json /app
COPY ./package-lock.json /app
COPY ./ /app
RUN npm run production

# Only copy built-files and serve it
FROM nginx:1.14.2-alpine as deploy-stage
COPY ./docker/nginx/awa.conf /etc/nginx/conf.d/default.conf
COPY --from=build-stage /app /usr/share/nginx/html