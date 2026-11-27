<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('approval_status', 20)->default('approved')->index();
        });
        Schema::create('resident_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('birth_date');
            $table->string('sex', 30);
            $table->string('civil_status', 30);
            $table->string('barangay', 100);
            $table->string('address', 255);
            $table->string('contact_no', 30);
            $table->boolean('is_indigent')->default(false);
            $table->timestamps();
        });
        Schema::create('barangay_officials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('position', 100);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('subject', 150);
            $table->text('description');
            $table->date('incident_date');
            $table->string('status', 20)->default('submitted')->index();
            $table->timestamps();
        });
        Schema::create('service_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('message', 255);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('service_notifications');
        Schema::dropIfExists('incident_reports');
        Schema::dropIfExists('barangay_officials');
        Schema::dropIfExists('resident_profiles');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('approval_status'));
    }
};
