# SMARTIMS - Smart Inventory Management System

<p align="center">
<img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
<img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
<img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
<img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

## 🚀 About SMARTIMS

SMARTIMS is a comprehensive, open-source Inventory Management System built with Laravel and MySQL. Designed to streamline inventory operations for businesses of all sizes, SMARTIMS provides powerful features through an intuitive interface.

### ✨ Key Features

- **Dual Admin Panels**
  - Super Admin Panel - Complete system control and management
  - Company Admin Panel - Company-specific inventory management
  
- **Inventory Management**
  - Real-time stock tracking
  - Product categorization
  - Stock alerts and notifications
  - Multi-warehouse support
  
- **User Management**
  - Role-based access control
  - Multiple user permissions
  - Activity logging

- **Reporting & Analytics**
  - Inventory reports
  - Sales tracking
  - Stock movement history
  - Custom report generation

## 🛠️ Tech Stack

- **Backend:** Laravel (PHP Framework)
- **Database:** MySQL
- **Frontend:** Blade Templates, Bootstrap/Tailwind CSS
- **Authentication:** Laravel Authentication

## 📋 Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM

## ⚙️ Installation

1. **Clone the repository**
```bash
git clone https://github.com/HmzaAkram/SMARTIMS.git
cd SMARTIMS
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
Update your `.env` file with database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartims
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations**
```bash
php artisan migrate --seed
```

6. **Compile assets**
```bash
npm run dev
```

7. **Start the development server**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 🔐 Default Login Credentials

**Super Admin:**
- Email: superadmin@smartims.com
- Password: password

**Company Admin:**
- Email: admin@company.com
- Password: password

> ⚠️ Please change these credentials after first login!

## 📖 Documentation

Detailed documentation is coming soon. For now, explore the codebase to understand the system architecture.

## 🤝 Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Hamza Akram**
- GitHub: [@HmzaAkram](https://github.com/HmzaAkram)
- LinkedIn: [Connect with me](https://linkedin.com/in/your-profile)

## 🙏 Acknowledgments

- Laravel Framework
- All contributors who help improve SMARTIMS
- The open-source community

## 📞 Support

If you have any questions or need help, please open an issue or reach out through GitHub.

---

<p align="center">Made with ❤️ by Hamza Akram</p>
