<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('zatca.database.table_name', 'zatca_documents');

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->string('icv')->comment('Invoice Counter Value');
            $table->uuid('uuid')->unique()->comment('Invoice UUID');
            $table->string('hash')->nullable()->comment('Invoice Hash (from ZATCA)');
            $table->longText('xml')->nullable()->comment('Signed Invoice XML');
            $table->boolean('sent_to_zatca')->default(false)->comment('Was sent to ZATCA');
            $table->string('sent_to_zatca_status')->nullable()->comment('ZATCA submission status');
            $table->dateTime('signing_time')->nullable()->comment('Invoice signing timestamp');
            $table->longText('response')->nullable()->comment('ZATCA API response');
            $table->string('response_source')->nullable()->comment('Response source identifier');
            $table->string('type')->nullable()->comment('Invoice type: simplified/standard');
            $table->string('portal_mode')->nullable()->comment('ZATCA environment used');
            $table->longText('qr_value')->nullable()->comment('QR code value');

            // Optional foreign keys - can be customized per implementation
            $table->unsignedBigInteger('invoice_id')->nullable()->comment('Your invoice ID');
            $table->morphs('invoiceable'); // Polymorphic relation support

            $table->timestamps();

            // Indexes
            $table->index('icv');
            $table->index('sent_to_zatca');
            $table->index('invoice_id');
            $table->index(['invoiceable_type', 'invoiceable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableName = config('zatca.database.table_name', 'zatca_documents');
        Schema::dropIfExists($tableName);
    }
};
