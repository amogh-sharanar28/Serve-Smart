pipeline {
  agent any

  stages {
    stage('Clone Repo') {
      steps {
        git 'https://github.com/amogh-sharanar28/Serve-Smart.git'
      }
    }

    stage('Deploy to EC2') {
      steps {
        sshagent(['ec2-key']) {
          sh '''
            ssh -o StrictHostKeyChecking=no ubuntu@3.110.222.128 "
              cd Serve-Smart &&
              git pull &&
              docker-compose down &&
              docker-compose up -d --build
            "
          '''
        }
      }
    }
  }
}

