<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['invoice_code', 'user_id', 'total_amount', 'amount_paid', 'change'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('invoice', 'like', '%' . $search . '%')
                ->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            return $query->where('status', $status);
        });

        $query->when($filters['payment_method'] ?? false, function ($query, $paymentMethod) {
            return $query->where('payment_method', $paymentMethod);
        });

        $query->when($filters['date_from'] ?? false, function ($query, $dateFrom) {
            return $query->whereDate('created_at', '>=', $dateFrom);
        });

        $query->when($filters['date_to'] ?? false, function ($query, $dateTo) {
            return $query->whereDate('created_at', '<=', $dateTo);
        });
    }
}
