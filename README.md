# Symfony 5.4 with AWS Cognito

## Core Specifications

- PHP 8.2+ Symfony 5.4.x
- Core packages:
  - aws/aws-sdk-php
- Database
  - MySQL

# Docker Setup

In order to be successful on this project you should follow a these guidelines:

```
1. Open terminal
2. cp example.env .env
```
Add these AWS Credentials to setup AWS Cognito

```
COGNITO_REGION=*****
COGNITO_USER_POOL_ID=*****
COGNITO_CLIENT_ID=******
COGNITO_CLIENT_SECRET=******
AWS_ACCESS_KEY_ID=*****
AWS_SECRET_ACCESS_KEY=********
```
## Run app locally using docker

```
1. Make sure you have docker and docker-compose installed locally
2. docker-compose up --build
3. The app will be running on http://localhost:8000
```

## Setup AWS Keys
To setup `SECRET_ACCESS_KEY` and `AWS_ACCESS_KEY_ID` follow below steps.


- Sign In to AWS Console:
Go to the AWS Management Console and sign in with your AWS account.

- Open the IAM Dashboard:
In the AWS Management Console, navigate to the "Services" dropdown and select "IAM" under the "Security, Identity, & Compliance" section.

- Create or Access an IAM User:

- If you have an existing IAM user, you can use their credentials. If not, you can create a new IAM user with the necessary permissions.
Click on "Users" in the left-hand navigation pane.
Create a New IAM User (if needed):

  - To create a new IAM user, click the "Add user" button.
Enter a username for the new user.
For "Access type," choose "Programmatic access" to generate access keys.
Click "Next: Permissions."
Set Permissions:

- Attach the necessary policies to the user. Policies define what the user can and cannot do in AWS.
You can attach existing policies or create custom policies based on your requirements.
Review and Create User:

- Review the user's information and permissions.
Click "Create user."
Access and Save the Credentials:

- After creating the user, you'll see a confirmation page.
The AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY will be displayed. Make sure to copy and save these credentials securely.
Secure Your Credentials:

## Setup AWS Cognito Keys
To setup `COGNITO_REGION`, `COGNITO_USER_POOL_ID`, `COGNITO_CLIENT_ID`, `COGNITO_CLIENT_SECRET`
AWS Cognito and obtain the necessary environment variables, follow these steps:
To set up 

- Sign In to AWS Console:
  - Go to the AWS Management Console and sign in with your AWS account.

- Open the AWS Cognito Dashboard:
  - In the AWS Management Console, navigate to the "Services" dropdown and select "Cognito" under the "Security, Identity, & Compliance" section.

- Create a User Pool:

  - In the Cognito dashboard, click on "Manage User Pools."
  - Click the "Create a user pool" button.
  - Configure your user pool with the desired settings. This includes the pool name, policies, and attributes. You can follow the on-screen instructions.
- Create an App Client:

  - After creating the user pool, click on the user pool's name to access its settings.
  - In the left navigation pane, click on "App clients."
  - Click the "Add an app client" button.
  - Configure the app client with a name and set other options as needed.
  - Click "Create app client."
- Note Down `COGNITO_USER_POOL_ID` and `COGNITO_CLIENT_ID`:

  - From the user pool settings page, you can find the "Pool Id" (COGNITO_USER_POOL_ID) and the "App client id" (COGNITO_CLIENT_ID).
  - Make a note of these values as they will be used as environment variables.
- Set Up App Client Secret (Optional):

  - If you want to use a client secret, you can configure it in the app client settings. Note that not all applications require a client secret, and it's typically used for server-side applications.
- Set the COGNITO_REGION:

  - The region is part of the AWS resources' URL. You need to know which AWS region your Cognito User Pool is in. You can find this information in the AWS Management Console while configuring your Cognito User Pool.
  - Set the region as an environment variable (COGNITO_REGION).
  - ```COGNITO_REGION=us-east-1```
- Set the `COGNITO_CLIENT_SECRET` (if used):
  - If you configured a client secret, make sure to set it as an environment variable (COGNITO_CLIENT_SECRET).
