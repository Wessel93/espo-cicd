### setup
setup git repo in dev VM: ssh into the VM
```
cd /var/www/espocrm/data/espocrm
sudo git config --global init.defaultBranch dev
sudo git config --global credential.helper store
sudo git init
sudo git config --global --add safe.directory /var/www/espocrm/data/espocrm
sudo git remote add origin https://github.com/jmargutt/espo-cicd.git
sudo git checkout -b dev
sudo git add data/config.php
sudo git commit -m "first commit"
sudo git push -u origin dev
--> add your github username and password
---> N.B. as password use a token that never expires #safety
```
add user to sudoers list
1. Edit sudoers file: `sudo nano /etc/sudoers`
2. Find a line which contains `includedir /etc/sudoers.d`
3. Below that line add: `username ALL=(ALL) NOPASSWD: ALL`, where `username` will be your passwordless sudo username
4. Save your changes: CTRL+O ENTER, CTRL+X ENTER


setup git repo in prod VM: ssh into the VM
```
cd /var/www/espocrm/data/espocrm
sudo git config --global init.defaultBranch main
sudo git config --global credential.helper store
sudo git init
sudo git config --global --add safe.directory /var/www/espocrm/data/espocrm
sudo git remote add origin https://github.com/jmargutt/espo-cicd.git
sudo git pull -u origin main
--> add your github username and password
---> N.B. as password use a token that never expires #safety
```