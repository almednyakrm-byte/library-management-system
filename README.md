# نظام إدارة مكتبة (إضافة، تعديل، حذف، recherche كتب)
==========================

## Overview & Project Purpose
---------------------------

نظام إدارة مكتبة هو تطبيق مفتوح المصدر يهدف إلى تسهيل إدارة المكتبات من خلال إضافة، تعديل، حذف، وبحث عن الكتب. يتيح هذا النظام للمستخدمين إدارة قاعدة بيانات الكتب بسهولة ووضوح.

## Project Structure Mapping
---------------------------

### Directory Structure


.
├── docker-compose.yml
├── README.md
├── app
│   ├── __init__.py
│   ├── models
│   │   ├── __init__.py
│   │   ├── book.py
│   │   └── user.py
│   ├── routes
│   │   ├── __init__.py
│   │   ├── book_routes.py
│   │   └── user_routes.py
│   ├── templates
│   │   ├── base.html
│   │   ├── book.html
│   │   └── user.html
│   └── utils
│       ├── __init__.py
│       └── database.py
├── requirements.txt
└── venv


### Module Structure

*   `app`: تطبيق الرئيسي
*   `app.models`: طبقات نموذج البيانات
*   `app.routes`: طبقات الطرق
*   `app.templates`: شفرات HTML للمواقع
*   `app.utils`: أدوات متفرقة

### Tables

*   `books`: قاعدة بيانات الكتب
*   `users`: قاعدة بيانات المستخدمين

### Roles

*   `admin`: دور الإدارة
*   `user`: دور المستخدم

## Step-by-Step Instructions
---------------------------

### 1. Clone Repository

bash
git clone https://github.com/your-username/نظام-إدارة-مكتبة.git


### 2. Install Dependencies

bash
pip install -r requirements.txt


### 3. Build Docker Image

bash
docker-compose build


### 4. Run Docker Container

bash
docker-compose up


### 5. Access Application

bash
http://localhost:5000


## Contact Developer Details
---------------------------

*   **Developer Name:** [Your Name]
*   **Email:** [your-email@example.com](mailto:your-email@example.com)
*   **GitHub:** [your-username](https://github.com/your-username)

## License
--------

نظام إدارة مكتبة هو تطبيق مفتوح المصدر، ويمكن استخدامها وتمديدها دون أي قيود.

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
