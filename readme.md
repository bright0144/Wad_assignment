# UECS 2194 Online Card Store Project

## Project Setup Guide

Follow these steps to initialize and run the project:

### 1. Install WAMP Server

Make sure you have [WAMP Server](https://www.wampserver.com/en/) installed on your machine.

### 2. Copy Project Folder

Copy the folder **`Wad_assignment`** into your WAMP `www` directory.  
Example (default path): C:\wamp64\www\Wad_assignment

### 3. Configure Database Connection

Open the following files:

- `config/config.php`
- `config/create-db.php`

Update the database settings:

- **Host name**
- **Password**
- **Port number**

according to your local WAMP setup.

### 4. Initialize Database Schema & Seed Data

Check which port WAMP is running on.  
For example, if it’s running on **8081**:

1. Navigate to: **http://localhost:8081/Wad_assignment/config/init.php**
   This will create the database schema, tables, and insert some random products.

### 5. Open the Home Page

After initializing the database, open the home page by visiting: **http://localhost:8081/Wad_assignment**

You should now see the project running successfully 🎉
