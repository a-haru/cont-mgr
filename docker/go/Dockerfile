FROM golang:1.15-alpine

RUN apk update; \
    apk add git;

ENV GOWORKDIR /usr/src/go

ENV DB_CONNECTION mysql
ENV DB_HOST db
ENV DB_PORT 3306
ENV DB_DATABASE content_manager
ENV DB_USERNAME root
ENV DB_PASSWORD root

WORKDIR $GOWORKDIR
