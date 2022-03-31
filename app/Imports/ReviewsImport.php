<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Review;
use App\Models\User;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReviewsImport implements ToCollection, WithHeadingRow
{
    public function collection($rows)
    {
        foreach ($rows as $row)
        {
           $review = Review::create([
               'review'=>$row['review_content'],
                'rating'=>$row['review_score'],
                'backup_name'=>$row['backup_name'],
                'backup_email'=>$row['backup_email'],
               'created_at'=>$row['backup_date']
            ]);
            $product=Product::where('title','=',$row['prod_title'])->first();
            if($product != null){
                ProductReview::create([
                   'product_id'=>$product->id,
                   'review_id'=>$review->id
                ]);

            }
		}
    }
}
