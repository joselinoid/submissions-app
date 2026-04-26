<?php

namespace App\Http\Controllers;

use App\Http\Requests\Submission\CreateSubmissionRequest;
use App\Http\Requests\Submission\UpdateSubmissionRequest;
use App\Models\Category;
use App\Models\Status;
use App\Models\Submission;
use App\Models\SubmissionApproval;
use App\Models\TransactionDetail;
use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:submissions.view')->only(['index', 'show']);
        $this->middleware('permission:submissions.create')->only(['create', 'store']);
        $this->middleware('permission:submissions.update')->only(['edit', 'update']);
        $this->middleware('permission:submissions.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        abort_if($user->role->key === 'ADMIN', 403);

        $tab = request('tab', 'approval');
        $maxStepOrder = Workflow::max('step_order');

        $submissions = Submission::with([
            'status:id,key,label',
            'workflow:id,key,label,step_order,role_id'
        ])

            ->when($user->role->key === 'PEMOHON', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })

            ->when(in_array($user->role->key, [
                'PEJABAT1',
                'PEJABAT2',
                'PEJABAT3',
                'PEJABAT4'
            ]), function ($q) use ($user, $maxStepOrder, $tab) {
                if ($tab === 'approval') {
                    $q->whereHas('workflow', function ($w) use ($user) {
                        $w->where('role_id', $user->role_id);
                    });

                    $q->whereHas('status', function ($s) {
                        $s->whereIn('key', ['PENDING', 'REVISION']);
                    });
                }
                if ($tab === 'history') {
                    $q->whereHas('workflow', function ($w) use ($maxStepOrder) {
                        $w->where('step_order', $maxStepOrder);
                    });
                }
            })

            ->latest()
            ->paginate(15);

        $workflows = Workflow::orderBy('step_order')->get();
        $statuses = Status::select('id', 'key', 'label')->get();

        return view('submissions.index', compact(
            'submissions',
            'workflows',
            'statuses',
            'tab'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(auth()->user()->role->key !== 'PEMOHON', 403);

        $categories = Category::all();

        return view('submissions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubmissionRequest $request)
    {
        DB::transaction(function () use ($request) {

            $transactionDetails = $request->input('transactionDetails');

            $total = collect($transactionDetails)->sum('amount');

            $submission = Submission::create([
                'user_id'         => Auth::id(),
                'applicant_name'  => $request->applicant_name,
                'company_name'    => $request->company_name,
                'submission_date' => $request->submission_date,
                'total'           => $total,
                'status_id'       => Status::where('key', 'PENDING')->value('id'),
                'workflow_id'     => Workflow::where('key', 'WAITING_P1_APPROVAL')->value('id'),
            ]);

            foreach ($transactionDetails as $index => $detail) {

                $filePath = null;

                if ($request->hasFile("transactionDetails.$index.file")) {
                    $file = $request->file("transactionDetails.$index.file");

                    $filePath = $file->store(
                        'submission-files',
                        'public'
                    );
                }

                $submission->transactionDetails()->create([
                    'category_id'             => $detail['category_id'],
                    'description'             => $detail['description'],
                    'amount'                  => $detail['amount'],
                    'reference'               => $detail['reference'],
                    'counterparty'            => $detail['counterparty'],
                    'bank_name'               => $detail['bank_name'],
                    'account_number'          => $detail['account_number'],
                    'account_name'            => $detail['account_name'],
                    'planned_date'            => $detail['planned_date'],
                    'recognized_transaction'  => $detail['recognized_transaction'],
                    'note'                    => $detail['note'],
                    'file'                    => $filePath
                ]);
            }
        });

        return redirect()->route('submissions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();

        $query = Submission::with([
            'transactionDetails.category',
            'workflow',
            'status',
            'submissionApprovals.user'
        ]);

        if ($user->role->key === 'PEMOHON') {
            $query->where('user_id', $user->id);
        }

        if (in_array($user->role->key, [
            'PEJABAT1',
            'PEJABAT2',
            'PEJABAT3',
            'PEJABAT4'
        ])) {

            $maxStepOrder = Workflow::max('step_order');

            $query->where(function ($q) use ($user, $maxStepOrder) {
                $q->where(function ($x) use ($user) {
                    $x->whereHas('workflow', function ($w) use ($user) {
                        $w->where('role_id', $user->role_id);
                    });

                    $x->whereHas('status', function ($s) {
                        $s->whereIn('key', ['PENDING', 'REVISION']);
                    });
                })

                    ->orWhere(function ($x) use ($maxStepOrder) {
                        $x->whereHas('workflow', function ($w) use ($maxStepOrder) {
                            $w->where('step_order', $maxStepOrder);
                        });

                        $x->whereHas('status', function ($s) {
                            $s->where('key', 'COMPLETED');
                        });
                    });
            });
        }

        $submission = $query->findOrFail($id);

        $workflows = Workflow::orderBy('step_order')->get();
        $statuses = Status::select('id', 'key', 'label')->get();

        return view('submissions.show', compact('submission', 'workflows', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $submission = Submission::with('transactionDetails')->findOrFail($id);

        $categories = Category::orderBy('name')->get();

        return view('submissions.edit', compact('submission', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubmissionRequest $request, string $id)
    {
        $submission = Submission::with('transactionDetails')->findOrFail($id);

        DB::transaction(function () use ($request, $submission) {

            $submission->update([
                'applicant_name'  => $request->applicant_name,
                'company_name'    => $request->company_name,
                'submission_date' => $request->submission_date,
            ]);

            $transactionDetails = $request->validated()['transactionDetails'] ?? [];

            $requestIds = collect($transactionDetails)
                ->pluck('id')
                ->filter()
                ->values()
                ->toArray();

            $submission->transactionDetails
                ->whereNotIn('id', $requestIds)
                ->each(function ($item) {
                    if ($item->file) {
                        Storage::disk('public')->delete($item->file);
                    }
                    $item->delete();
                });

            foreach ($transactionDetails as $phpIndex => $detail) {

                $transaction = !empty($detail['id'])
                    ? $submission->transactionDetails()->find($detail['id'])
                    : null;

                if (!$transaction) {
                    $transaction = new TransactionDetail();
                }

                $file = $request->file('transactionDetails.' . $phpIndex . '.file');

                if ($file && $file->isValid()) {
                    if ($transaction->file) {
                        Storage::disk('public')->delete($transaction->file);
                    }
                    $transaction->file = $file->store('submission-files', 'public');
                }

                $transaction->fill([
                    'submission_id'          => $submission->id,
                    'category_id'            => $detail['category_id'] ?? null,
                    'description'            => $detail['description'] ?? null,
                    'amount'                 => $detail['amount'] ?? 0,
                    'reference'              => $detail['reference'] ?? null,
                    'counterparty'           => $detail['counterparty'] ?? null,
                    'bank_name'              => $detail['bank_name'] ?? null,
                    'account_number'         => $detail['account_number'] ?? null,
                    'account_name'           => $detail['account_name'] ?? null,
                    'planned_date'           => $detail['planned_date'] ?? null,
                    'recognized_transaction' => $detail['recognized_transaction'] ?? null,
                    'note'                   => $detail['note'] ?? null,
                ]);

                $transaction->save();
            }

            $submission->update([
                'total' => $submission->transactionDetails()->sum('amount'),
            ]);
        });

        return redirect()->route('submissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function approve($id)
    {
        $user = auth()->user();
        $submission = Submission::with('workflow')->findOrFail($id);
        $currentWorkflow = $submission->workflow;

        if ($currentWorkflow->role_id !== $user->role_id) {
            abort(403);
        }

        DB::transaction(function () use ($submission, $currentWorkflow, $user) {

            $nextWorkflow = Workflow::where('step_order', '>', $currentWorkflow->step_order)
                ->orderBy('step_order')
                ->first();

            if ($nextWorkflow) {
                $submission->update([
                    'workflow_id' => $nextWorkflow->id,
                    'status_id' => Status::where('key', 'PENDING')->first()->id,
                ]);
            } else {
                $submission->update([
                    'status_id' => Status::where('key', 'COMPLETED')->first()->id,
                ]);
            }

            $allowedRoles = ['PEJABAT3', 'PEJABAT4'];
            if (in_array($user->role->key, $allowedRoles)) {
                $hasP4 = SubmissionApproval::where('submission_id', $submission->id)
                    ->whereHas('user', function ($q) {
                        $q->whereHas('role', function ($r) {
                            $r->where('key', 'PEJABAT4');
                        });
                    })
                    ->exists();

                if (!($user->role->key === 'PEJABAT3' && !$hasP4)) {

                    SubmissionApproval::create([
                        'submission_id' => $submission->id,
                        'approved_by' => $user->id,
                    ]);
                }
            }
        });

        return redirect()->route('submissions.index');
    }

    public function reject($id)
    {
        $user = auth()->user();
        $submission = Submission::with('workflow')->findOrFail($id);
        $currentWorkflow = $submission->workflow;

        if ($currentWorkflow->role_id !== $user->role_id) {
            abort(403);
        }

        DB::transaction(function () use ($submission) {
            $submission->update([
                'status_id' => Status::where('key', 'REJECTED')->value('id'),
            ]);
        });

        return redirect()->route('submissions.index');
    }

    public function revision($id)
    {
        $user = auth()->user();
        $submission = Submission::with('workflow')->findOrFail($id);

        if ($submission->workflow->role_id !== $user->role_id) {
            abort(403);
        }

        DB::transaction(function () use ($submission) {

            $submission->update([
                'status_id' => Status::where('key', 'REVISION')->value('id')
            ]);

        });

        return redirect()->route('submissions.index');
    }

    public function reapply($id)
    {
        $user = auth()->user();
        $old = Submission::with('transactionDetails')->findOrFail($id);

        if ($old->user_id !== $user->id) {
            abort(403);
        }

        if ($old->status->key !== 'REJECTED') {
            abort(403);
        }

        DB::transaction(function () use ($old, $user) {
            $firstWorkflow = Workflow::orderBy('step_order')->first();

            $new = Submission::create([
                'user_id'         => $user->id,
                'applicant_name'  => $old->applicant_name,
                'company_name'    => $old->company_name,
                'submission_date' => $old->submission_date,
                'total'           => $old->total,
                'status_id'       => Status::where('key', 'PENDING')->value('id'),
                'workflow_id'     => Workflow::where('key', 'WAITING_P1_APPROVAL')->value('id'),
                'reapply_from_id' => $old->id,
            ]);

            foreach ($old->transactionDetails as $detail) {
                $new->transactionDetails()->create([
                    'category_id' => $detail->category_id,
                    'description' => $detail->description,
                    'amount' => $detail->amount,
                    'reference' => $detail->reference,
                    'counterparty' => $detail->counterparty,
                    'bank_name'              => $detail->bank_name,
                    'account_number'         => $detail->account_number,
                    'account_name'           => $detail->account_name,
                    'planned_date' => $detail->planned_date,
                    'recognized_transaction' => $detail->recognized_transaction,
                    'note' => $detail->note,
                    'file' => $detail->file,
                ]);
            }
        });

        return redirect()->route('submissions.index');
    }
}
