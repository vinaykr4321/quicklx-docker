#### First install Docker

`sudo apt-get install docker docker-compose`

#### Grant required permissions

This will create a group   
`sudo groupadd docker`

Add the user to the group  
`sudo usermod -aG docker $USER`

Exit the shell/terminal and login again.

Test the installation buy running   
`docker run hellow-world`

 
