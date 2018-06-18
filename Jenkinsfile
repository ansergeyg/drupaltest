node('JenkinsSlave') {
    stage('Clone') {
        // Clone the repo
        checkout([
            $class: 'GitSCM',
            branches: [
                [name: '${ghprbActualCommit}']
            ],
            doGenerateSubmoduleConfigurations: false,
            extensions: [],
            submoduleCfg: [],
            userRemoteConfigs: [
                [
                    credentialsId: 'CnectBotUP',
                    refspec: '+refs/pull/*:refs/remotes/origin/pr/*',
                    url: 'https://github.com/cnect-web/d8p'
                ]
            ]
        ])
    }
    stage('Build') {
        sh '''
            cd ${WORKSPACE} &&
            composer install &&
            ./bin/robo project:install -o "project.root: ${WORKSPACE}" -o "database.password: ${MYSQL_PASSWORD}" &&
            ./bin/robo project:setup-behat -o "project.root: ${WORKSPACE}" -o "database.password: ${MYSQL_PASSWORD}"
            '''
    }
    stage('Install') {
        sh '''
            sudo ln -s ${WORKSPACE}/web /var/www/html/ &&
            sudo chmod 0777 ${WORKSPACE}/web/sites/default/files/
            '''
    }
    stage('Test') {
        sh '''cd ${WORKSPACE}/tests &&
        ./behat
        '''
    }
    stage('Ready') {
        sh '''cd ${WORKSPACE}/ &&
        ./bin/robo ec2:hostname
        '''
    }
}
