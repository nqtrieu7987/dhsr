FROM keymetrics/pm2:latest-alpine
VOLUME ["/src"]
# Bundle APP files
COPY src src/
#COPY package.json .
#COPY pm2.json .

# Install app dependencies
ENV NPM_CONFIG_LOGLEVEL warn
WORKDIR src
RUN npm install --production

# Show current folder structure in logs
#RUN ls -al -R
CMD [ "pm2-runtime", "start", "/src/pm2.json" ]
