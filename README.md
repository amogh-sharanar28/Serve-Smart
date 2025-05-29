# Serve-Smart 🍽️

**A DevOps-based Automated Deployment System for a Food Ordering Web App**

This project demonstrates how to fully automate the CI/CD pipeline of a PHP + MySQL-based food ordering system using GitHub Actions, Docker, Docker Hub, and AWS EC2.

## 🚀 Project Overview

Serve-Smart is a web-based food ordering system designed to simplify the customer experience and streamline restaurant order management. The key highlight of this project is the implementation of a fully automated deployment pipeline using DevOps practices.

## 🛠️ Technologies Used

### 💻 Web Application
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Server**: Apache (via Docker)

### ⚙️ DevOps & Deployment
- **CI/CD**: GitHub Actions
- **Containerization**: Docker, Docker Compose
- **Cloud Hosting**: AWS EC2 (Ubuntu)
- **Docker Registry**: Docker Hub

---

## 🔁 Workflow Summary

1. **Push to GitHub** → triggers GitHub Actions
2. **GitHub Actions**:
   - Installs dependencies on EC2 via SSH
   - Pulls latest repo code
   - Builds Docker images
   - Pushes to Docker Hub
   - Stops and removes old containers
   - Deploys updated containers on EC2 via `docker-compose`
3. **App accessible via EC2 Public IP**

---

## 📁 Project Structure

```
Serve-Smart/
│
├── docker-compose.yml            # Defines PHP + MySQL services
├── Dockerfile                    # PHP-Apache web server setup
├── fos_db.sql                    # MySQL database schema
├── .github/workflows/deploy.yml # GitHub Actions workflow for CI/CD
├── src/
│   ├── admin/                    # Admin interface
│   ├── customer/                 # Customer ordering interface
│   └── includes/                # Common PHP includes
└── README.md                    # Project documentation
```

---

## ⚙️ Deployment Steps (Automated)

1. Push code to `main` branch.
2. GitHub Actions workflow (`.github/workflows/deploy.yml`) gets triggered.
3. Workflow performs:
   - SSH into AWS EC2
   - Clone the repo
   - Build Docker image
   - Push to Docker Hub
   - Pull latest image on EC2
   - Run containers using `docker-compose`

🟢 **Deployment is live on EC2 Public IP.**

---

## 🔐 Database Info

- **File**: `fos_db.sql`
- **Engine**: MySQL
- **Data**: Contains schema and initial seed data for users, menus, orders, etc.

---

## 🧪 Features

- Customer registration and login
- Browse menu and place orders
- Admin panel for managing:
  - Food items
  - Orders
  - Customers
- Secure authentication
- Responsive UI for desktop and mobile

---

## 🧰 Prerequisites (for manual setup)

- Docker & Docker Compose installed
- AWS EC2 Ubuntu instance running
- Docker Hub account
- SSH access configured

---

## 🔄 Manual Deployment (Optional)

```bash
# On EC2:
git clone https://github.com/amogh-sharanar28/Serve-Smart.git
cd Serve-Smart
docker-compose up -d --build
```

---

## 👨‍💻 Author

**Amogh Sharanar**  
6th Semester CSE Student  
KLE MSSCET, Belgaum  
📧 amoghsharanar28@gmail.com  
🌐 [GitHub](https://github.com/amogh-sharanar28)  
🔗 [LinkedIn](https://linkedin.com/in/amogh-sharanar28)

---

## 📜 License

This project is licensed under the MIT License.

---

## 📷 Screenshots

> _You can add screenshots of the homepage, admin panel, customer order page, etc. here._

---

## 📞 Contact

Feel free to reach out for collaboration, improvements, or queries!

📧 **Email**: amoghsharanar28@gmail.com  
🐙 **GitHub**: [@amogh-sharanar28](https://github.com/amogh-sharanar28)  
💼 **LinkedIn**: [linkedin.com/in/amogh-sharanar28](https://linkedin.com/in/amogh-sharanar28)