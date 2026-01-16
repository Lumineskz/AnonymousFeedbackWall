# Admin Panel Documentation

## Overview
The admin panel has been successfully created for the Anonymous Feedback Wall application. Admin users have full control over managing rooms, users, and feedback.

## Features

### 1. **Admin Authentication**
- Only users with role `admin` can access the admin panel
- The role is stored in the `users` table (roles: 'user' or 'admin')
- Non-admin users are automatically redirected to the rooms page

### 2. **Admin Navigation**
The admin panel includes:
- **Dashboard**: Overview with statistics
- **Manage Rooms**: View, edit, and delete rooms
- **Manage Users**: View, change user roles, and delete users
- **Manage Feedback**: View and delete feedback

### 3. **Dashboard**
Shows three key statistics:
- Total Rooms
- Total Users
- Total Feedback

### 4. **Manage Rooms**
- View all rooms with details (ID, Title, Creator, Description, Creation Date)
- Edit room title and description
- Delete rooms (cascades delete associated feedback)

### 5. **Manage Users**
- View all users with their roles
- Change user roles directly from a dropdown (admin/user)
- Delete users (cascades delete associated rooms and feedback)
- Cannot delete self
- Cannot demote yourself from admin

### 6. **Manage Feedback**
- View all feedback with room and author information
- View full feedback details
- Delete feedback

## File Structure

### New Files Created:

**Pages:**
- `pages/admin.php` - Main admin dashboard with all sections

**Includes:**
- `includes/admin-auth.php` - Admin authentication check
- `includes/admin-header.php` - Admin header and sidebar navigation
- `includes/admin-footer.php` - Admin page footer

**Actions:**
- `actions/admin_delete_room.php` - Delete room and associated feedback
- `actions/admin_delete_user.php` - Delete user and associated data
- `actions/admin_delete_feedback.php` - Delete feedback
- `actions/admin_update_user.php` - Update user role
- `actions/admin_update_room.php` - Update room details
- `actions/admin_get_user.php` - Get user data for editing

**Styles:**
- `assets/css/admin.css` - Complete admin panel styling

### Modified Files:
- `includes/header.php` - Added Admin Panel link for admin users
- `includes/admin-header.php` - Populated with sidebar and navigation

## Styling

The admin panel uses a modern, professional design with:
- Gradient header (purple theme matching existing design)
- Sidebar navigation with active state indicators
- Responsive table layouts
- Color-coded badges for user roles
- Action buttons with hover effects
- Success and error message notifications
- Mobile responsive design (768px breakpoint)

## Database Schema

The system uses these tables:
- `users` - Contains user_id, username, password, **role** (enum: 'user', 'admin'), created_at
- `rooms` - Contains room_id, user_id, title, description, image, created_at
- `feedback` - Contains feedback_id, room_id, user_id, message, display_name, image, created_at

## Access Control

### Accessing Admin Panel:
1. Login as an admin user
2. You'll see an "Admin Panel" button in the header (orange color)
3. Click to enter the admin dashboard

### Example Admin Account:
- Username: `Admin`
- Password: (use the hashed password already in the database)
- Role: `admin`

## Security Features

- Session-based authentication
- Role checking for all admin operations
- Prevention of self-deletion and self-demotion
- Prepared statements to prevent SQL injection
- HTML escaping to prevent XSS
- Cascading deletes maintain data integrity

## User Experience

- Inline role switching with auto-submit dropdown
- One-click delete with confirmation dialogs
- Success/error messages with auto-dismiss
- Active menu highlighting showing current section
- Responsive design works on mobile devices
- Icons from Font Awesome for better visual clarity

## Future Enhancements

The following can be added:
- Edit user usernames
- Edit room images
- View full feedback details in a modal
- Bulk actions (delete multiple items)
- Advanced filtering and search
- Audit logs for admin actions
- Date range filters for statistics
