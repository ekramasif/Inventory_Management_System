timestamps { 
  podTemplate(
    label: 'agent-inventory-app',
    containers: [
      containerTemplate(name: 'docker', image: 'docker:18.06', command: 'cat', ttyEnabled: true),
    ],
    volumes: [
      hostPathVolume(
        mountPath: '/var/run/docker.sock', 
        hostPath: '/var/run/docker.sock'
        ),
    ],
  ) {
    node ('agent-inventory-app') {
      stage('Get latest version of code') {
        git branch: 'feature/kubernetes', url: 'https://github.com/Jabrique/Inventory_Management_System.git'
      }
      
      container('docker') {
          stage('build image') {
            sh 'docker build -t inventory-app/inventory-app:v .'
          }
          
          stage('push image to harbor') {
            withCredentials([usernamePassword(credentialsId: 'Harbor', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                // Login to Harbor using credentials
                sh 'echo ${DOCKER_PASS} | docker login -u ${DOCKER_USER} --password-stdin harbor.internskomda.cloud'
    
                // Tag the image
                sh 'docker tag inventory-app/inventory-app:v harbor.internskomda.cloud/inventory-app/inventory-app:v${BUILD_NUMBER}'
    
                // Push the image to Harbor
                sh 'docker push harbor.internskomda.cloud/inventory-app/inventory-app:v${BUILD_NUMBER}'
            }
          }
      }
      
      stage('update deployment manifest on github') {
        withCredentials([string(credentialsId: 'github', variable: 'GITHUB_TOKEN')]) {
                sh '''
                    git config user.email "shironimex@gmail.com"
                    git config user.name "Jabrique"
                    sed -i "s#image: harbor.internskomda.cloud/inventory-app/inventory-app:v.*#image: harbor.internskomda.cloud/inventory-app/inventory-app:v${BUILD_NUMBER}#" kubernetes/inventory-app/inventory.yml
                    git add .
                    git commit -m "ci: Update deployment inventory-app image to version ${BUILD_NUMBER}"
                    git push https://${GITHUB_TOKEN}@github.com/Jabrique/Inventory_Management_System HEAD:feature/kubernetes
                '''
            }
      }
    }
  }
}