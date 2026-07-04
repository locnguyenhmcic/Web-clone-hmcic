<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Kho lưu trữ tài liệu chuyên ngành xây dựng',
                'slug' => 'kho-luu-tru-tai-lieu-xay-dung',
                'excerpt' => 'Dự án xây dựng kho lưu trữ tài liệu chuyên ngành',
                'content' => '<p>Dự án này nhằm mục đích xây dựng một kho lưu trữ tài liệu chuyên ngành xây dựng hiện đại.</p>',
                'status' => 'published',
                'featured' => true,
                'published_at' => now()->subMonths(3),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Nhà kho 400 Nguyễn Duy',
                'slug' => 'nha-kho-400-nguyen-duy',
                'excerpt' => 'Dự án xây dựng nhà kho tại 400 đường Nguyễn Duy',
                'content' => '<p>Nhà kho 400 Nguyễn Duy là một dự án xây dựng quy mô lớn với diện tích rộng lớn.</p>',
                'status' => 'published',
                'featured' => true,
                'published_at' => now()->subMonths(2),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Chợ Bình Điền',
                'slug' => 'cho-binh-dien',
                'excerpt' => 'Dự án xây dựng và cải tạo chợ Bình Điền',
                'content' => '<p>Chợ Bình Điền là một trong những dự án quy mô lớn được chúng tôi giám định và quản lý.</p>',
                'status' => 'published',
                'featured' => true,
                'published_at' => now()->subMonths(1),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Trung tâm thương mại BMC Vinh',
                'slug' => 'trung-tam-thuong-mai-bmc-vinh',
                'excerpt' => 'Trung tâm thương mại và chung cư cao tầng BMC Vinh',
                'content' => '<p>Dự án phức hợp thương mại kết hợp chung cư cao tầng tại TP Vinh.</p>',
                'status' => 'published',
                'featured' => false,
                'published_at' => now()->subDays(15),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Trung tâm bồi dưỡng kiến thức phụ nữ',
                'slug' => 'trung-tam-boi-duong-kien-thuc-phu-nu',
                'excerpt' => 'Dự án xây dựng trung tâm bồi dưỡng kiến thức phụ nữ',
                'content' => '<p>Trung tâm bồi dưỡng kiến thức phụ nữ là dự án có mục đích xã hội cao.</p>',
                'status' => 'published',
                'featured' => false,
                'published_at' => now()->subDays(10),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Dự án xây dựng khu thương mại quốc tế',
                'slug' => 'du-an-khu-thuong-mai-quoc-te',
                'excerpt' => 'Khu thương mại quốc tế quy mô lớn',
                'content' => '<p>Dự án này là khu thương mại quốc tế với quy mô lớn và hạ tầng hiện đại.</p>',
                'status' => 'published',
                'featured' => false,
                'published_at' => now()->subDays(5),
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}

