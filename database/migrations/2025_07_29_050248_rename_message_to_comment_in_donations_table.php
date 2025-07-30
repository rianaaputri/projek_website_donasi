<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMessageToCommentInDonationsTable extends Migration
{
    public function up()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->renameColumn('message', 'comment');
        });
    }

    public function down()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->renameColumn('comment', 'message');
        });
    }
}

