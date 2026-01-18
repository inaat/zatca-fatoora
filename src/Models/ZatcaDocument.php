<?php

namespace SaudiEv\Fatoora\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * ZATCA Document Model
 *
 * Stores invoice submissions to ZATCA
 */
class ZatcaDocument extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'zatca_documents';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the table name from config
     *
     * @return string
     */
    public function getTable()
    {
        return config('zatca.database.table_name', 'zatca_documents');
    }
}
