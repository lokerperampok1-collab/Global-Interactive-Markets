<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserInvestment;
use Illuminate\Support\Facades\DB;

trait AutoMaturity
{
    /**
     * Check and settle matured investments for a user.
     *
     * @param User $user
     * @return array Array with count and total settled amount.
     */
    public function checkMaturity(User $user): array
    {
        $now = now();
        $maturedInvestments = UserInvestment::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('end_at', '<=', $now)
            ->get();

        if ($maturedInvestments->isEmpty()) {
            return ['count' => 0, 'total' => 0.00];
        }

        $count = 0;
        $totalAmount = 0.00;

        DB::transaction(function () use ($user, $maturedInvestments, &$count, &$totalAmount) {
            // Lock the user's wallet
            $wallet = $user->wallet()->lockForUpdate()->first();
            if (!$wallet) {
                return;
            }

            foreach ($maturedInvestments as $investment) {
                // Update investment status
                $investment->update([
                    'status' => 'completed'
                ]);

                // Create profit transaction
                $user->transactions()->create([
                    'currency' => $wallet->currency,
                    'type' => 'profit',
                    'status' => 'approved',
                    'amount' => $investment->target_return,
                    'note' => "Investment matured: " . $investment->plan_name,
                ]);

                // Increment balance
                $wallet->increment('balance', $investment->target_return);

                $count++;
                $totalAmount += $investment->target_return;
            }
        });

        return [
            'count' => $count,
            'total' => $totalAmount,
        ];
    }
}
