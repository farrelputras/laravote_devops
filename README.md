
# 🗳️ Laravote - DevOps Voting Application

![Laravote Dashboard](images/laravote-dashboard.png)

**Laravote** is a web-based voting platform enhanced with DevOps best practices and full CI/CD pipeline implementation. This project integrates modern software development workflows including automated testing, containerized deployment, monitoring, and role-based feature control.

Want to dive deeper into our technical documentation?  
👉 [Read Full Docs Here (Bahasa Indonesia)](https://intip.in/DOCUMENTATIONLARAVOTE)

---

## 🌟 Features

### Voting System Capabilities
- 🔐 **Token-Based Voting** - Ensure each voter gets one secure, validated vote
- 🧭 **Role-based Access** - Separate flows for Admin and Voters
- 📊 **Real-Time Results** - Vote result visualization with charts
- 🎨 **Improved UI/UX** - Fully redesigned front-end interface using Blade + Bootstrap
- 🛠️ **Admin Panel** - Manage candidates, view tokens, and monitor progress

---

## 🏗️ Technology Stack

### Backend
- **Laravel 5.x** (PHP Framework)
- **PHP 7.x**
- **MySQL** - Relational database
- **Composer** - PHP dependency manager

### Frontend
- **Blade Template Engine**
- **Bootstrap** + **Custom CSS**

### Infrastructure
- **Azure Virtual Machine (VM)**
- **Docker & Docker Compose**

### Development & Testing
- **Git + GitHub**
- **GitHub Actions** (CI/CD)
- **Larastan** - Static code analyzer
- **PHPUnit** - Unit & feature testing
- **SonarQube / SonarCloud** - Code quality & test coverage

### Monitoring
- **Prometheus** - Metrics collection
- **Grafana** - Real-time dashboard
- **UptimeRobot** - Live status checker
- **Sentry** - Error tracking

---

## 🚀 Getting Started

### Prerequisites
- PHP 7.x
- Composer
- Docker & Docker Compose
- MySQL
- Node.js (for frontend assets)

### Setup Guide

1. **Clone the Repository**
```bash
git clone https://github.com/farrelputras/laravote_devops.git
cd laravote_devops
```

2. **Install Dependencies**
```bash
composer install
npm install && npm run dev
```

3. **Configure Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Run Migration & Seed**
```bash
php artisan migrate --seed
```

5. **Start Local Development**
```bash
php artisan serve
```

6. **Or Use Docker**
```bash
docker-compose up --build -d
```

---

## 📡 API Documentation

| Method | Endpoint        | Description                   |
|--------|------------------|-------------------------------|
| GET    | `/`              | Landing Page                  |
| POST   | `/login`         | Login User                    |
| GET    | `/dashboard`     | Admin/Voter Dashboard         |
| POST   | `/vote`          | Submit Vote Token             |
| GET    | `/result`        | Visualize Voting Results      |

More available in the full [API Docs](https://intip.in/DOCUMENTATIONLARAVOTE)

---

## 🔁 DevOps & CI/CD Pipeline

### 🔧 Continuous Integration (CI)
GitHub Actions runs on every `push` and `pull request`:
- ✅ Static Code Analysis (Larastan)
- 🧪 Unit & Feature Testing (PHPUnit)
- 📈 Code Coverage + Smell Report (SonarCloud)

### 🛠 Continuous Deployment (CD)
Deployment pipeline includes:
- 🐳 Build Docker image
- 📦 Push to Azure VM (via SSH)
- ⚙️ Laravel setup: migrate DB, set permissions
- 🔁 Zero-downtime deploy with container orchestration

### 🖥 Monitoring System
- 📊 Azure Monitoring & Grafana: live metrics dashboard
- 🚨 UptimeRobot for public availability checks
- 🐛 Sentry for error alerting & bug tracking

---

## 🌐 Live Demo

Visit: [http://20.106.186.136:8080](http://20.106.186.136:8080)  
🔐 Login Admin:  
- Email: `rifki@admin.com`  
- Password: `admin`

---

## 👥 Project Contributors

Final Project Group 9 – PSO C  
- Ivena Sabita W. (5026221014)  
- Fernandio Farrel P. S. (5026221102)  
- Faiz Musyaffa R. (5026221153)  
- M. Geresidi Rachmadi (5026221163)

---

## 📎 Repositories & Resources

- 🔗 [Laravote DevOps Repo](https://github.com/farrelputras/laravote_devops)
- 🔗 [Original Laravote Source](https://github.com/RifkiCS29/laravote)
- 📄 [Documentation](https://intip.in/DOCUMENTATIONLARAVOTE)
- 🎥 [Project Presentation](https://tekan.id/Progress2-Group9-C)

---

# 📚 Original Setup & Detailed Installation Guide


### Built With
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![GitHub Actions](https://img.shields.io/badge/GitHub%20Actions-2088FF?style=for-the-badge&logo=github-actions&logoColor=white)
![SonarCloud](https://img.shields.io/badge/SonarCloud-F3702A?style=for-the-badge&logo=sonarcloud&logoColor=white)
![Azure](https://img.shields.io/badge/Azure%20VM-0078D4?style=for-the-badge&logo=microsoftazure&logoColor=white)
![Grafana](https://img.shields.io/badge/Grafana-F46800?style=for-the-badge&logo=grafana&logoColor=white)
---
# How to Install This Project

## Prerequisites
- A terminal (Command Prompt, PowerShell, or Git Bash)
- A web server such as [XAMPP](https://www.apachefriends.org/) with **PHP ≥ 7.1.3**
- [Composer](https://getcomposer.org/) installed (`composer -V` to check)
- Internet connection (required to download dependencies)

## Local Setup
1. **Download the Source Code**

   Download this repository as a `.zip` file or clone it.

2. **Extract the Project**

   Extract the zip file to the `htdocs` directory of XAMPP, e.g.:

   ```
   C:\xampp\htdocs\laravote
   ```

3. **Navigate to the Project Directory**

   Open your terminal and run:

   ```bash
   cd path/to/laravote
   ```

4. **Install Dependencies**

   Run the following command to install PHP dependencies:

   ```bash
   composer install
   ```

5. **Verify Laravel Installation**

   Run this command to check if Laravel Artisan is working:

   ```bash
   php artisan
   ```

6. **Create a Database**

   Using phpMyAdmin or MySQL CLI, create a new database named:

   ```
   laravote
   ```

7. **Set Up the Environment File**

   - Copy `.env.example` to `.env`
   - Run the command to generate the app key:

     ```bash
     php artisan key:generate
     ```

8. **Configure the Database in `.env`**

   Open the `.env` file and update the database configuration:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravote
   DB_USERNAME=root
   DB_PASSWORD=
   ```

9. **Add Google OAuth Credentials**

   Add these lines to your `.env` file:

   ```env
   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   GOOGLE_REDIRECT_CALLBACK=http://localhost:8000/google/callback
   ```

   > 💡 Follow this [tutorial](https://daengweb.id/google-authentication-with-laravel-58) to generate your Google credentials.

10. **Run Migrations**

    To create tables in the database:

    ```bash
    php artisan migrate
    ```

11. **Seed the Database**

    Run this command to insert a default admin user:

    ```bash
    php artisan db:seed
    ```

    - Email: `rifki@admin.com`
    - Password: `admin`

12. **Start the Laravel Development Server**

    Finally, run the application locally:

    ```bash
    php artisan serve
    ```

    Visit: [http://localhost:8000](http://localhost:8000)

## Cloud Setup

## Github Actions CI/CD Pipeline
This CI/CD pipeline automates testing, code quality scanning, and deployment of the Laravel application to a remote Virtual Machine (VM).

1. **Workflow Files**:
   - `.github/workflows/test.yml`: Executes testing, code analysis, and SonarCloud scan.
   - `.github/workflows/deploy.yml`: Deploys the application to the VM on successful test completion.

2. **Pipeline Stages:**
   - ### 🧪 Test & Analyze (`test.yml`)
     - Runs on every push or pull request to the `master` branch.
     - Steps:
       - Sets up MySQL service.
       - Installs PHP 7.4 and dependencies.
       - Configures `.env` file and generates `APP_KEY`.
       - Waits for MySQL, runs migrations.
       - Executes unit tests using `php artisan test` and `phpunit`.
       - Sets up Java and downloads SonarScanner CLI.
       - Performs manual SonarCloud analysis with `sonar-scanner`.

   - ### 🚀 Deployment (`deploy.yml`)
     - Triggered on:
       - Success of `test.yml` workflow 
     - Steps:
       - SSH into VM and pull the latest code from GitHub.
       - Rebuilds and restarts Docker containers.
       - Initializes `.env` file and config if needed (fresh deploy).
       - Runs database migrations and optional seeder.
       - Sets folder permissions.
       - Reloads or restarts Nginx service.

3. **Secrets Configuration:**
   Add these secrets in your GitHub repository settings:

   - For Test & Analysis:
     - `SONAR_TOKEN` — Token for SonarCloud authentication.

   - For Deployment via SSH:
     - `VM_HOST` — IP address of the VM.
     - `VM_USER` — VM username.
     - `VM_PASSWORD` — VM password.

> 💡 Notes:
> - Ensure your VM is preconfigured with Docker, Docker Compose, and has access to the Laravel project path (`~/laravote_devops`).
> - The pipeline supports both incremental deployments and fresh setup detection.

---
### Screenshots
![01 Halaman Login](https://github.com/RifkiCS29/laravote/blob/master/public/img/login.png)
![01 Halaman Home Summary](https://github.com/RifkiCS29/laravote/blob/master/public/img/home.png)
![01 Halaman Manage Users](https://github.com/RifkiCS29/laravote/blob/master/public/img/manageUser.png)
![01 Halaman Manage Candidates](https://github.com/RifkiCS29/laravote/blob/master/public/img/manageCandidates.png)
![01 Halaman Choice](https://github.com/RifkiCS29/laravote/blob/master/public/img/choice.png)
