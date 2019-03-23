<?php
/**
 * DeleteEmptyGroups.php
 * Copyright (c) 2019 thegrumpydictator@gmail.com
 *
 * This file is part of Firefly III.
 *
 * Firefly III is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Firefly III is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Firefly III. If not, see <http://www.gnu.org/licenses/>.
 */

namespace FireflyIII\Console\Commands\Correction;

use Exception;
use FireflyIII\Models\TransactionGroup;
use FireflyIII\Models\TransactionJournal;
use Illuminate\Console\Command;

/**
 * Class DeleteEmptyGroups
 */
class DeleteEmptyGroups extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete empty transaction groups.';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firefly-iii:delete-empty-groups';

    /**
     * Execute the console command.
     *
     * @throws Exception;
     * @return mixed
     */
    public function handle(): int
    {
        //
        $groups = array_unique(TransactionJournal::get(['transaction_group_id'])->pluck('transaction_group_id')->toArray());
        $count  = TransactionGroup::whereNull('deleted_at')->whereNotIn('id', $groups)->count();
        if (0 === $count) {
            $this->info('No empty groups.');
        }
        if ($count > 0) {
            $this->info(sprintf('Deleted %d empty groups.', $count));
            TransactionGroup::whereNull('deleted_at')->whereNotIn('id', $groups)->delete();
        }

        return 0;
    }
}