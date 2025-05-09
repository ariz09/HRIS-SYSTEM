<?php

namespace App\Enums;

enum NotificationType: string
{
    case USER_REGISTRATION = 'user_registration';
    case USER_UPDATE = 'user_update';
    case TASK_ASSIGNED = 'task_assigned';
    case TASK_UPDATED = 'task_updated';
    case TASK_COMPLETED = 'task_completed';
    case PROJECT_CREATED = 'project_created';
    case PROJECT_UPDATED = 'project_updated';
    case PROJECT_APPROVED = 'project_approved';
    case LEAVE_REQUEST = 'leave_request';
    case ATTENDANCE = 'attendance';
    case FILE_UPLOADED = 'file_uploaded';
    case FILE_APPROVED = 'file_approved';
    case MESSAGE_RECEIVED = 'message_received';
    case MESSAGE_SENT = 'message_sent';
    case APPROVAL_REQUESTED = 'approval_requested';
    case APPROVAL_ACCEPTED = 'approval_accepted';
    case APPROVAL_REJECTED = 'approval_rejected';
    case BILLING_INVOICE = 'billing_invoice';
    case BILLING_REMINDER = 'billing_reminder';
    case SYSTEM_ALERT = 'system_alert';
    case EVENT_NOTIFICATION = 'event_notification';
    case REMINDER = 'reminder';
    case SYSTEM_UPDATE = 'system_update';
    case URGENT_ALERT = 'urgent_alert';
    case USER_DELETED = 'user_deleted';
    case PASSWORD_RESET = 'password_reset';
    case USER_ROLE_CHANGE = 'user_role_change';
    case ACCOUNT_SUSPENDED = 'account_suspended';
}
