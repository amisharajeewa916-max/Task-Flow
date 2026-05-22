<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // tasks table — most frequently filtered/sorted columns
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('assigned_to',  'idx_tasks_assigned_to');
            $table->index('created_by',   'idx_tasks_created_by');
            $table->index('status',       'idx_tasks_status');
            $table->index('priority',     'idx_tasks_priority');
            $table->index('deadline',     'idx_tasks_deadline');
            $table->index('project_id',   'idx_tasks_project_id');
            $table->index(['status', 'deadline'], 'idx_tasks_status_deadline');
        });

        // notifications table — queried on every NotificationBell render
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'idx_notifications_user_status');
        });

        // comments table — queried whenever a task is shown
        Schema::table('comments', function (Blueprint $table) {
            $table->index('task_id', 'idx_comments_task_id');
        });

        // attachments table
        Schema::table('attachments', function (Blueprint $table) {
            $table->index('task_id', 'idx_attachments_task_id');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('idx_tasks_assigned_to');
            $table->dropIndex('idx_tasks_created_by');
            $table->dropIndex('idx_tasks_status');
            $table->dropIndex('idx_tasks_priority');
            $table->dropIndex('idx_tasks_deadline');
            $table->dropIndex('idx_tasks_project_id');
            $table->dropIndex('idx_tasks_status_deadline');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('idx_notifications_user_status');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('idx_comments_task_id');
        });

        Schema::table('attachments', function (Blueprint $table) {
            $table->dropIndex('idx_attachments_task_id');
        });
    }
};
