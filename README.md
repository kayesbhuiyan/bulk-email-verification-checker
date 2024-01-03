# bulk-email-verification-checker
These are the steps you can use in linux cli: 
1. create a dir called Trumail-rack.
2. typed  CD Trumail-rack 
3. Create a file called docker-compose.yml
4. paste this code on yml file or follow this link: https://hub.docker.com/r/truemail/truemail-rack

##Example docker-compose.yml: 
version: "3.7"

services:
  truemail:
    image: truemail/truemail-rack:v0.5.0 # for latest version you can use just truemail/truemail-rack:latest
    ports:
      - 9292:9292
    environment:
      VERIFIER_EMAIL: addYourEmailHere@gmail.com
      ACCESS_TOKENS: xxxyyy
    tty: true

5.run your docker
6.Type on this command on command terminal "dir docker-compose up -d" 
7. Run the php script on your pc the you will get the reasult.



Note : Your can learn more from here https://truemail-rb.org/truemail-rack/  
