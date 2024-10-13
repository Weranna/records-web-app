# Medical Equipment Management System

This is a web application created as part of an internship project for managing and tracking medical equipment in various locations. The application is built using PHP and MySQL, and allows users to log in, add, view, and manage equipment as well as related events. It also supports file and image uploads.

## Features

- **User Authentication**: Secure login system for multiple user roles.
  - Admin users have full access to all equipment and management features.
  - Regular users can only see equipment in their assigned location.
  
- **Role-based Access Control**:
  - Different functionalities are available depending on the user's role.
  - Admins can manage users, add equipment, create events, and modify dictionaries.
  - Regular users have limited access to the system, restricted by location.

- **Dictionary Management**:
  - Admins can add and manage dictionaries for various categories (e.g., equipment types, locations, manufacturers).

- **Equipment Management**:
  - Users can view equipment based on their location.
  - Equipment records include relevant details such as model, serial number, and manufacturer.
  
- **Event Tracking**:
  - Users can report equipment failure.
  - Event history is available for each piece of equipment.
  - Admin can report more type of events.

- **File & Image Uploads**:
  - Attach relevant files (e.g., manuals, maintenance reports) and images to equipment or events.

## Technologies Used

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **File Storage**: Local server storage for uploaded files and images

## Purpose of the Project

This project was developed as part of an internship program to demonstrate proficiency in building web applications using PHP and MySQL. It is intended as a practical demonstration of skills related to database management, user authentication, file handling, and role-based access control.

## Usage

### Admin Functions:
- **Manage Users**: Add, edit, or delete users with different roles.
- **Manage Equipment**: Add new equipment, update details, or remove equipment from the system.
- **Manage Events**: Log events related to equipment such as maintenance, breakdowns, etc.
- **Manage Dictionaries**: Create and manage dictionaries (e.g., equipment types, manufacturers).

### Regular User Functions:
- **View Equipment**: View equipment in their assigned location.
- **Log Events**: Add events related to equipment in their location.
- **Upload Files/Images**: Attach files and images to equipment or events.

### File Uploads:
- Ensure the `uploads/` directory is writable. Users can upload files like equipment manuals or event photos, which are stored locally.
