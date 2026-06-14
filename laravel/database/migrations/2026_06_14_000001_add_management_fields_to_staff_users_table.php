<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_users', function (Blueprint $table) {
            $table->string('account_status')->default('active')->after('role');
            $table->foreignId('managed_by_staff_user_id')
                ->nullable()
                ->after('account_status')
                ->constrained('staff_users')
                ->nullOnDelete();
            $table->boolean('must_change_password')->default(false)->after('is_active');
            $table->timestamp('status_changed_at')->nullable()->after('last_login_at');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('staff_users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('managed_by_staff_user_id');
            $table->dropColumn([
                'account_status',
                'must_change_password',
                'status_changed_at',
                'deleted_at',
            ]);
        });
    }
};
