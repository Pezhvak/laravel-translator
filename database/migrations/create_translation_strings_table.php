<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('translation_strings', function (Blueprint $table) {
            $table->id();
            $table->string('locale');
            $table->string('file');
            $table->string('key');
            $table->text('value');
            $table->boolean('needs_translation')
                ->default(true);
            $table->timestamps();
            $table->unique(['locale', 'file', 'key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('translation_strings');
    }
};
