# flug.dk

This is the github project that contains the source of Flug's current homepage. 

# Docker
One way to run the project on your own computer is though Docker. If you have a computer with Docker already install and have cloned this project from github then you can run this website on you own computer using these few steps.

```bash
docker build --rm --tag flugdk/flug-dk . # This assumes that your current folder is the folder with the Dockerfile
docker run -d -p 80:80 --name flug-dk flugdk/flug-dk
```


