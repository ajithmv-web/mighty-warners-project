<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add soft deletes and indexes to categories
        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('name');
        });

        // Add soft deletes and indexes to colors
        Schema::table('colors', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('name');
        });

        // Add soft deletes and indexes to sizes
        Schema::table('sizes', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('name');
        });

        // Add soft deletes and indexes to products
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('name');
            $table->index('category_id');
            $table->index('color_id');
            $table->index('size_id');
            $table->index('price');
            $table->index(['category_id', 'color_id', 'size_id']);
        });

        // Add soft deletes and indexes to coupons
        Schema::table('coupons', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('code');
            $table->index('is_active');
            $table->index('expires_at');
        });

        // Add soft deletes to users
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['name']);
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['name']);
        });

        Schema::table('sizes', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['name']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['name']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['color_id']);
            $table->dropIndex(['size_id']);
            $table->dropIndex(['price']);
            $table->dropIndex(['category_id', 'color_id', 'size_id']);
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['code']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['expires_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['email']);
        });
    }
};
