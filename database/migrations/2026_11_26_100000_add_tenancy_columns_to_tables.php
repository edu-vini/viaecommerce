<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('tenant_id')->nullable()->after('id')->index();
            $table->boolean('is_super_admin')->default(false)->after('password');

            $table->dropUnique(['email']);
            $table->unique(['tenant_id', 'email']);

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('store_settings', function (Blueprint $table): void {
            $table->string('tenant_id')->nullable()->after('id')->unique();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->string('tenant_id')->nullable()->after('id')->index();

            $table->dropUnique(['slug']);
            $table->unique(['tenant_id', 'slug']);

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->string('tenant_id')->nullable()->after('id')->index();

            $table->dropUnique(['slug']);
            $table->dropUnique(['sku']);
            $table->unique(['tenant_id', 'slug']);
            $table->unique(['tenant_id', 'sku']);

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('banners', function (Blueprint $table): void {
            $table->string('tenant_id')->nullable()->after('id')->index();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->string('tenant_id')->nullable()->after('id')->index();

            $table->dropUnique(['code']);
            $table->unique(['tenant_id', 'code']);

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropForeign(['tenant_id']);
            $table->dropUnique(['tenant_id', 'code']);
            $table->unique(['code']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('banners', function (Blueprint $table): void {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropForeign(['tenant_id']);
            $table->dropUnique(['tenant_id', 'slug']);
            $table->dropUnique(['tenant_id', 'sku']);
            $table->unique(['slug']);
            $table->unique(['sku']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->dropForeign(['tenant_id']);
            $table->dropUnique(['tenant_id', 'slug']);
            $table->unique(['slug']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('store_settings', function (Blueprint $table): void {
            $table->dropForeign(['tenant_id']);
            $table->dropUnique(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['tenant_id']);
            $table->dropUnique(['tenant_id', 'email']);
            $table->unique(['email']);
            $table->dropColumn(['tenant_id', 'is_super_admin']);
        });
    }
};
