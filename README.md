# 📜 Delivery Tracker - Development Setup

This guide provides step-by-step instructions to set up the project locally using **Docker + Laravel Sail** on **WSL (Ubuntu 22.04) + Windows**.

---

## **⚡ Prerequisites**
Before setting up the project, ensure you have the following installed:

- **[Docker Desktop](https://www.docker.com/products/docker-desktop/)** (Ensure WSL integration is enabled)
- **[Windows Subsystem for Linux (WSL)](https://learn.microsoft.com/en-us/windows/wsl/install)**
- **Ubuntu 22.04 (WSL 2)**
- **Git**

---

## **🚀 Installation Steps**

### **1️⃣ Clone the Repository**
```sh
git clone https://github.com/your-repo/delivery-tracker.git
cd delivery-tracker
```

---

### **2️⃣ Create the `.env` File**
```sh
cp .env.example .env
```
Modify `.env` if needed (e.g., database credentials, `APP_URL`).

---

### **3️⃣ Start the Development Environment**
Run Laravel Sail to start the project in Docker:
```sh
sail up -d
```
> ⚠️ **Note:** If you haven't set up the `sail` alias, use `./vendor/bin/sail` instead of `sail`.

---

### **4️⃣ Restart Sail to Ensure DB Connection**  
Sometimes, migrations fail because the database isn't fully ready. If you run into issues, try this:
```sh
sail down
sail up -d
```

---

### **5️⃣ Run Database Migrations**
Once the containers are up, migrate the database:
```sh
sail artisan migrate
```

---

### **6️⃣ Install JavaScript Dependencies**
If you're using **Vue.js + Tailwind CSS**, install frontend dependencies and start the Vite dev server:
```sh
sail npm install
sail npm run dev
```

---

### **7️⃣ Seed the Database with Fake Data**
To populate the database with fake test data, run:
```sh
sail artisan migrate:fresh --seed
```
This will **reset the database** and run all seeders to generate sample data.

Also run to sync the Packages sync data
```sh
sail artisan meilisearch:sync
```

Also run to listen for work
```sh
sail artisan queue:listen
```
---

## **🎯 Common Commands**

| **Command** | **Description** |
|------------|----------------|
| `sail up -d` | Start the application in detached mode. |
| `sail down` | Stop all running containers. |
| `sail artisan migrate` | Run database migrations. |
| `sail artisan tinker` | Open Laravel Tinker for testing. |
| `sail npm run dev` | Start the Vite dev server. |

---

## **✅ Everything is Set!**
Now, visit the app in your browser:
```
http://localhost
```

🎉 **You're ready to start developing!** 🚀

---

## 📦 Updating Package Scans via API

You can update a package's scan history using the API endpoint (NO authentication implemented):

### **📤 Endpoint**
```http
POST http://localhost/api/packages/{package_id}/update-scans
````

### 📩 Request Body (JSON)
```json
{
    "terminal_id": 3,
    "scanned_at": "2025-02-09T15:30:00Z"
}
```

## **🛠️ Docker Services Included**

The following services are included in `docker-compose.yml`:

- **PostgreSQL** - Database service (`pgsql`)
- **Mailpit** - Email testing service accessible at [`http://localhost:8025`](http://localhost:8025).
- **Soketi** - WebSockets (Pusher alternative) running on port `6001`.
- **Meilisearch** - Full-text search engine accessible at [`http://localhost:7700`](http://localhost:7700).