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
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('tenant_id')->nullable()->index();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('cpf')->nullable();
            $table->string('default_zipcode')->nullable();
            $table->string('default_address')->nullable();
            $table->string('default_number')->nullable();
            $table->string('default_complement')->nullable();
            $table->string('default_neighborhood')->nullable();
            $table->string('default_city')->nullable();
            $table->string('default_state', 10)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('marketing_opt_in')->default(false);
            $table->timestamp('lgpd_consent_at')->nullable();
            $table->timestamp('anonymized_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'email']);
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->foreignId('customer_id')
                ->nullable()
                ->after('tenant_id')
                ->constrained('customers')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('customer_id');
        });

        Schema::dropIfExists('customers');
    }
};
