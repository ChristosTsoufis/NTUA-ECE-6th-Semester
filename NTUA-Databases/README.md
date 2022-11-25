# Databases

### Project for semester project in PHP/mySQL for Databases course at [ECE](https://www.ece.ntua.gr/en), [NTUA](https://www.ntua.gr/en)
Academic year 2019-2020 (Professor [Kantere](https://www.ece.ntua.gr/en/staff/210))

## **Steps to follow in order to run the project**

## Docker Environment Setup

1. Ακολουθείτε τον οδηγό  [DockerToolbox] και εγκαθιστάτε το docker
2. Κατεβάζετε το [GIT]
3. Ανοίγετε το GIT bash και κανετε clone το repo στον επιθυμητό φάκελο
    ```bash
    $ cd Desktop/
    $ git clone https://github.com/konpap99/Databases_Project.git
    ```
4. Ανοίγετε νέο terminal και τρέχετε το docker
    ```sh
    cd Desktop/Databases_Project
    docker-machine start default
    docker-compose up --build -d
    ```
5. Αφού κατέβουν όλα τα απαραίτητα για το docker αρχεία, είσαστε έτοιμοι να ξεκινήσετε 
6. Πληκτρολογείτε την διεύθυνση 
    ```sh 
    192.168.99.100:8080 #για phpMyAdmin
    #Username: root, Password: root
    ``` 
    ή
    
    ```sh 
    192.168.99.100:8888 #για UI
    ```
7. Για να κλείσετε το docker τρέχετε docker-compose stop
<br />

## Windows Pro / Enterprise

** Για έκδοση Windows Pro ή Enterprise κατεβάζετε το docker από εδώ [Docker] <br />
Ίδια διαδικασία αλλά αλλάζουν οι διευθύνσεις στον browser
```sh 
localhost:8080 #για phpMyAdmin
``` 
ή
```sh 
localhost:8888 #για UI
```

[DockerToolbox]: <https://docs.docker.com/toolbox/toolbox_install_windows/>
[GIT]: <https://git-scm.com/downloads>
[Docker]: <https://hub.docker.com/editions/community/docker-ce-desktop-windows>

### Team Members

| Name |
|-------------------------|
| Konstantinos Papaioannou |
| Vasileios Theodoropoulos|
| Christos Tsoufis |