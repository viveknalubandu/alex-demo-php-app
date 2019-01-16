pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        sh '''set +e

# Run Unit Test
/var/jenkins_home/phpunit /var/jenkins_home/unitTest/ConfigTest.php
result=$?

if [[ ${result} -eq 0 ]]; then
  printf "Unit Tests Succeeded\\n"
  # Build artifact and send to temp location
  rm -f /tmp/alexwebapp_*
  artifact="alexwebapp_${GIT_COMMIT}.tgz"
  tar -czvf /tmp/${artifact} ./ --exclude=\'.[^/]*\'
  ssh ubuntu@172.31.21.64 \'sudo rm -rf /tmp/*.tgz\'
  scp /tmp/${artifact} ubuntu@172.31.21.64:/tmp #webserver for demo purposes
  printf "${artifact} sent to temporary location for testing\\n"
  rm -f /tmp/${artifact}
else
  printf "Unit Tests Failed\\n"
fi

exit ${result}'''
      }
    }
    stage('Dev Deploy') {
      steps {
        sh '''set +e
# Deploy to Dev server for testing

ssh ubuntu@172.31.21.64 \'artifact_location=$(find /tmp -name "alexwebapp_*.tgz"); artifact=$(echo ${artifact_location#/tmp/alexwebapp_}); git_commit=$(echo ${artifact%.tgz}); sudo mkdir -p /var/www/dev/${git_commit}; sudo tar -xzvf ${artifact_location} -C /var/www/dev/${git_commit} && sudo chown -R root:root /var/www/dev/${git_commit}; sudo unlink /var/www/dev/latest && sudo ln -s /var/www/dev/${git_commit} /var/www/dev/latest; sudo service nginx restart\'

if [[ $? -eq 0 ]]; then
  printf "Deployed to Dev"
  exit 0
else
  printf "Failed to deploy to Dev"
  exit 1
fi'''
      }
    }
    stage('Dev Test') {
      steps {
        sh '''# Check status code on Dev site

status_code=$(curl -o /dev/null -s -w "%{http_code}" http://demo-dev.sndevops.xyz/)

if [[ ${status_code} -eq 200 ]]; then
  printf "Succeeded Dev test\\n"
  exit 0
else
  printf "Failed Dev test\\n"
  exit 1
fi'''
      }
    }
    stage('Prod Deploy') {
      steps {
        sh '''set +e
# Deploy to Production

ssh ubuntu@172.31.21.64 \'artifact_location=$(find /tmp -name "alexwebapp_*.tgz"); artifact=$(echo ${artifact_location#/tmp/alexwebapp_}); git_commit=$(echo ${artifact%.tgz}); sudo mkdir -p /var/www/${git_commit}; sudo tar -xzvf ${artifact_location} -C /var/www/${git_commit} && sudo chown -R root:root /var/www/${git_commit}; sudo unlink /var/www/latest && sudo ln -s /var/www/${git_commit} /var/www/latest; sudo service nginx restart; sudo rm -rf /tmp/*.tgz\'

if [[ $? -eq 0 ]]; then
  printf "Deployed to Production"
  exit 0
else
  printf "Failed to deploy to Production"
  exit 1
fi'''
      }
    }
    stage('Prod Test') {
      steps {
        sh '''# Check status code on Production site

status_code=$(curl -o /dev/null -s -w "%{http_code}" http://demo.sndevops.xyz)

if [[ ${status_code} -eq 200 ]]; then
  printf "Succeeded production test\\n"
  exit 0
else
  printf "Failed production test\\n"
  exit 1
fi

'''
      }
    }
  }
}