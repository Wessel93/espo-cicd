### setup
setup git repo in dev VM: ssh into the VM
```
cd /var/www/espocrm/data/espocrm
sudo git config --global init.defaultBranch dev
sudo git config --global credential.helper store
--> add your github username and password
---> N.B. as password use a token that never expires #safety
sudo git init
sudo git config --global --add safe.directory /var/www/espocrm/data/espocrm
sudo git remote add origin https://github.com/jmargutt/espo-cicd.git
sudo git checkout -b dev
sudo git push origin -u dev
```