# docker-web-app
Creating a Docker Image and Pushing To Docker hub

The following steps was taking:
1. Create a Repo on Githup name "docker-web-app"
2. Clone the Repo to your machine using this Command : git clone https://github.com/Ayorock/docker-web-app
3. CD into the Directory with this command : cd docker-web-app
4. Create the directory "version1" and "version2" : mkdir version1 version2
5. Create file index.html with nano editor : nano index.html  ( Ctrl + X and y then Enter to save)
6. Create dockerfile : nano dockerfile
7. Build docker iumage with the command : docker build -t azubi-docker-form .
8. Run a container from the image you just built: docker run -d -p 8080:80 azubi-docker-form
9. Open your web browser and go to http://localhost:8080 to see your login form.
10. To push the Docker image to Docker Hub, follow these steps:

    a. Create a Docker Hub Account (if you don't already have one)
	b. Go to Docker Hub and sign up for an account.
	c. Log in to Docker Hub from the command line:
		docker login (Enter your Docker Hub username and password when prompted)
	d. Tag the Docker image : 
		docker tag azubi-docker-form aborisade/azubi-docker-form:latest
	e. Push the docker image to Docker hub : 
		docker push aborisade/azubi-docker-form:latest



