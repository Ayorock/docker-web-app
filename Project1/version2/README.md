Pushing a Docker Image to ECR
A. Create a Public Repo on AWS
    aws ecr create-repository --repository-name my-repo --region us-west-2


B. Install the AWS Command Line Interface (CLI) on an Ubuntu machine, follow these steps:
    First, update the package index on your Ubuntu machine:
        sudo apt update
    Install unzip and curl if they are not already installed:
        sudo apt install unzip curl -y
    Use the curl command to download the AWS CLI bundle:
        curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
    Unzip the AWS CLI bundle:
        unzip awscliv2.zip
    Run the AWS CLI installer:Run the installation script:
        sudo ./aws/install
    Verify the installation
        aws --version
            aws-cli/2.x.x Python/3.x.x Linux/4.x.x botocore/2.x.x
    After installing the AWS CLI, you need to configure it with your AWS credentials. Run the following command:
        aws configure
            You will be prompted to enter your AWS Access Key ID, Secret Access Key, region, and output format. If you don't have your credentials yet, you can create them in the AWS Management Console under "IAM" (Identity and Access Management).
    Cleanup
        After the installation is complete, you can remove the downloaded and extracted files:
            rm awscliv2.zip
            rm -rf aws

C. Authenticate Docker to ECR:
    aws ecr get-login-password --region us-west-2 | docker login --username AWS --password-stdin 123456789012.dkr.ecr.us-west-2.amazonaws.com

D. Tag the Docker Image
    docker tag my-app:latest 123456789012.dkr.ecr.us-west-2.amazonaws.com/my-repo:latest
E. Push the Docker Image to ECR:
    docker tag my-app:latest 123456789012.dkr.ecr.us-west-2.amazonaws.com/my-repo:latest

STEP 2 : RUN on ECS 
    Open the ECS Console:
    Go to the Amazon ECS console.
Create a new ECS cluster:  aws ecs create-cluster --cluster-name my-ecs-cluster --region your-region
    Click on "Clusters" in the left-hand menu.
    Click the "Create Cluster" button.
    Choose "Networking only" and click "Next step".
    Provide a name for your cluster (e.g., my-ecs-cluster).
    Click "Create".
Create Task Definition:
    Open the Task Definitions page:
    Click on "Task Definitions" in the left-hand menu.
    Click the "Create new Task Definition" button.
    Choose Fargate:
        Select "Fargate" and click "Next step".
Configure the task and container definitions:
    Give your task definition a name (e.g., my-task-def).
    For "Task execution role", select an existing IAM role or create a new one with the (aws iam create-role --role-name ecsTaskExecutionRole --assume-role-policy-document file://task-execution-assume-role.json
)                   {
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Principal": {
                "Service": "ecs-tasks.amazonaws.com"
            },
            "Action": "sts:AssumeRole"
        }
    ]
}


    AmazonECSTaskExecutionRolePolicy policy attached. (aws iam attach-role-policy --role-name ecsTaskExecutionRole --policy-arn arn:aws:iam::aws:policy/service-role/AmazonECSTaskExecutionRolePolicy
)
        {
  "family": "my-task-def",
  "executionRoleArn": "arn:aws:iam::your-account-id:role/ecsTaskExecutionRole",
  "networkMode": "awsvpc",
  "containerDefinitions": [
    {
      "name": "my-app-container",
      "image": "your-account-id.dkr.ecr.your-region.amazonaws.com/your-repository-name:tag",
      "essential": true,
      "portMappings": [
        {
          "containerPort": 80,
          "hostPort": 80,
          "protocol": "tcp"
        }
      ]
    }
  ],
  "requiresCompatibilities": [
    "FARGATE"
  ],
  "cpu": "256",
  "memory": "512"
}
    aws ecs register-task-definition --cli-input-json file://task-definition.json

    Under "Container definitions", click "Add container".
Configure your container:
    Container name: my-app-container
    Image: your-account-id.dkr.ecr.your-region.amazonaws.com/your-repository-name:tag
    Port mappings: add port 80 (assuming your web app runs on port 80).
Adjust other settings as needed (e.g., memory limits).
Create the task definition:
    Click "Create".
Run the Task : 
    aws ec2 create-security-group --group-name my-security-group --description "My security group" --vpc-id your-vpc-id
    aws ec2 authorize-security-group-ingress --group-id your-security-group-id --protocol tcp --port 80 --cidr 0.0.0.0/0
    aws ec2 authorize-security-group-ingress --group-id your-security-group-id --protocol tcp --port 80 --cidr 0.0.0.0/0
    aws ecs run-task --cluster my-ecs-cluster --launch-type FARGATE --task-definition my-task-def --network-configuration "awsvpcConfiguration={subnets=[your-subnet-id],securityGroups=[your-security-group-id],assignPublicIp=ENABLED}"
    aws ecs describe-tasks --cluster my-ecs-cluster --tasks your-task-id
    aws ecs create-service --cluster my-ecs-cluster --service-name my-service --task-definition my-task-def --desired-count 1 --launch-type FARGATE --network-configuration "awsvpcConfiguration={subnets=[your-subnet-id],securityGroups=[your-security-group-id],assignPublicIp=ENABLED}"
    aws application-autoscaling register-scalable-target --service-namespace ecs --resource-id service/my-ecs-cluster/my-service --scalable-dimension ecs:service:DesiredCount --min-capacity 1 --max-capacity 10
    aws application-autoscaling put-scaling-policy --service-namespace ecs --scalable-dimension ecs:service:DesiredCount --resource-id service/my-ecs-cluster/my-service --policy-name my-scaling-policy --policy-type TargetTrackingScaling --target-tracking-scaling-policy-configuration "TargetValue=50.0,PredefinedMetricSpecification={PredefinedMetricType=ECSServiceAverageCPUUtilization},ScaleOutCooldown=60,ScaleInCooldown=60"

Go to your ECS cluster:
    Click on "Clusters" in the left-hand menu.
    Select your cluster (my-ecs-cluster).
Run a new task:
    Click the "Tasks" tab.
    Click the "Run new task" button.
    Launch type: select "Fargate".
    Task definition: select your newly created task definition.
    Platform version: select the latest version.
    Cluster: select your cluster.
    Cluster VPC: select the VPC where your ECS tasks will run.
    Subnets: select at least one subnet.
    Security groups: ensure your security group allows inbound traffic on port 80.
    Click "Run task".
Verify the Application
    View running tasks:
    In the ECS console, go to your cluster and click on the "Tasks" tab to see your running tasks.
Get public IP:
    Select your running task.
    In the "Details" tab, find the "Network" section.
    Note the public IP address of your task.
Access your web application:
    Open a web browser and navigate to the public IP address of your task.
    Set Up Auto Scaling (Optional)
    Open the Auto Scaling page:
    In the ECS console, click on "Clusters" in the left-hand menu.
    Select your cluster.
    Click on the "Auto Scaling" tab.
    Create a new Auto Scaling policy:

    Click the "Create" button.
    Configure your Auto Scaling policy to automatically adjust the number of running tasks based on CPU or memory utilization.