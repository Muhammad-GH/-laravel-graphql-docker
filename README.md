## Evolution

https://www.toptal.com/graphql/laravel-graphql-server-tutorial

- `alias pa='php artisan'`
- `composer create-project laravel/laravel example-app`
- create db and update .env
- In user migration, add `$table->string('api_token', 80)->unique()->nullable()->default(null);`
- `pa make:model Company -m`, model with `parent() { hasMany(Company::class);} ` and `stations() { hasMany(Station::class); }` relations.
- `pa make:model Station -m`, model with `company() { belongsTo(Company::class); }` relation

**Seeding**

- `pa make:seeder CompaniesTableSeeder`
- `pa make:seeder StationsTableSeeder`
- Then add `$this->call(CompaniesTableSeeder::class);` in `DatabaseSeeder`

**Lighthouse**

- `composer require nuwave/lighthouse`
- Load configuration `php artisan vendor:publish` and select provider `Provider: Nuwave\Lighthouse\LighthouseServiceProvider`
- `composer require mll-lab/laravel-graphql-playground`

**Dockerization**

- Follow official documentation to install Docker
- Create Dockerfile
- `docker build . -t laravel-docker-aws`
- `docker run -it -p 8001:80 laravel-docker-aws`
- Run `http://localhost:8001/graphql-playground` (but DB Connection fails)

**ECR Build**

- `buildspec.yml` (Check region name in `ecr get-login` command)
- Create a repository in AWS ECR `asif/laravel-docker-aws`
- Copy and update repo URI in `buildspec.yml`
- Create a build project at AWS CodeBuild. 
    - Source: BitBucket/GitHub,
    - Managed Image: Ubuntu,
    - Runtime: Standard,
    - Image: (depeding on runtime (PHP) version you need)
    - Check Previliged (for Docker) 
    - Others: Keep default,
    - and Save
- IAM Roles. Looks for `codebuild-build-project-laravel-docker-aws-service-role` (that's the CodeBuild project name), attach a new policy `AmazonEC2ContainerRegistryPowerUser` to it.
- Run the build, and the Docker image will be available in our ECR repo to use in ECS

**ECS Cluster, Task Definition and Service**

- Create an ECS Cluster.
- Create a task definition. Port map 80. 
- In the cluster, create a service. Keep desired tasks = 1. 
- Save, and a task will start in a while
- Just make sure the security group of that task has All Traffic allowed.
- Get the IP from the task, and run it. Laravel should be loaded here.

**RDS Database**

- Create an RDS Instance. Keep public access ON (for assignment pruposes)
- Make sure RDS secuirty group also allows public traffic on 3306
- To run the migration on the RDS DB, you can just now set DB details in `.env` locally and run migrations and seeding
- Make sure RDS DB details are set in `.env.fargate` as well. This is cloned to `.env` in Docker Build step.

**React, Typescript and Appolo GraphQL Client**

See: https://github.com/arxoft/reactjs-typescript-apollo


**Lifesavers**

- https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/php-laravel-tutorial.html#php-laravel-tutorial-configure
- Docker installation issue on Linux Mint: https://stackoverflow.com/a/45881841
- Dockerize Laravel: https://www.aws.ps/deploy-a-docker-ized-laravel-application-to-aws-ecs-with-codebuild/
- Docker without sudo: https://askubuntu.com/questions/477551/how-can-i-use-docker-without-sudo
- Using Appolo: https://www.apollographql.com/blog/graphql/examples/4-simple-ways-to-call-a-graphql-api/
- ~~Prepare EC2 instance for ECS Task: https://stackoverflow.com/a/36533601~~

### DevOps

- Make changes to code
- ~~Change IMAGE_TAG in builspec.yml~~
- Commit and Push to master
- Run the build in AWS CodeBuild 
- Release a new task revision with updated IMAGE_TAG
- Update service with new task definition revision number
- Delete the task
- A new task will spawn
