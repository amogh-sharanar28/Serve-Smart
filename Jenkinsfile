pipeline {
    agent any

    environment {
        EC2_USER = "ubuntu"
        EC2_HOST = "13.203.104.163"
        SSH_KEY_ID = "ec2-key"  // This must match the Jenkins credential ID
    }

    stages {
        stage('Clone Repository') {
            steps {
                git 'https://github.com/amogh-sharanar28/Serve-Smart.git'
            }
        }

        stage('Deploy on EC2') {
            steps {
                sshagent([SSH_KEY_ID]) {
                    sh """
                    ssh -o StrictHostKeyChecking=no $EC2_USER@$EC2_HOST '
                        cd Serve-Smart &&
                        git pull &&
                        docker-compose down &&
                        docker-compose up -d --build
                    '
                    """
                }
            }
        }
    }
}
