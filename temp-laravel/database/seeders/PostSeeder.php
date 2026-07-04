<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Create or get categories
        $categories = Category::firstOrCreate(
            ['slug' => 'thong-bao'],
            ['name' => 'Thông báo']
        )->id;

        $posts = [
            [
                'title' => 'Thông báo về việc báo giá cung cấp mực in',
                'slug' => 'thong-bao-ve-viec-bao-gia-cung-cap-muc-in',
                'excerpt' => 'Trung tâm Quản lý nhà và Giám định xây dựng mời các nhà cung cấp báo giá mực in.',
                'content' => '<p>Thông báo chính thức về việc mời báo giá cung cấp mực in cho các máy photocopy.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Thông báo về công trình sửa chữa quỹ nhà ở',
                'slug' => 'thong-bao-ve-cong-trinh-sua-chua-quy-nha-o',
                'excerpt' => 'Mời các nhà thầu tham gia các công trình sửa chữa quỹ nhà ở.',
                'content' => '<p>Công khai mời thầu các công trình sửa chữa quỹ nhà ở thuộc tài sản công.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Thông báo về Hồ sơ năng lực phòng thí nghiệm',
                'slug' => 'thong-bao-ve-ho-so-nang-luc-phong-thi-nghiem',
                'excerpt' => 'Công bố hồ sơ năng lực phòng thí nghiệm của trung tâm.',
                'content' => '<p>Hồ sơ năng lực phòng thí nghiệm được cập nhật và công bố công khai.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(8),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Thông báo về thẩm định giá quỹ nhà ở',
                'slug' => 'thong-bao-ve-tham-dinh-gia-quy-nha-o',
                'excerpt' => 'Mời các đơn vị tham gia báo giá thẩm định giá quỹ nhà ở.',
                'content' => '<p>Công khai mời báo giá thẩm định giá để xác định giá thuê quỹ nhà ở dôi dư.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Thông báo hoạt động của trung tâm',
                'slug' => 'thong-bao-hoat-dong-cua-trung-tam',
                'excerpt' => 'Cập nhật các hoạt động chính của trung tâm trong tháng.',
                'content' => '<p>Các hoạt động chính của trung tâm được thực hiện đúng kế hoạch đã đề ra.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Thông báo về tuyển dụng nhân sự',
                'slug' => 'thong-bao-ve-tuyen-dung-nhan-su',
                'excerpt' => 'Trung tâm tuyển dụng một số vị trí nhân sự.',
                'content' => '<p>Công khai tuyển dụng nhân sự để bổ sung đội ngũ quản lý và chuyên gia.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(20),
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($posts as $post) {
            $createdPost = Post::create($post);
            // Attach to category
            $createdPost->categories()->attach($categories);
        }
    }
}

