<?php

namespace App\Services;

use App\Models\RiceType;
use App\Models\RiceTypeRecoveryRateHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RiceTypeService
{
    public function create(array $data): RiceType
    {
        return RiceType::create($data);
    }

    public function update(RiceType $riceType, array $data, ?User $actor): RiceType
    {
        return DB::transaction(function () use ($riceType, $data, $actor): RiceType {
            $riceType = RiceType::query()->lockForUpdate()->findOrFail($riceType->id);
            $oldRate = (float) $riceType->recovery_rate;
            $newRate = (float) $data['recovery_rate'];

            $riceType->update($data);

            if (round($oldRate, 2) !== round($newRate, 2)) {
                RiceTypeRecoveryRateHistory::create([
                    'rice_type_id' => $riceType->id,
                    'old_rate' => $oldRate,
                    'new_rate' => $newRate,
                    'changed_by' => $actor?->id,
                    'changed_at' => now(),
                ]);
            }

            return $riceType->refresh();
        });
    }
}
