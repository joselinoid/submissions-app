<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'submissions';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'applicant_name',
        'company_name',
        'submission_date',
        'total',
        'status_id',
        'workflow_id',
        'reapply_from_id',
    ];

    protected $casts = [
        'submission_date' => 'date',
    ];

    /**
     * Get the user that owns the submission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function reapplyFrom()
    {
        return $this->belongsTo(self::class, 'reapply_from_id');
    }

    public function reapplyChildren()
    {
        return $this->hasMany(self::class, 'reapply_from_id');
    }

    public function submissionDiscussions(): HasMany
    {
        return $this->hasMany(SubmissionDiscussion::class)->latest();
    }

    public function submissionApprovals(): HasMany
    {
        return $this->hasMany(SubmissionApproval::class);
    }
}
