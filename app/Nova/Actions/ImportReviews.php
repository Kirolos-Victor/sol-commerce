<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

use Laravel\Nova\Fields\File;
use App\Imports\ReviewsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportReviews extends Action
{
    use InteractsWithQueue, Queueable;

    public $standalone = true;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
      Excel::import(new ReviewsImport, $fields->file);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
      return [
          File::make('File')
      ];
    }
}
