<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class VentasPayu extends Model
{
    protected $table = 'venta_payu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'reference_code',
        'transaction_state',
        'pol_response_code',
         'reference_pol', 
         'tx_value', 
         'tx_tax', 
         'buyer_email', 
         'processing_date', 
         'currency', 
         'cus',
         'pse_bank', 
         'description', 
         'lap_response_code', 
         'lap_payment_method', 
         'lap_payment_type', 
         'lap_transaction_state', 
         'message', 
         'extra1', 
         'extra2', 
         'extra3', 
         'autorization_code', 
         'merchant_address', 
         'merchant_name', 
         'pse_cycle', 
         'pse_reference1', 
         'pse_reference2', 
         'pse_reference3', 
         'telephone', 
         'transaction_id',
         'trazability_code', 
         'tx_administrative_fee', 
         'tx_tax_administrative_fee', 
         'tx_tax_administrative_fee_return_base',
         'action_code_description', 
         'cc_holder', 
         'cc_number', 
         'processing_date_time', 
         'request_number',
         'id_cuenta_principal'
    ];
}
