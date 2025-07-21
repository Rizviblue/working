# Courier Management System

A comprehensive web-based courier management system with role-based authentication and modern UI design.

## Features

### Authentication System
- **Role-based login**: Admin, Agent, and User access levels
- **Demo credentials**: One-click login for testing
- **Secure password handling**: bcrypt encryption
- **Session management**: Secure user sessions

### Admin Panel
- **Complete dashboard**: Statistics cards, recent couriers, quick actions
- **Courier management**: Add, edit, delete, and track couriers
- **Agent management**: View and manage delivery agents
- **Customer management**: User account oversight
- **Reports**: Comprehensive reporting system
- **Settings**: System configuration

### Agent Panel
- **Area-specific access**: Limited to agent's assigned city
- **Courier operations**: Add and manage local deliveries
- **Branch reports**: Download area-specific reports
- **Status updates**: Real-time shipment tracking

### User Panel
- **Package tracking**: Real-time courier status
- **Tracking history**: Previous shipment records
- **Print functionality**: Shipment details printing

### Technical Features
- **Responsive design**: Bootstrap 5 framework
- **Modern UI**: Clean, professional interface
- **Security**: SQL injection prevention, XSS protection
- **Database**: MySQL with proper relationships
- **File organization**: Clean, modular structure

## Installation

1. **Database Setup**:
   ```sql
   mysql -u root -p < db/courier_system.sql
   ```

2. **Configuration**:
   - Update database credentials in `includes/config.php`
   - Ensure web server has proper permissions

3. **Access**:
   - Navigate to your web server URL
   - Use demo credentials for testing

## Demo Credentials

| Role  | Email               | Password |
|-------|---------------------|----------|
| Admin | admin@courier.com   | password |
| Agent | agent@courier.com   | password |
| User  | user@courier.com    | password |

## File Structure

```
/
├── admin/              # Admin panel pages
├── agent/              # Agent panel pages  
├── user/               # User panel pages
├── assets/
│   ├── css/           # Stylesheets
│   └── js/            # JavaScript files
├── db/                # Database files
├── includes/          # PHP includes
├── login.php          # Login page
├── register.php       # Registration page
└── logout.php         # Logout handler
```

## Database Schema

- **admins**: System administrators
- **agents**: Delivery agents with city assignments
- **users**: Customer accounts
- **couriers**: Shipment records with tracking
- **sms_logs**: SMS notification logs

## Security Features

- Password hashing with PHP's `password_hash()`
- SQL injection prevention with prepared statements
- XSS protection with input sanitization
- Session-based authentication
- Role-based access control

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## License

This project is open source and available under the MIT License.