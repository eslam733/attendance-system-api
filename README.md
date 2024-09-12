# 📚 Table of Contents

- [🐋 Installing Docker on Ubuntu](#installing-docker-on-ubuntu)
- [🛠 Building the Docker Image](#building-the-docker-image)
- [🎯 Running the Docker Container](#running-the-docker-container)
- [🔧 Accessing the Bash Shell in the Docker Container](#accessing-the-bash-shell-in-the-docker-container)

## Installing Docker on Ubuntu
Follow these steps to install Docker on Ubuntu.

### Step 1: Update Your Package List (optional)

First, update your package list to ensure you have the latest information about available packages:

```bash
sudo apt-get update
```

### Step 2: Install Prerequisite Packages
Install the necessary packages to allow apt to use repositories over HTTPS:
```
sudo apt-get install apt-transport-https ca-certificates curl software-properties-common
```

### Step 3: Add Docker’s Official GPG Key
```
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
```
### Step 4: Add Docker Repository to APT Sources
```
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
```

### Step 5: Install Docker
```
sudo apt-get install docker-ce
```

### Step 6: Verify Docker Installation
```
sudo docker --version
```

### Step 7: Run Docker as a Non-Root User (Optional)
```
sudo groupadd docker
sudo usermod -aG docker $USER
```
### Step 8: install docker-compose
```
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

## Building the Docker Image

### Step 1: Clone the Repository
```
git clone git@github.com:eslam733/attendance-system-api.git
cd platform
```

### 📦 First-Time Setup: Adding the MySQL Initialization File
Add your MySQL initialization SQL file to this path with the name init_db.sql:
```
docker-compose/mysql/init_db.sql
```
🚨 Important Note
This step is only required the first time you set up the project or if you need to reinitialize the database.

### Step 2: Build the Docker Image with Docker Compose

```
docker-compose build
```

## Running the Docker Container

### Step 1: Run the Docker Container
```
docker-compose up -d
```
### Step 2: Verify the Container is Running
```
docker-compose ps
```

## Accessing the Bash Shell in the Docker Container
```
docker-compose exec app /bin/bash
```
## Migrate the database
```
php artisan migrate
```

### Access the platform:
`http://localhost:8000/`
### Access the database here:
`http://127.0.0.1:8082/`

###  Happy coding! 🚀
